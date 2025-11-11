<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Position;
use App\Models\Office;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $positions = Position::all();
        $offices = Office::all();
        $leaveTypes = LeaveType::all();

        $positionId = $request->get('position_id');
        $officeId = $request->get('office_id');
        $leaveTypeId = $request->get('leave_type_id');

        // Validasi input
        $validated = $request->validate([
            'position_id' => 'nullable|exists:positions,id',
            'office_id' => 'nullable|exists:offices,id',
            'leave_type_id' => 'nullable|exists:leave_types,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'per_page' => 'nullable|integer|min:10|max:100'
        ]);

        $positionId = $validated['position_id'] ?? null;
        $officeId = $validated['office_id'] ?? null;
        $leaveTypeId = $validated['leave_type_id'] ?? null;
        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;
        $perPage = $validated['per_page'] ?? 15;

        // Query optimized dengan select specific columns dan eager loading
        $query = Leave::select([
            'id',
            'user_id',
            'leave_type_id',
            'start_date',
            'end_date',
            'total_hari',
            'alasan',
            'status_final'
        ])
            ->with([
                'user:id,name,position_id,office_id',
                'user.position:id,nama_jabatan',
                'user.office:id,nama_kantor',
                'leaveType:id,name'
            ]);

        // Apply filters
        if ($positionId) {
            $query->whereHas('user', function ($q) use ($positionId) {
                $q->where('position_id', $positionId);
            });
        }

        if ($officeId) {
            $query->whereHas('user', function ($q) use ($officeId) {
                $q->where('office_id', $officeId);
            });
        }

        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        }

        $leaves = $query
            ->orderBy('start_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('hrd.rekap', compact('leaves', 'positions', 'offices', 'leaveTypes', 'positionId', 'officeId', 'leaveTypeId', 'startDate', 'endDate', 'perPage'));
    }
}
