<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MicroSkillLeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Intern::join('users', 'interns.user_id', '=', 'users.id')
            ->leftJoin(
                'micro_skill_submissions',
                'interns.id',
                '=',
                'micro_skill_submissions.intern_id'
            )
            ->select(
                'interns.id as intern_id',
                'users.name',
                'interns.institution',
                'interns.photo_path',
                'interns.is_active',
                DB::raw('COUNT(micro_skill_submissions.id) as total')
            );

        // Filter status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('interns.is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('interns.is_active', false);
            }
        }

        // Search nama
        if ($request->filled('search')) {
            $query->where('users.name', 'like', '%' . trim($request->search) . '%');
        }

        $rows = $query
            ->groupBy(
                'interns.id',
                'users.name',
                'interns.institution',
                'interns.photo_path',
                'interns.is_active'
            )
            ->orderByDesc('total')
            ->orderBy('users.name')
            ->paginate(20)
            ->withQueryString();

        return view('intern.microskill.leaderboard', compact('rows'));
    }
}