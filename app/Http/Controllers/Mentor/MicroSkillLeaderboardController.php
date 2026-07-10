<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MicroSkillLeaderboardController extends Controller
{
    public function index()
    {
        $mentor = Auth::user()->mentor;
        $internIds = $mentor ? $mentor->interns()->pluck('id') : collect([]);

        $rows = \App\Models\Intern::join('users', 'interns.user_id', '=', 'users.id')
            ->leftJoin('micro_skill_submissions', 'interns.id', '=', 'micro_skill_submissions.intern_id')
            ->whereIn('interns.id', $internIds)
            ->select('interns.id as intern_id', 'users.name', 'interns.institution', 'interns.photo_path', DB::raw('COUNT(micro_skill_submissions.id) as total'))
            ->groupBy('interns.id', 'users.name', 'interns.institution', 'interns.photo_path')
            ->orderByDesc('total')
            ->orderBy('users.name')
            ->paginate(20);

        return view('mentor.microskill.leaderboard', compact('rows'));
    }
}


