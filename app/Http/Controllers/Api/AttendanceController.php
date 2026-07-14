<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\HolidayService;
use App\Services\TimeService;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function today(Request $request)
    {
        $user = $request->user();
        $intern = $user->intern;

        $nowWita = TimeService::nowWita();
        $todayWita = $nowWita->toDateString();

        $todayAttendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $todayWita)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $todayAttendance,
            'is_holiday' => HolidayService::isHoliday($nowWita) || HolidayService::isWeekend($nowWita),
            'holiday_name' => HolidayService::getHolidayName($nowWita),
            'server_time' => $nowWita->toDateTimeString(),
        ]);
    }

    public function history(Request $request)
    {
        $intern = $request->user()->intern;

        $attendances = Attendance::where('intern_id', $intern->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        $totalHadir = Attendance::where('intern_id', $intern->id)->where('status', 'hadir')->count();
        $totalIzin = Attendance::where('intern_id', $intern->id)->where('status', 'izin')->count();
        $totalSakit = Attendance::where('intern_id', $intern->id)->where('status', 'sakit')->count();
        $totalAlfa = Attendance::where('intern_id', $intern->id)->where('status', 'alfa')->count();

        return response()->json([
            'success' => true,
            'data' => $attendances->items(),
            'meta' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'total' => $attendances->total(),
            ],
            'summary' => [
                'hadir' => $totalHadir,
                'izin' => $totalIzin,
                'sakit' => $totalSakit,
                'alfa' => $totalAlfa,
            ]
        ]);
    }

    public function checkIn(Request $request)
    {
        $intern = $request->user()->intern;
        $nowWita = TimeService::nowWita();
        $todayWita = $nowWita->toDateString();

        if (HolidayService::isWeekend($nowWita) || HolidayService::isNationalHoliday($nowWita)) {
            return response()->json(['success' => false, 'message' => 'Hari ini libur.'], 400);
        }

        $todayAttendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $todayWita)
            ->first();

        if ($todayAttendance) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absensi hari ini.'], 400);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:hadir,izin,sakit'],
            'photo_data' => ['required_if:status,hadir', 'nullable', 'string'],
            'note' => ['required_if:status,izin,sakit', 'nullable', 'string'],
        ]);

        $data = [
            'intern_id' => $intern->id,
            'date' => $todayWita,
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'hadir') {
            $checkInStart = env('ATTENDANCE_CHECKIN_START', '08:00');
            $checkInEnd = env('ATTENDANCE_CHECKIN_END', '12:00');
            $currentTime = $nowWita->format('H:i');

            if ($currentTime < $checkInStart || $currentTime > $checkInEnd) {
                return response()->json(['success' => false, 'message' => 'Absensi hanya antara ' . $checkInStart . ' - ' . $checkInEnd], 400);
            }

            if ($request->filled('photo_data')) {
                $imageData = $request->input('photo_data');

                // Clean base64 header if exists
                if (preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $imageData)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                }
                
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    return response()->json(['success' => false, 'message' => 'Data gambar rusak.'], 400);
                }

                try {
                    $filename = Str::uuid() . '.jpg';
                    $path = 'private/attendance-photos/' . $filename;
                    $destinationPath = storage_path('app/private/attendance-photos');
                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($imageData)->toJpeg(80);
                    Storage::disk('local')->put($path, (string) $image);
                    
                    $data['photo_path'] = $path;
                    $data['check_in'] = $nowWita;
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal upload foto.'], 500);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Foto wajib diisi.'], 400);
            }
        } else {
            $data['note'] = $validated['note'] ?? null;
            $data['document_status'] = 'pending';
            // Document upload for API not yet implemented in this minimal version
        }

        $attendance = Attendance::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan.',
            'data' => $attendance
        ]);
    }

    public function checkOut(Request $request)
    {
        $nowWita = TimeService::nowWita();
        $intern = $request->user()->intern;

        $validated = $request->validate([
            'photo_data' => ['required', 'string'],
        ]);

        $todayAttendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', $nowWita->toDateString())
            ->where('status', 'hadir')
            ->first();

        if (!$todayAttendance) {
            return response()->json(['success' => false, 'message' => 'Belum absen masuk hari ini.'], 400);
        }

        if ($todayAttendance->check_out) {
            return response()->json(['success' => false, 'message' => 'Sudah absen keluar.'], 400);
        }

        if ($nowWita->format('H:i') < '16:00') {
            return response()->json(['success' => false, 'message' => 'Absensi keluar mulai 16:00.'], 400);
        }

        $imageData = $request->input('photo_data');
        if (preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $imageData)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
        }
        $imageData = base64_decode($imageData);

        try {
            $filename = Str::uuid() . '.jpg';
            $path = 'private/attendance-photos/' . $filename;
            $destinationPath = storage_path('app/private/attendance-photos');
            if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

            $manager = new ImageManager(new Driver());
            $image = $manager->read($imageData)->toJpeg(80);
            Storage::disk('local')->put($path, (string) $image);
            
            $todayAttendance->update([
                'check_out' => $nowWita,
                'photo_checkout' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal upload foto.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Absensi keluar berhasil disimpan.',
            'data' => $todayAttendance
        ]);
    }
}
