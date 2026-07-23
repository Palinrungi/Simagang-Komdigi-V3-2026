<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MicroSkillSubmission;
use App\Models\MicroSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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

    public function store(Request $request)
    {
        $intern = $request->user()->intern;

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('micro_skill_submissions', 'title')
                    ->where(fn ($query) => $query->where('intern_id', $intern->id)),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:4096'
            ],
        ]); 

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
            ];

            if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
                return response()->json(['success' => false, 'message' => 'Tipe file tidak valid.'], 400);
            }

            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    $filename = Str::uuid() . '.jpg';
                    $destinationPath = storage_path('app/private/micro-skills');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($photo)->toJpeg(80);

                    Storage::disk('local')->put(
                        'private/micro-skills/' . $filename,
                        (string) $image
                    );

                    $photoPath = 'private/micro-skills/' . $filename;
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal upload foto: ' . $e->getMessage()], 500);
                }
            }
        }

        $submission = MicroSkillSubmission::create([
            'intern_id' => $intern->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'photo_path' => $photoPath,
            'status' => 'approved',
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diunggah.',
            'data' => $submission
        ]);
    }

    public function update(Request $request, $id)
    {
        $intern = $request->user()->intern;
        $submission = MicroSkillSubmission::where('id', $id)->where('intern_id', $intern->id)->firstOrFail();

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('micro_skill_submissions', 'title')
                    ->where(fn ($query) => $query->where('intern_id', $intern->id))
                    ->ignore($submission->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:4096'
            ],
        ]);

        if ($request->hasFile('photo')) {
            if ($submission->photo_path) {
                $oldPath = storage_path('app/private/' . $submission->photo_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $photo = $request->file('photo');
            $allowedMimeTypes = ['image/jpeg', 'image/png'];

            if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
                return response()->json(['success' => false, 'message' => 'Tipe file tidak valid.'], 400);
            }

            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    $filename = Str::uuid() . '.jpg';
                    $destinationPath = storage_path('app/private/micro-skills');

                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($photo)->toJpeg(80);

                    Storage::disk('local')->put(
                        'private/micro-skills/' . $filename,
                        (string) $image
                    );

                    $validated['photo_path'] = 'private/micro-skills/' . $filename;
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal upload foto: ' . $e->getMessage()], 500);
                }
            }
        }

        $submission->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'photo_path' => $validated['photo_path'] ?? $submission->photo_path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data' => $submission
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $intern = $request->user()->intern;
        $submission = MicroSkillSubmission::where('id', $id)->where('intern_id', $intern->id)->firstOrFail();

        if ($submission->photo_path) {
            $photoPath = storage_path('app/private/' . $submission->photo_path);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.'
        ]);
    }

    public function servePhoto(Request $request, $filename)
    {
        $intern = $request->user()->intern;
        
        if ($filename !== basename($filename)) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $filePath = storage_path('app/private/micro-skills/' . $filename);

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $submission = MicroSkillSubmission::where('photo_path', 'private/micro-skills/' . $filename)
            ->where('intern_id', $intern->id)
            ->first();

        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->file($filePath);
    }
}
