<?php

namespace App\Http\Controllers\Industri;

use App\Http\Controllers\Controller;
use App\Models\FinalReport;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndustriReportController extends Controller
{
    private function getInternIds(): \Illuminate\Support\Collection
    {
        $industri = Auth::user()->industri;

        if (!$industri) {
            return collect();
        }

        return DB::table('interns')
            ->join('users', 'users.id', '=', 'interns.user_id')
            ->join('pengajuan_details', 'pengajuan_details.email', '=', 'users.email')
            ->join('pengajuans', 'pengajuans.id', '=', 'pengajuan_details.pengajuan_id')
            ->join('lowongans', 'lowongans.id', '=', 'pengajuans.lowongan_id')
            ->where('lowongans.industri_id', $industri->id)
            ->where('interns.is_active', true)
            ->distinct()
            ->pluck('interns.id');
    }

    public function index(Request $request)
    {
        $internIds = $this->getInternIds();

        $query = FinalReport::with('intern')
            ->whereIn('intern_id', $internIds);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->integer('intern_id'));
        }

        $reports = $query
            ->orderByDesc('submitted_at')
            ->paginate(20)
            ->withQueryString();

        $interns = Intern::whereIn('id', $internIds)
            ->join('users', 'interns.user_id', '=', 'users.id')->select('interns.*')->orderBy('users.name')
            ->get();

        return view('industri.report.index', compact(
            'reports',
            'interns'
        ));
    }

    public function show(FinalReport $report)
    {
        $internIds = $this->getInternIds();

        abort_unless(
            $internIds->contains($report->intern_id),
            403
        );

        $report->load('intern');

        return view('industri.report.show', compact('report'));
    }

    public function updateStatus(Request $request, FinalReport $report)
    {
        $internIds = $this->getInternIds();

        abort_unless(
            $internIds->contains($report->intern_id),
            403
        );

        $validated = $request->validate([
            'status'     => ['required', 'in:approved,rejected,revised'],
            'admin_note' => ['nullable', 'string'],
            'score'      => ['required_if:status,approved', 'nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $data = [
            'admin_note' => $validated['admin_note'] ?? null,
        ];

        if ($validated['status'] === 'approved') {
            $score = (int) $validated['score'];
            $grade = match (true) {
                $score >= 85 => 'A',
                $score >= 70 => 'B',
                default      => 'C',
            };

            $data['status']         = 'approved';
            $data['needs_revision'] = false;
            $data['score']          = $score;
            $data['grade']          = $grade;

            $successMessage = 'Laporan disetujui dengan nilai ' . $grade . ' (Score: ' . $score . ')';

        } elseif ($validated['status'] === 'revised') {
            $data['status']         = 'pending';
            $data['needs_revision'] = true;

            $successMessage = 'Laporan dikembalikan untuk revisi.';

        } else {
            $data['status']         = 'rejected';
            $data['needs_revision'] = false;
            $data['score']          = null;
            $data['grade']          = null;

            $successMessage = 'Laporan berhasil ditolak.';
        }

        $report->update($data);

        return back()->with('success', $successMessage);
    }
}