<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Industri;
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

        $komdigi = Industri::where('nama_industri', 'BBLSDM Komdigi Makassar')->first();

        $baseInternQuery = Intern::where(function ($q) use ($komdigi) {
            $q->whereNull('pengajuan_detail_id');

            if ($komdigi) {
                $q->orWhereHas('pengajuanDetail.pengajuan.lowongan', function ($query) use ($komdigi) {
                    $query->where('industri_id', $komdigi->id);
                });
            }
        });

        $activeInterns = (clone $baseInternQuery)->where('is_active', true)->count();
        $totalInterns  = (clone $baseInternQuery)->count();

        $adminInternIds = (clone $baseInternQuery)->pluck('id');

        $totalHadir = Attendance::whereDate('date', $today)
            ->where('status', 'hadir')
            ->whereIn('intern_id', $adminInternIds)
            ->count();

        $totalIzin = Attendance::whereDate('date', $today)
            ->where('status', 'izin')
            ->whereIn('intern_id', $adminInternIds)
            ->count();

        $totalSakit = Attendance::whereDate('date', $today)
            ->where('status', 'sakit')
            ->whereIn('intern_id', $adminInternIds)
            ->count();

        $totalAlfa = Attendance::whereDate('date', $today)
            ->where('status', 'alfa')
            ->whereIn('intern_id', $adminInternIds)
            ->count();

        $microTotal = MicroSkillSubmission::whereDate('updated_at', $today)
            ->whereIn('intern_id', $adminInternIds)
            ->count();

        $todayAttendances = Attendance::whereDate('date', $today)
            ->whereIn('intern_id', $adminInternIds)
            ->with('intern')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $todayAbsentInterns = collect();
        if (!HolidayService::isHoliday($nowWita)) {
            $presentIds = Attendance::whereDate('date', $today)
                ->whereIn('intern_id', $adminInternIds)
                ->pluck('intern_id')
                ->toArray();

            $todayAbsentInterns = (clone $baseInternQuery)
                ->with('user')
                ->where('is_active', true)
                ->whereNotIn('id', $presentIds)
                ->get()
                ->sortBy(function ($intern) {
                    return $intern->user->name ?? '';
                })->values();
        }

        $topMicroSkills = Intern::join('users', 'interns.user_id', '=', 'users.id')
            ->leftJoin('micro_skill_submissions', 'interns.id', '=', 'micro_skill_submissions.intern_id')
            ->select(
                'interns.id as intern_id',
                'users.name',
                'interns.institution',
                'interns.photo_path',
                DB::raw('COUNT(micro_skill_submissions.id) as total')
            )
            ->whereIn('interns.id', $adminInternIds)
            ->groupBy('interns.id', 'users.name', 'interns.institution', 'interns.photo_path')
            ->orderByDesc('total')
            ->orderBy('users.name')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'intern_id'   => $row->intern_id,
                    'name'        => $row->name,
                    'institution' => $row->institution,
                    'photo_path'  => $row->photo_path,
                    'total'       => (int) $row->total,
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