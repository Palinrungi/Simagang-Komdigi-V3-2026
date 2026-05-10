<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MicroSkillSubmission;
use Illuminate\Http\Request;

class AdminMicroSkillController extends Controller
{
    public function index(Request $request)
    {
        $query = MicroSkillSubmission::with('intern');
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('q')) {
            $q = '%' . $request->input('q') . '%';
            $query->whereHas('intern', function ($sub) use ($q) {
                $sub->where('name', 'like', $q)->orWhere('institution', 'like', $q);
            });
        }
        $submissions = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.microskill.index', compact('submissions'));
    }

    public function servePhoto(string $filename)
    {
        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $photoPath = 'private/micro-skills/' . $filename;

        MicroSkillSubmission::where('photo_path', $photoPath)->firstOrFail();

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
}


