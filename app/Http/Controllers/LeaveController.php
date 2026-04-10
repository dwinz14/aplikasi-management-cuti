<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Models\Approval;
use App\Models\ApprovalHistory;
use App\Models\LeaveType;
use App\Models\UserLeaveBalance;
use App\Jobs\SendNotification;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('approvals.approver')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $user = Auth::user();
        $requiresReplacement = in_array($user->role, ['staff', 'kasie', 'kabag-pincab'], true);

        // $penggantiList = $requiresReplacement
        //     ? Cache::remember("pengganti_{$user->office_id}", 300, fn() =>
        //     User::select('id', 'name', 'role')->where('office_id', $user->office_id)->where('id', '!=', $user->id)->get())
        //     : collect();
        // Default query dasar
        $query = User::query()->where('id', '!=', $user->id);

        // Case 1: user role kabag-pincab & kantor pusat
        if ($user->role === 'kabag-pincab' && $user->office_id == Office::PUSAT) {
            $penggantiList = $query
                ->where('office_id', Office::PUSAT)
                ->orderBy('name')
                ->get();

            // Case 2: user role kabag-pincab tapi bukan kantor pusat
        } elseif ($user->role === 'kabag-pincab' && $user->office_id != Office::PUSAT) {
            $penggantiList = $query
                ->whereIn('role', ['kabag-pincab', 'hrd'])
                ->orderBy('name')
                ->get();

            // Case 3: role lain → tetap filter satu kantor
        } else {
            $penggantiList = $requiresReplacement
                ? Cache::remember("pengganti_{$user->office_id}", 300, fn() =>
                User::select('id', 'name', 'role')->where('office_id', $user->office_id)->where('id', '!=', $user->id)->get())
                : collect();
        }
        $requiresAtasan = !in_array($user->role, ['direksi'], true);
        $atasanList = collect();

        if ($requiresAtasan) {
            $direksi = Cache::remember('direksi_users', 300, fn() =>
            User::select('id', 'name', 'role')->where('role', 'direksi')->get());

            $atasanList = $atasanList->merge($direksi);

            $hrd = Cache::remember('hrd_users', 300, fn() =>
            User::select('id', 'name', 'role')->where('role', 'hrd')->get());

            $atasanList = $atasanList->merge($hrd);

            if ($user->role !== 'hrd') {
                $others = Cache::remember("atasan_{$user->office_id}", 300, fn() =>
                User::select('id', 'name', 'role')
                    ->where('office_id', $user->office_id)
                    ->whereIn('role', ['kabag-pincab', 'kasie'])
                    ->where('id', '!=', $user->id)
                    ->get());

                $atasanList = $atasanList->merge($others);

                $atasanList = $atasanList->unique('id')->values();
            }
        }

        // Get available leave types for the user
        $leaveTypes = LeaveType::where('is_active', true)
            ->when($user->gender, function ($query) use ($user) {
                return $query->where(function ($q) use ($user) {
                    $q->whereNull('gender')
                        ->orWhere('gender', $user->gender);
                });
            })
            ->where(function ($q) use ($user) {
                $masaKerja = $user->masaKerjaTahun();

                $q->where('min_years', 0)
                    ->orWhere('min_years', '<=', $masaKerja);
            })
            ->get();

        // Get user's leave balances for current year
        $userLeaveBalances = UserLeaveBalance::where('user_id', $user->id)
            ->where('year', now()->year)
            ->with('leaveType')
            ->get()
            ->keyBy('leave_type_id');

        return view('leaves.create', compact('penggantiList', 'requiresReplacement', 'atasanList', 'requiresAtasan', 'leaveTypes', 'userLeaveBalances', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        // Validasi masa kerja minimal untuk jenis cuti
        if ($leaveType->min_years > 0) {

            if (!$user->tanggal_aktif_kerja) {
                return back()->withErrors([
                    'leave_type_id' => 'Tanggal aktif kerja belum diatur. Hubungi Admin.'
                ])->withInput();
            }

            $masaKerjaTahun = $user->masaKerjaTahun();

            if ($masaKerjaTahun < $leaveType->min_years) {
                return back()->withErrors([
                    'leave_type_id' => "Jenis cuti ini hanya untuk karyawan dengan masa kerja minimal {$leaveType->min_years} tahun."
                ])->withInput();
            }
        }

        $isSickLeave = in_array(strtolower($leaveType->name), ['izin sakit dengan surat dokter', 'izin sakit tanpa surat dokter']);
        $requiresProof = strtolower($leaveType->name) === 'izin sakit dengan surat dokter';

        $rules = [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date|' . ($isSickLeave ? 'before_or_equal:today' : ''),
            'end_date'      => 'required|date|after_or_equal:start_date',
            'alasan'        => ['required', 'string', 'max:500', 'regex:/^[a-zA-Z0-9\s.,()-]+$/'],

            'proof_image'   => ($requiresProof ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif|max:2048',

            'pengganti_id' => in_array($user->role, ['staff', 'kasie', 'kabag-pincab'], true)
                ? 'required|exists:users,id'
                : 'nullable|exists:users,id',

            'atasan_id' => !in_array($user->role, ['direksi'], true)
                ? 'required|exists:users,id'
                : 'nullable|exists:users,id',
        ];

        $messages = [
            'leave_type_i.required' => 'Anda harus memilih jenis cuti',
            'start_date.required' => 'Anda harus memilih tanggal mulai cuti',
            'end_date.required' => 'Anda harus memilih tanggal selesai cuti',
            'alasan.required' => 'Anda harus mengisi alasan cuti',
            'proof_image.required' => 'Anda harus menyertakan bukti surat dokter',
            'pengganti_id.required' => 'Anda harus memilih pengganti',
            'pengganti_id.exists'   => 'Pengganti tidak valid',

            'atasan_id.required' => 'Anda harus memilih atasan',
            'atasan_id.exists'   => 'Atasan tidak valid',
        ];

        $request->validate($rules, $messages);

        // Validate leave type availability for user
        if ($leaveType->gender && $leaveType->gender !== $user->gender) {
            return back()->withErrors(['leave_type_id' => 'Jenis cuti ini tidak tersedia untuk Anda.'])->withInput();
        }

        $totalHari = $this->calculateWorkingDays($request->start_date, $request->end_date);

        // Check quota for non-unlimited leave types
        if ($leaveType->quota > 0) {
            $userLeaveBalance = UserLeaveBalance::where('user_id', $user->id)
                ->where('leave_type_id', $request->leave_type_id)
                ->where('year', now()->year)
                ->first();

            if (!$userLeaveBalance || $userLeaveBalance->remaining < $totalHari) {
                return back()->withErrors(['msg' => 'Kuota cuti tidak mencukupi untuk jenis cuti ini.'])->withInput();
            }
        }



        if ($this->hasOverlapLeave($user->id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Masih ada cuti aktif atau pending.']);
        }

        if ($request->pengganti_id && $this->hasOverlapLeave($request->pengganti_id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Pengganti yang dipilih sedang cuti di tanggal tersebut.']);
        }

        if ($this->hasOverlapReplacement($request->pengganti_id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Pengganti tersebut sudah ditugaskan pada cuti lain.']);
        }

        if ($this->hasOverlapReplacement($user->id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Anda sedang jadi pengganti di tanggal tersebut.']);
        }

        return DB::transaction(function () use ($request, $user, $totalHari, $leaveType, $isSickLeave) {
            $proofImagePath = null;
            if ($request->hasFile('proof_image')) {
                $proofImagePath = $request->file('proof_image')->store('proof_images', 'public');
            }

            $leave = Leave::create([
                'user_id'       => $user->id,
                'leave_type_id' => $request->leave_type_id,
                'pengganti_id'   => $request->pengganti_id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'total_hari'    => $totalHari,
                'alasan'        => $request->alasan,
                'proof_image'   => $proofImagePath,
                'status_final'   => 'pending',
                'is_mendadak' => !$isSickLeave && \Carbon\Carbon::parse($request->start_date)->lt(\Carbon\Carbon::today()->addWeek()),
            ]);

            if ($user->role === 'direksi') {
                $leave->update(['status_final' => 'approved']);

                // Update leave balance for non-unlimited leave types
                if ($leaveType->quota > 0) {
                    $userLeaveBalance = UserLeaveBalance::where('user_id', $user->id)
                        ->where('leave_type_id', $request->leave_type_id)
                        ->where('year', now()->year)
                        ->first();

                    if ($userLeaveBalance) {
                        $userLeaveBalance->increment('used', $totalHari);
                        $userLeaveBalance->decrement('remaining', $totalHari);
                    }
                }

                ApprovalHistory::create([
                    'leave_id'    => $leave->id,
                    'approved_by' => $user->id,
                    'role'        => $user->role,
                    'status'      => 'approved',
                ]);

                return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti disetujui otomatis.');
            }

            $approvers = collect([$request->pengganti_id, $request->atasan_id])
                ->filter()
                ->unique()
                ->values();

            foreach ($approvers as $index => $approverId) {
                Approval::create([
                    'leave_id'    => $leave->id,
                    'approver_id' => $approverId,
                    'step'        => $index + 1,
                    'status'      => 'pending',
                ]);
            }

            SendNotification::dispatch(
                $approvers->first(),
                'leave_request',
                'Pengajuan Cuti Baru',
                "Pengajuan cuti dari {$user->name} membutuhkan persetujuan Anda.",
                ['leave_id' => $leave->id, 'requester_id' => $user->id]
            );

            return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dibuat.');
        });
    }

    private function hasOverlapLeave($userId, $start, $end)
    {
        return Leave::where('user_id', $userId)
            ->whereIn('status_final', ['pending', 'approved'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();
    }

    private function hasOverlapReplacement($replacementId, $start, $end)
    {
        return Leave::where('pengganti_id', $replacementId)
            ->whereNotIn('status_final', ['rejected'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    public function replacements()
    {
        $leaves = Leave::with('user')->where('pengganti_id', Auth::id())->latest()->paginate(10);
        return view('replacements.index', compact('leaves'));
    }

    private function calculateWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;

        while ($start <= $end) {
            // Check if it's a weekday (Monday to Friday)
            if ($start->dayOfWeek !== Carbon::SATURDAY && $start->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $start->addDay();
        }

        return $workingDays;
    }

    public function acceptRevision(Leave $leave)
    {
        abort_unless($leave->user_id === Auth::id(), 403);
        abort_unless($leave->is_revision_pending, 400);

        $approval = $leave->revisionApproval;

        if (!$approval || !$approval->revised_start_date) {
            return back()->withErrors(['msg' => 'Data revisi tidak ditemukan.']);
        }

        // Update tanggal leave dengan tanggal revisi
        $leave->update([
            'start_date' => $approval->revised_start_date,
            'end_date' => $approval->revised_end_date,
            'total_hari' => $approval->revised_total_hari,
            'is_revision_pending' => false,
            'revision_by_approval_id' => null,
        ]);

        // Approve approval yang meminta revisi
        $approval->update([
            'status' => 'approved',
        ]);

        // Update history status for the approver
        ApprovalHistory::where('leave_id', $leave->id)
            ->where('approved_by', $approval->approver_id)
            ->where('status', 'revision_requested')
            ->update(['status' => 'revision_accepted']);

        ApprovalHistory::create([
            'leave_id'    => $leave->id,
            'approved_by' => Auth::id(),
            'role'        => Auth::user()->role,
            'status'      => 'revision_accepted',
            'catatan'     => "Pemohon menyetujui revisi tanggal: {$approval->revised_start_date} s/d {$approval->revised_end_date}",
        ]);

        // Kirim notifikasi ke approver
        SendNotification::dispatch(
            $approval->approver_id,
            'revision_accepted',
            'Revisi Diterima',
            Auth::user()->name . " menyetujui revisi tanggal cuti yang Anda ajukan.",
            ['leave_id' => $leave->id]
        );

        // Cek apakah ada approver berikutnya
        $nextApproval = $leave->approvals()->where('step', '>', $approval->step)->orderBy('step')->first();

        if ($nextApproval) {
            // Kirim notifikasi ke approver berikutnya
            SendNotification::dispatch(
                $nextApproval->approver_id,
                'leave_request',
                'Pengajuan Cuti Baru',
                "Pengajuan cuti dari {$leave->user->name} membutuhkan persetujuan Anda.",
                ['leave_id' => $leave->id, 'requester_id' => $leave->user_id]
            );
        } else {
            // Final approve
            $this->finalApproveLeave($leave);
        }

        return redirect()->route('cuti.index')->with('success', 'Revisi tanggal telah diterima dan cuti disetujui.');
    }

    public function rejectRevision(Leave $leave)
    {
        abort_unless($leave->user_id === Auth::id(), 403);
        abort_unless($leave->is_revision_pending, 400);

        $approval = $leave->revisionApproval;

        // Update status
        $leave->update([
            'status_final' => 'rejected',
            'is_revision_pending' => false,
            'revision_by_approval_id' => null,
        ]);

        $approval->update([
            'status' => 'rejected',
        ]);

        // Update history status for the approver
        ApprovalHistory::where('leave_id', $leave->id)
            ->where('approved_by', $approval->approver_id)
            ->where('status', 'revision_requested')
            ->update(['status' => 'revision_rejected']);

        ApprovalHistory::create([
            'leave_id'    => $leave->id,
            'approved_by' => Auth::id(),
            'role'        => Auth::user()->role,
            'status'      => 'revision_rejected',
            'catatan'     => "Pemohon menolak revisi tanggal dari " . $approval->approver->name,
        ]);

        // Kirim notifikasi ke approver
        SendNotification::dispatch(
            $approval->approver_id,
            'revision_rejected',
            'Revisi Ditolak',
            Auth::user()->name . " menolak revisi tanggal cuti yang Anda ajukan. Pengajuan cuti dibatalkan.",
            ['leave_id' => $leave->id]
        );

        return redirect()->route('cuti.index')->with('error', 'Revisi tanggal ditolak. Pengajuan cuti dibatalkan.');
    }

    private function finalApproveLeave($leave)
    {
        $leave->update(['status_final' => 'approved']);

        $leaveType = $leave->leaveType;

        // Potong cuti jika ada quota
        if ($leaveType->quota > 0) {
            $userLeaveBalance = \App\Models\UserLeaveBalance::where('user_id', $leave->user_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', now()->year)
                ->first();

            if ($userLeaveBalance) {
                $userLeaveBalance->increment('used', $leave->total_hari);
                $userLeaveBalance->decrement('remaining', $leave->total_hari);
            } else {
                \App\Models\UserLeaveBalance::create([
                    'user_id' => $leave->user_id,
                    'leave_type_id' => $leave->leave_type_id,
                    'year' => now()->year,
                    'allocated' => $leaveType->quota ?? 0,
                    'used' => $leave->total_hari,
                    'remaining' => ($leaveType->quota ?? 0) - $leave->total_hari,
                ]);
            }
        }

        SendNotification::dispatch(
            $leave->user_id,
            'leave_final_approved',
            'Cuti Final Disetujui',
            "Pengajuan cuti Anda telah disetujui secara final dan cuti telah dipotong dari kuota Anda.",
            ['leave_id' => $leave->id]
        );
    }
}
