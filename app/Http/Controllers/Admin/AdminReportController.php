<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinalReport;
use App\Models\Industri;
use App\Models\Intern;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_reports')->only(['index', 'show']);
        $this->middleware('permission:manage_reports')->only(['updateStatus']);
    }

    private function adminInternIds(): \Illuminate\Support\Collection
    {
        $komdigi = Industri::where('nama_industri', 'BBLSDM Komdigi Makassar')->first();

        return Intern::where(function ($q) use ($komdigi) {
            $q->whereNull('pengajuan_detail_id');

            if ($komdigi) {
                $q->orWhereHas('pengajuanDetail.pengajuan.lowongan', function ($lq) use ($komdigi) {
                    $lq->where('industri_id', $komdigi->id);
                });
            }
        })->pluck('id');
    }

    public function index(Request $request)
    {
        $internIds = $this->adminInternIds();

        $query = FinalReport::with('intern')
            ->whereIn('intern_id', $internIds);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->intern_id);
        }

        $reports = $query->orderBy('submitted_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Dropdown hanya menampilkan intern aktif milik admin
        $interns = Intern::whereIn('id', $internIds)
            ->where('is_active', true)
            ->orderBy(\App\Models\User::select('name')->whereColumn('users.id', 'interns.user_id'), 'asc')
            ->get();

        return view('admin.report.index', compact('reports', 'interns'));
    }

    public function show(FinalReport $report)
    {
        abort_unless(
            $this->adminInternIds()->contains($report->intern_id),
            403
        );

        $this->authorize('view', $report);

        $report->load('intern');

        return view('admin.report.show', compact('report'));
    }

    public function updateStatus(Request $request, FinalReport $report)
    {
        abort_unless(
            $this->adminInternIds()->contains($report->intern_id),
            403
        );

        $this->authorize('grade', $report);

        $validated = $request->validate([
            'status'     => ['required', 'in:approved,rejected,revised'],
            'admin_note' => ['nullable', 'string'],
        ]);

        if (
            $validated['status'] === 'approved' &&
            is_null($report->mentor_score)
        ) {
            return back()->with(
                'error',
                'Laporan tidak dapat disetujui sebelum mentor memberikan nilai.'
            );
        }

        $data = [
            'admin_note' => $validated['admin_note'] ?? null,
        ];

        if ($validated['status'] === 'approved') {
            $data['status']        = 'approved';
            $data['needs_revision'] = false;
        } elseif ($validated['status'] === 'revised') {
            $data['status']        = 'pending';
            $data['needs_revision'] = true;
        } else {
            $data['status']        = 'rejected';
            $data['needs_revision'] = false;
        }

        $report->update($data);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}