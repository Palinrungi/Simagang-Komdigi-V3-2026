<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MicroSkillSubmission;
use App\Models\MicroSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MicroSkillController extends Controller
{
    public function index(Request $request)
    {
        $intern = $request->user()->intern;

        $submissions = MicroSkillSubmission::where('intern_id', $intern->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($sub) {
                $sub->photo_url = $sub->photo_path ? url('api/micro-skills/photo/' . basename($sub->photo_path)) : null;
                return $sub;
            });

        $doneTitles = MicroSkillSubmission::where('intern_id', $intern->id)->pluck('title')->toArray();

        $normalize = function ($str) {
            return strtolower(preg_replace('/\s+/', '', $str ?? ''));
        };

        $doneNormalized = array_map($normalize, $doneTitles);

        $recommendations = MicroSkill::orderBy('created_at', 'desc')->get()
            ->filter(function ($m) use ($doneNormalized, $normalize) {
                return !in_array($normalize($m->judul_micro), $doneNormalized);
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'submissions' => $submissions,
                'recommendations' => $recommendations,
            ]
        ]);
    }
}
