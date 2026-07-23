<?php

namespace App\Http\Controllers\Industri;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use App\Services\HolidayService;
use App\Services\TimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndustriAttendanceController extends Controller
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
        $industri = Auth::user()->industri;
        $nowWita = TimeService::nowWita();
        $todayWita = $nowWita->toDateString();

        $internIds = $this->getInternIds();

        $query = Attendance::with('intern')
            ->whereIn('intern_id', $internIds);

        // Filter peserta
        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->integer('intern_id'));
        }

        // Filter tanggal
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

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        $interns = Intern::whereIn('id', $internIds)
            ->join('users', 'interns.user_id', '=', 'users.id')->select('interns.*')->orderBy('users.name')
            ->get();

        $internStatistics = $interns->map(function ($intern) {

            $totalAttendance = Attendance::where('intern_id', $intern->id)->count();

            $totalPresent = Attendance::where('intern_id', $intern->id)
                ->where('status', 'hadir')
                ->count();

            $intern->attendance_percentage = $totalAttendance > 0
                ? round(($totalPresent / $totalAttendance) * 100)
                : 0;

            return $intern;
        })->sortBy('attendance_percentage');

        $todayAbsentInterns = collect();

        $isWorkday = !HolidayService::isHoliday($nowWita);

        $showAbsentToday =
            !$request->filled('date_from') &&
            !$request->filled('date_to');

        if ($isWorkday && $showAbsentToday) {

            $presentIds = Attendance::whereIn('intern_id', $internIds)
                ->whereDate('date', $todayWita)
                ->pluck('intern_id');

            $todayAbsentInterns = $interns->whereNotIn(
                'id',
                $presentIds->toArray()
            );

            if ($request->filled('intern_id')) {
                $todayAbsentInterns = $todayAbsentInterns
                    ->where('id', $request->integer('intern_id'));
            }
        }

        return view('industri.attendance.index', compact(
            'industri',
            'attendances',
            'interns',
            'todayAbsentInterns',
            'todayWita',
            'internStatistics'
        ));
    }

    public function show(Request $request, Intern $intern)
    {
        $internIds = $this->getInternIds();

        abort_unless($internIds->contains($intern->id), 403);

        $query = Attendance::where('intern_id', $intern->id);

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        $totalAttendance = Attendance::where('intern_id', $intern->id)->count();

        $totalPresent = Attendance::where('intern_id', $intern->id)
            ->where('status', 'hadir')
            ->count();

        $attendancePercentage = $totalAttendance > 0
            ? round(($totalPresent / $totalAttendance) * 100)
            : 0;

        return view('industri.attendance.show', compact(
            'intern',
            'attendances',
            'attendancePercentage',
            'totalAttendance',
            'totalPresent'
        ));
    }

    public function showDetail(Attendance $attendance)
    {
        $internIds = $this->getInternIds();

        abort_unless($internIds->contains($attendance->intern_id), 403);
        abort_unless(in_array($attendance->status, ['izin', 'sakit'], true), 404);

        $attendance->load('intern');

        return view('industri.attendance.detail', compact('attendance'));
    }

    public function updateDocumentStatus(Request $request, Attendance $attendance)
    {
        $internIds = $this->getInternIds();

        abort_unless($internIds->contains($attendance->intern_id), 403);

        if (!in_array($attendance->status, ['izin', 'sakit'], true)) {
            return back()->withErrors(['error' => 'Status absensi ini tidak memiliki dokumen.']);
        }

        $validated = $request->validate([
            'document_status' => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string'],
        ]);

        $attendance->update([
            'document_status' => $validated['document_status'],
            'admin_note' => $validated['admin_note'] ?? $attendance->admin_note,
        ]);

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }

    public function servePhoto(string $filename)
    {
        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $internIds = $this->getInternIds();

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

        return response()->file($fullPath, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function serveDocument($filename)
    {
        $intern = Auth::user()->intern;
        $filePath = storage_path('app/private/attendance-documents/' . $filename);

        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        // Validate the file path to prevent directory traversal
        if (!str_starts_with(realpath($filePath) ?: '', realpath(storage_path('app/private/attendance-documents')) ?: '')) {
            abort(403, 'Unauthorized');
        }

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Check if document belongs to authenticated user
        $attendance = Attendance::where('document_path', 'private/attendance-documents/' . $filename)
            ->first();

        if (!$attendance) {
            abort(403, 'Unauthorized');
        }

        $this->authorize('view', $attendance);

        return response()->download($filePath, null, [
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}