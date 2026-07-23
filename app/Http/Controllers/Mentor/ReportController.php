<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\FinalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $mentor = Auth::user()->mentor;

        $query = FinalReport::query()
            ->with('intern')
            ->when($mentor, function ($q) use ($mentor) {
                $q->whereIn('intern_id', $mentor->interns()->pluck('id'));
            });

        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->integer('intern_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('needs_revision')) {
            $query->where('needs_revision', $request->boolean('needs_revision'));
        }

        $reports = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $interns = $mentor ? $mentor->interns()->join('users', 'interns.user_id', '=', 'users.id')->orderBy('users.name')->select('interns.*')->get() : collect();

        return view('mentor.report.index', compact('mentor', 'reports', 'interns'));
    }

    public function show(FinalReport $report)
    {
        $this->authorize('view', $report);

        $report->load('intern');
        return view('mentor.report.show', compact('report'));
    }

    public function grade(Request $request, FinalReport $report)
    {
        $this->authorize('grade', $report);

        $validated = $request->validate([
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'mentor_note' => ['nullable', 'string'],
        ]);

        // Convert score to grade
        $score = $validated['score'];
        $grade = 'C'; // default
        if ($score >= 85) {
            $grade = 'A';
        } elseif ($score >= 70) {
            $grade = 'B';
        }

        $report->update([
            'score' => $score,
            'grade' => $grade,
            'admin_note' => $validated['mentor_note'] ?? null,
            'status' => 'approved',
            'needs_revision' => false,
        ]);

        return back()->with('success', 'Nilai berhasil diberikan: ' . $grade . ' (Score: ' . $score . ')');
    }
}


