<?php

namespace App\Http\Controllers\Industri;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\MicroSkillSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndustriMicroskillController extends Controller
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
        $internIds = $this->getInternIds();

        $query = MicroSkillSubmission::with('intern')
            ->whereIn('intern_id', $internIds);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('intern_id')) {
            $query->where('intern_id', $request->integer('intern_id'));
        }

        $submissions = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $interns = $internIds->isNotEmpty()
            ? Intern::whereIn('id', $internIds)->with('user')->get()->sortBy(function($i) { return $i->user->name ?? ''; })->values()
            : collect();

        return view('industri.microskill.index', compact('industri', 'submissions', 'interns'));
    }

    public function servePhoto(string $filename)
    {
        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $photoPath = 'private/micro-skills/' . $filename;

        $submission = MicroSkillSubmission::where('photo_path', $photoPath)->firstOrFail();

        $this->authorize('view', $submission);

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
