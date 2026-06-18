<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use App\Models\MicroSkillSubmission;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Logbook;
use App\Models\SharingSession;
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
            // If user doesn't have intern record, logout and redirect to register
            Auth::logout();
            return redirect()->route('register')->withErrors(['error' => 'Data profil Anda tidak lengkap. Silakan daftar ulang.']);
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
        $microSkillApproved = $intern->microSkills()->where('status', 'approved')->count();

        // Latest certificate for this intern (if any)
        $certificate = Certificate::where('intern_id', $intern->id)->latest()->first();

        // Latest approved logbook (most recent approved by mentor)
        $latestApprovedLogbook = Logbook::where('intern_id', $intern->id)
            ->where('approval_status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->first();

        // Leaderboard (Top 10 global, termasuk yang 0)
        $topMicroSkills = Intern::leftJoin('micro_skill_submissions', 'interns.id', '=', 'micro_skill_submissions.intern_id')
            ->select('interns.id as intern_id', 'interns.name', 'interns.institution', 'interns.photo_path', DB::raw('COUNT(micro_skill_submissions.id) as total'))
            ->groupBy('interns.id', 'interns.name', 'interns.institution', 'interns.photo_path')
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
                    'total' => (int)$row->total,
                ];
            });

        $cekaktif = $intern && $intern->is_active;

        $todaySharingSessions = SharingSession::with(['speakerUser', 'moderatorUser'])
        ->whereDate('session_date', today())
        ->orderBy('start_time')
        ->get();

       $mustFillSharingMaterial = false;
$sharingSessionAlert = null;

$allMySpeakerSessions = SharingSession::where(
    'speaker_user_id',
    auth()->id()
)
->orderBy('session_date')
->get();

foreach ($allMySpeakerSessions as $session) {

    $materialNotFilled =
        empty($session->description) ||
        empty($session->evaluation_form_link);

    if ($materialNotFilled) {
        $mustFillSharingMaterial = true;
        $sharingSessionAlert = $session;
        break;
    }
}

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
            'sharingSessionAlert'
        ));
    }
}