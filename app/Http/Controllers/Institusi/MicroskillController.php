<?php

namespace App\Http\Controllers\Institusi;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\MicroSkillSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MicroSkillController extends Controller
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
        $internIds = $this->getInstitusiInternIds();

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
            ? Intern::whereIn('id', $internIds)->join('users', 'interns.user_id', '=', 'users.id')->orderBy('users.name')->select('interns.*')->get()
            : collect();

        return view('institusi.microskill.index', compact('institusi', 'submissions', 'interns'));
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