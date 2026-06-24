<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use App\Models\Certificate;
use App\Models\Logbook;
use App\Models\SharingSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function makeOneTimeAttendancePhotoUrl(?string $photoPath): ?string
    {
        if (!$photoPath) {
            return null;
        }

        $filename = basename($photoPath);

        return route('intern.attendance.photo', [
            'filename' => $filename,
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $intern = $user->intern;

        if (!$intern) {
            Auth::logout();

            return redirect()
                ->route('register')
                ->withErrors([
                    'error' => 'Data profil Anda tidak lengkap. Silakan daftar ulang.',
                ]);
        }

        // Today's attendance status
        $todayAttendance = Attendance::where('intern_id', $intern->id)
            ->whereDate('date', today())
            ->first();

        if ($todayAttendance) {
            $todayAttendance->check_in_photo_url = $this->makeOneTimeAttendancePhotoUrl($todayAttendance->photo_path);
            $todayAttendance->check_out_photo_url = $this->makeOneTimeAttendancePhotoUrl($todayAttendance->photo_checkout);
        }

        // Statistics
        $totalHadir = Attendance::where('intern_id', $intern->id)
            ->where('status', 'hadir')
            ->count();

        $totalIzin = Attendance::where('intern_id', $intern->id)
            ->where('status', 'izin')
            ->count();

        $totalSakit = Attendance::where('intern_id', $intern->id)
            ->where('status', 'sakit')
            ->count();

        $totalTidakHadir = Attendance::where('intern_id', $intern->id)
            ->where('status', 'alfa')
            ->count();

        $hasFinalReport = $intern->finalReport !== null;

        // Micro skill summary
        $microSkillTotal = \App\Models\MicroSkill::count();

        $microSkillApproved = $intern->microSkills()
            ->where('status', 'approved')
            ->count();

        // Latest certificate for this intern
        $certificate = Certificate::where('intern_id', $intern->id)
            ->latest()
            ->first();

        // Latest approved logbook
        $latestApprovedLogbook = Logbook::where('intern_id', $intern->id)
            ->where('approval_status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->first();

        // Leaderboard Micro Skill
        $topMicroSkills = Intern::leftJoin('micro_skill_submissions', 'interns.id', '=', 'micro_skill_submissions.intern_id')
            ->select(
                'interns.id as intern_id',
                'interns.name',
                'interns.institution',
                'interns.photo_path',
                DB::raw('COUNT(micro_skill_submissions.id) as total')
            )
            ->groupBy(
                'interns.id',
                'interns.name',
                'interns.institution',
                'interns.photo_path'
            )
            ->where('interns.is_active', true)
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
                    'total' => (int) $row->total,
                ];
            });

        $cekaktif = $intern && $intern->is_active;

        // Sharing Session Hari Ini
        $todaySharingSessions = SharingSession::with(['speakerUser', 'moderatorUser'])
            ->whereDate('session_date', today())
            ->orderBy('start_time')
            ->get();

                /*
        |--------------------------------------------------------------------------
        | Alert Narasumber Belum Lengkapi Materi
        |--------------------------------------------------------------------------
        */
        $mustFillSharingMaterial = false;
        $sharingSessionAlert = null;

        $allMySpeakerSessions = SharingSession::where('speaker_user_id', Auth::id())
            ->whereDate('session_date', '>=', Carbon::today())
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        foreach ($allMySpeakerSessions as $session) {
            $materialNotFilled =
                empty($session->title) ||
                empty($session->description);

            if ($materialNotFilled) {
                $mustFillSharingMaterial = true;
                $sharingSessionAlert = $session;
                break;
            }
        }
        /*
        |--------------------------------------------------------------------------
        | Alert Moderator Belum Upload Dokumentasi Gambar
        |--------------------------------------------------------------------------
        | Muncul kalau:
        | - user login adalah moderator
        | - sharing session sudah berlangsung hari ini / sebelumnya
        | - documentation_photo masih kosong
        */
        $sharingDocumentationAlert = SharingSession::with(['speakerUser', 'moderatorUser'])
            ->where('moderator_user_id', Auth::id())
            ->whereDate('session_date', '<=', Carbon::today())
            ->where(function ($query) {
                $query->whereNull('documentation_photo')
                    ->orWhere('documentation_photo', '');
            })
            ->orderBy('session_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->first();

        $mustUploadSharingDocumentation = $sharingDocumentationAlert ? true : false;

        return view('intern.dashboard', compact(
            'intern',
            'todayAttendance',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalTidakHadir',
            'hasFinalReport',
            'certificate',
            'microSkillTotal',
            'microSkillApproved',
            'topMicroSkills',
            'cekaktif',
            'latestApprovedLogbook',
            'todaySharingSessions',
            'mustFillSharingMaterial',
            'sharingSessionAlert',
            'mustUploadSharingDocumentation',
            'sharingDocumentationAlert'
        ));
    }
}