<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Industri;
use App\Models\Intern;
use App\Services\HolidayService;
use App\Services\TimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_attendance')->only(['index', 'show', 'servePhoto']);
        $this->middleware('permission:manage_attendance')->only(['updateDocumentStatus']);
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
        $nowWita   = TimeService::nowWita();
        $todayWita = $nowWita->toDateString();

        $internIds = $this->adminInternIds();

        $query = Attendance::with('intern')
            ->whereIn('intern_id', $internIds);

        // Filter berdasarkan inputan
        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->intern_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Dropdown filter hanya menampilkan intern milik admin
        $interns = Intern::whereIn('id', $internIds)->orderBy('name')->get();

        $todayAbsentInterns = collect();
        $isWorkday      = !HolidayService::isHoliday($nowWita);
        $noDateFilter   = !$request->filled('date_from') && !$request->filled('date_to');
        $noStatusFilter = !$request->filled('status') || $request->input('status') === 'alfa';

        if ($isWorkday && $noDateFilter && $noStatusFilter) {
            $presentIds = Attendance::whereDate('date', $todayWita)
                ->whereIn('intern_id', $internIds)
                ->pluck('intern_id')
                ->toArray();

            $absentQuery = Intern::whereIn('id', $internIds)
                ->where('is_active', true)
                ->whereNotIn('id', $presentIds)
                ->orderBy('name');

            if ($request->filled('intern_id')) {
                $absentQuery->where('id', $request->integer('intern_id'));
            }

            $todayAbsentInterns = $absentQuery->get();
        }

        return view('admin.attendance.index', compact(
            'attendances',
            'interns',
            'todayAbsentInterns',
            'todayWita'
        ));
    }

    public function show(Attendance $attendance)
    {
        // Pastikan attendance ini milik intern admin
        abort_unless(
            $this->adminInternIds()->contains($attendance->intern_id),
            403
        );

        $this->authorize('view', $attendance);

        $attendance->load('intern');

        return view('admin.attendance.show', compact('attendance'));
    }

    public function servePhoto(string $filename)
    {
        $photoPath = 'private/attendance-photos/' . $filename;

        $attendance = Attendance::where(function ($query) use ($photoPath) {
            $query->where('photo_path', $photoPath)
                ->orWhere('photo_checkout', $photoPath);
        })->firstOrFail();

        // Pastikan attendance ini milik intern admin
        abort_unless(
            $this->adminInternIds()->contains($attendance->intern_id),
            403
        );

        $this->authorize('view', $attendance);

        $fullPath = storage_path('app/' . $photoPath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        return response()->file($fullPath, [
            'Cache-Control'          => 'no-store, no-cache, must-revalidate',
            'Pragma'                 => 'no-cache',
            'Expires'                => '0',
            'X-Content-Type-Options' => 'nosniff',
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

    public function updateDocumentStatus(Request $request, Attendance $attendance)
    {
        abort_unless(
            $this->adminInternIds()->contains($attendance->intern_id),
            403
        );

        if (!in_array($attendance->status, ['izin', 'sakit'])) {
            return back()->withErrors(['error' => 'Status absensi ini tidak memiliki dokumen.']);
        }

        $validated = $request->validate([
            'document_status' => ['required', 'in:approved,rejected'],
            'admin_note'      => ['nullable', 'string'],
        ]);

        $attendance->update([
            'document_status' => $validated['document_status'],
            'admin_note'      => $validated['admin_note'] ?? null,
        ]);

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }
}