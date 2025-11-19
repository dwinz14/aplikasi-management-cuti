<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;

class LeavePrintController extends Controller
{
    /**
     * Print (generate) PDF form cuti.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leave  $leave
     */
    public function print(Request $request, Leave $leave)
    {
        // Load relations needed for PDF
        $leave->load([
            'user.division',
            'user.position',
            'user.office',
            'leaveType',
            'pengganti',
            'approvals.approver',
            'approvalHistories.approver',
        ]);

        // Security: hanya bisa print kalau status final = approved
        if (strtolower($leave->status_final) !== 'approved') {
            abort(403, 'Form cuti hanya bisa dicetak untuk pengajuan yang sudah disetujui.');
        }

        // Prepare data for view: latest approval per step + human friendly
        $approvals = $leave->approvals()->with('approver')->orderBy('step')->get();

        // We also want a chronological list of approval actions (history)
        $histories = $leave->approvalHistories()->with('approver')->orderBy('created_at')->get();

        // Load logo as base64 for PDF compatibility
        $logoPath = public_path('img/logo.png');
        $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        $data = [
            'leave'       => $leave,
            'approvals'   => $approvals,
            'histories'   => $histories,
            'generated_at' => now()->format('d M Y H:i'),
            'logoData'    => $logoData,
        ];

        // Render blade view to PDF
        $pdf = Pdf::loadView('leaves.pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans']);

        // Generate filename
        $filename = 'form-cuti-' . $leave->id . '-' . now()->format('Ymd_His') . '.pdf';

        // Return PDF: download or stream
        return $request->query('download') === '1'
            ? $pdf->download($filename)
            : $pdf->stream($filename);
    }
}
