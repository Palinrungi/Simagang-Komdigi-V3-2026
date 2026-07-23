<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Logbook;
use App\Models\MicroSkillSubmission;
use App\Services\HolidayService;
use App\Services\TimeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $mentor  = $user->mentor;
        $nowWita = TimeService::nowWita();
        $today   = $nowWita->toDateString();

        $interns = $mentor ? $mentor->interns()->where('is_active', true)
            ->withCount(['attendances', 'microSkills'])
            ->with(['attendances' => function ($q) use ($today) {
                $q->whereDate('date', $today);
            }])->get() : collect();

        $alumni = $mentor ? $mentor->interns()
            ->where('is_active', false)
            ->withCount(['attendances', 'microSkills'])
            ->get() : collect();

        $todayAttendances = Attendance::whereIn('intern_id', $interns->pluck('id')->toArray() ?: [0])
            ->whereDate('date', $today)
            ->with('intern')
            ->orderBy('created_at', 'desc')
            ->get();

        $todayAbsentInterns = collect();
        if (!HolidayService::isHoliday($nowWita) && $mentor) {
            $presentIds = $todayAttendances->pluck('intern_id')->toArray();
            $todayAbsentInterns = $interns->whereNotIn('id', $presentIds);
        }

        $internIds = $interns->pluck('id');
        $microPending = MicroSkillSubmission::whereIn('intern_id', $internIds)->where('status', 'pending')->count();
        $microTodayTotal = MicroSkillSubmission::whereIn('intern_id', $internIds)
            ->whereDate('submitted_at', $today)
            ->count();

        // Get logbooks submitted today by interns (all)
        $todayLogbooks = Logbook::whereIn('intern_id', $internIds ?: [0])
            ->whereDate('date', $today)
            ->with('intern')
            ->orderByDesc('created_at')
            ->get();

        // Pending logbooks (not approved) — used to trigger alerts / review list
        $todayLogbooksPending = Logbook::whereIn('intern_id', $internIds ?: [0])
            ->whereDate('date', $today)
            ->where(function ($q) {
                $q->whereNull('approval_status')->orWhere('approval_status', '!=', 'approved');
            })
            ->with('intern')
            ->orderByDesc('created_at')
            ->get();

        $topMicroSkills = \App\Models\Intern::join('users', 'interns.user_id', '=', 'users.id')
            ->leftJoin('micro_skill_submissions', 'interns.id', '=', 'micro_skill_submissions.intern_id')
            ->whereIn('interns.id', $internIds)
            ->select('interns.id as intern_id', 'users.name', 'interns.institution', 'interns.photo_path', DB::raw('COUNT(micro_skill_submissions.id) as total'))
            ->groupBy('interns.id', 'users.name', 'interns.institution', 'interns.photo_path')
            ->orderByDesc('total')
            ->orderBy('users.name')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'intern_id' => $row->intern_id,
                    'name' => $row->name,
                    'institution' => $row->institution,
                    'photo_path' => $row->photo_path,
                    'total' => (int)$row->total,
                ];
            });

        return view('mentor.dashboard', compact('mentor', 'interns', 'alumni', 'todayAttendances', 'todayAbsentInterns', 'today', 'microPending', 'microTodayTotal', 'topMicroSkills', 'todayLogbooks', 'todayLogbooksPending'));
    }
}


