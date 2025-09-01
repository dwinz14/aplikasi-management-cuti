<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Division;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Cache divisions untuk 1 jam
        $divisions = Cache::remember('divisions_list', 3600, function () {
            return Division::select('id', 'nama_divisi')->get();
        });

        // Validasi input
        $validated = $request->validate([
            'division_id' => 'nullable|exists:divisions,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'per_page' => 'nullable|integer|min:10|max:100'
        ]);

        $divisionId = $validated['division_id'] ?? null;
        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;
        $perPage = $validated['per_page'] ?? 10;

        // Query optimized dengan select specific columns dan eager loading
        $query = Leave::select([
            'id',
            'user_id',
            'start_date',
            'end_date',
            'total_hari',
            'alasan',
            'status_final'
        ])
            ->with([
                'user:id,name,division_id',
                'user.division:id,nama_divisi'
            ]);

        // Apply filters
        if ($divisionId) {
            $query->whereHas('user', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        }

        // Add index untuk performa jika belum ada
        // Pastikan ada index pada: leaves(start_date), users(division_id), leaves(user_id)

        $leaves = $query
            ->orderBy('start_date', 'desc')
            ->paginate($perPage)
            ->withQueryString(); // Maintain filter parameters in pagination links

        return view('hrd.rekap', compact('leaves', 'divisions', 'divisionId', 'startDate', 'endDate', 'perPage'));
    }
}
