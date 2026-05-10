<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use App\Models\MicroSkillSubmission;
use App\Services\HolidayService;
use App\Services\TimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $nowWita = TimeService::nowWita();
        $today   = $nowWita->toDateString();

        $activeInterns = Intern::where('is_active', true)->count();
        $totalInterns  = Intern::count();

        // Daily stats (hanya untuk hari ini)
        $totalHadir = Attendance::whereDate('date', $today)->where('status', 'hadir')->count();
        $totalIzin  = Attendance::whereDate('date', $today)->where('status', 'izin')->count();
        $totalSakit = Attendance::whereDate('date', $today)->where('status', 'sakit')->count();
        $totalAlfa  = Attendance::whereDate('date', $today)->where('status', 'alfa')->count();

        // Mikro skill yang diperbarui hari ini
        $microTotal = MicroSkillSubmission::whereDate('updated_at', $today)->count();

        $todayAttendances = Attendance::whereDate('date', $today)
            ->with('intern')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $todayAbsentInterns = collect();
        if (!HolidayService::isHoliday($nowWita)) {
            $presentIds = Attendance::whereDate('date', $today)->pluck('intern_id')->toArray();
            $todayAbsentInterns = Intern::where('is_active', true)
                ->whereNotIn('id', $presentIds)
                ->orderBy('name')
                ->get();
        }

        $topMicroSkills = Intern::leftJoin('micro_skill_submissions', 'interns.id', '=', 'micro_skill_submissions.intern_id')
            ->select('interns.id as intern_id', 'interns.name', 'interns.institution', 'interns.photo_path', DB::raw('COUNT(micro_skill_submissions.id) as total'))
            ->groupBy('interns.id', 'interns.name', 'interns.institution', 'interns.photo_path')
            ->orderByDesc('total')
            ->orderBy('interns.name')
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

        return view('admin.dashboard', compact(
            'activeInterns',
            'totalInterns',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlfa',
            'microTotal',
            'todayAttendances',
            'todayAbsentInterns',
            'today',
            'topMicroSkills'
        ));
    }
}