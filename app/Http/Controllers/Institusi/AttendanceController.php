<?php

namespace App\Http\Controllers\Institusi;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use App\Services\HolidayService;
use App\Services\TimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    private function getInstitusiInternIds(): \Illuminate\Support\Collection
    {
        $institusi = Auth::user()->institusi;

        if (!$institusi) {
            return collect();
        }

        return DB::table('interns')
            ->join('users', 'users.id', '=', 'interns.user_id')
            ->join('pengajuan_details', 'pengajuan_details.email', '=', 'users.email')
            ->join('pengajuans', 'pengajuans.id', '=', 'pengajuan_details.pengajuan_id')
            ->where('pengajuans.institusi_id', $institusi->id)
            ->where('interns.is_active', true)
            ->pluck('interns.id');
    }

    public function index(Request $request)
    {
        $institusi = Auth::user()->institusi;
        $nowWita   = TimeService::nowWita();
        $todayWita = $nowWita->toDateString();

        $internIds = $this->getInstitusiInternIds();

        // query absensi
        $query = Attendance::query()
            ->with('intern')
            ->whereIn('intern_id', $internIds);

        // FILTER PESERTA
        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->integer('intern_id'));
        }

        // DEFAULT = HARI INI
        if (!$request->filled('date_from') && !$request->filled('date_to')) {
            $query->whereDate('date', $todayWita);
        } else {

            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->whereDate('date', '<=', $request->date('date_to'));
            }
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        
        // data intern
        $interns = Intern::whereIn('id', $internIds)
            ->join('users', 'interns.user_id', '=', 'users.id')->select('interns.*')->orderBy('users.name')
            ->get();

        // persentase kehadiran
        $internStatistics = $interns->map(function ($intern) {

            $totalAttendance = Attendance::where('intern_id', $intern->id)->count();

            $totalPresent = Attendance::where('intern_id', $intern->id)
                ->where('status', 'hadir')
                ->count();

            $percentage = $totalAttendance > 0
                ? round(($totalPresent / $totalAttendance) * 100)
                : 0;

            $intern->attendance_percentage = $percentage;

            return $intern;
        })->sortBy('attendance_percentage');

        // absen hari ini
        $todayAbsentInterns = collect();

        $isWorkday = !HolidayService::isHoliday($nowWita);

        $showAbsentToday =
            !$request->filled('date_from')
            && !$request->filled('date_to');

        if ($isWorkday && $showAbsentToday) {

            $presentIds = Attendance::whereIn('intern_id', $internIds)
                ->whereDate('date', $todayWita)
                ->pluck('intern_id');

            $todayAbsentInterns = $interns->whereNotIn('id', $presentIds->toArray());

            if ($request->filled('intern_id')) {
                $todayAbsentInterns = $todayAbsentInterns
                    ->where('id', $request->integer('intern_id'));
            }
        }

        return view('institusi.attendance.index', compact(
            'institusi',
            'attendances',
            'interns',
            'todayAbsentInterns',
            'todayWita',
            'internStatistics'
        ));
    }

    public function show(Request $request, Intern $intern)
    {
        $internIds = $this->getInstitusiInternIds();

        // Pastikan intern milik institusi ini
        abort_unless($internIds->contains($intern->id), 403);

        $query = Attendance::where('intern_id', $intern->id);

        // FILTER TANGGAL
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        // Statistik
        $totalAttendance = Attendance::where('intern_id', $intern->id)->count();

        $totalPresent = Attendance::where('intern_id', $intern->id)
            ->where('status', 'hadir')
            ->count();

        $attendancePercentage = $totalAttendance > 0
            ? round(($totalPresent / $totalAttendance) * 100)
            : 0;

        return view('institusi.attendance.show', compact(
            'intern',
            'attendances',
            'attendancePercentage',
            'totalAttendance',
            'totalPresent'
        ));
    }

    public function servePhoto(string $filename)
    {
        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $internIds = $this->getInstitusiInternIds();
        $photoPath = 'private/attendance-photos/' . $filename;

        Attendance::whereIn('intern_id', $internIds)
            ->where(function ($query) use ($photoPath) {
                $query->where('photo_path', $photoPath)
                    ->orWhere('photo_checkout', $photoPath);
            })
            ->firstOrFail();

        $fullPath = storage_path('app/' . $photoPath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        return response()->file($fullPath, $headers);
    }

}