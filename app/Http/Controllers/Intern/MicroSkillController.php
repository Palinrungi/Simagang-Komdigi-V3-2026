<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\MicroSkillSubmission;
use App\Models\MicroSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MicroSkillController extends Controller
{
    public function index(Request $request)
    {
        $intern = Auth::user()->intern;

        $query = MicroSkillSubmission::where('intern_id', $intern->id);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $submissions = $query->orderByDesc('created_at')->paginate(15);

        // Generate one-time photo URLs for each submission
        foreach ($submissions as $submission) {
            $submission->photo_url = $this->makeOneTimeMicroSkillPhotoUrl($submission->photo_path);
        }

        $cekaktif = $intern && $intern->is_active;

        // Recommendations: microskills not yet done by this intern
        // Normalize titles by lowercasing and removing whitespace so capitalization/spaces are ignored
        $doneTitles = MicroSkillSubmission::where('intern_id', $intern->id)->pluck('title')->toArray();

        $normalize = function ($str) {
            return strtolower(preg_replace('/\s+/', '', $str ?? ''));
        };

        $doneNormalized = array_map($normalize, $doneTitles);

        // Load microskills and filter in PHP using normalized comparison (safe and handles spacing/case)
        $recommendations = MicroSkill::orderBy('created_at', 'desc')->get()
            ->filter(function ($m) use ($doneNormalized, $normalize) {
                return !in_array($normalize($m->judul_micro), $doneNormalized);
            })
            ->take(5)
            ->values();

        return view('intern.microskill.index', compact('submissions', 'cekaktif', 'recommendations'));
    }

    public function create(Request $request)
    {
        $suggestedTitle = $request->query('title');

        $microSkills = MicroSkill::orderBy('judul_micro')->get();

        return view(
            'intern.microskill.create',
            compact('suggestedTitle', 'microSkills')
        );
    }

    public function store(Request $request)
    {
        $intern = Auth::user()->intern;

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
                return back()->withErrors([
                    'photo' => 'Tipe file tidak valid.'
                ])->withInput();
            }

            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    $filename = Str::uuid() . '.jpg';

                    $destinationPath = storage_path('app/private/micro-skills');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($photo)
                        ->toJpeg(80);

                    Storage::disk('local')->put(
                        'private/micro-skills/' . $filename,
                        (string) $image
                    );

                    $photoPath = 'private/micro-skills/' . $filename;
                } catch (\Exception $e) {
                    return back()->withErrors([
                        'photo' => 'Gagal upload foto.'
                    ])->withInput();
                }
            }
        }

        MicroSkillSubmission::create([
            'intern_id' => $intern->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'photo_path' => $photoPath,
            'status' => 'approved',
            'submitted_at' => now(),
        ]);

        return redirect()->route('intern.microskill.index')->with('success', 'Bukti Mikro Skill berhasil diunggah.');
    }

    public function edit(MicroSkillSubmission $submission)
    {
        $this->authorize('update', $submission);

        // 1. Ambil data master MicroSkill dari database agar tidak memicu error undefined variable di View
        $microSkills = MicroSkill::orderBy('judul_micro')->get();

        // 2. Generate one-time photo URL
        $submission->photo_url = $this->makeOneTimeMicroSkillPhotoUrl($submission->photo_path);

        return view('intern.microskill.edit', compact('submission', 'microSkills'));
    }

    public function update(Request $request, MicroSkillSubmission $submission)
    {
        $this->authorize('update', $submission);

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('micro_skill_submissions', 'title')
                    ->where(fn ($query) => $query->where('intern_id', $submission->intern_id))
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

        $photoPath = $submission->photo_path;

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($submission->photo_path) {
                $oldPath = storage_path('app/private/' . $submission->photo_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $photo = $request->file('photo');

            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
            ];

            if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
                return back()->withErrors([
                    'photo' => 'Tipe file tidak valid.'
                ])->withInput();
            }

            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    $filename = Str::uuid() . '.jpg';

                    $destinationPath = storage_path('app/private/micro-skills');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($photo)
                        ->toJpeg(80);

                    Storage::disk('local')->put(
                        'private/micro-skills/' . $filename,
                        (string) $image
                    );

                    $photoPath = 'private/micro-skills/' . $filename;

                } catch (\Exception $e) {
                    return back()->withErrors([
                        'photo' => 'Gagal upload foto.'
                    ])->withInput();
                }
            }
        }

        $submission->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('intern.microskill.index')->with('success', 'Bukti Mikro Skill berhasil diperbarui.');
    }

    public function destroy(MicroSkillSubmission $submission)
    {
        $this->authorize('delete', $submission);

        // Delete the photo if it exists
        if ($submission->photo_path) {
            $photoPath = storage_path('app/private/' . $submission->photo_path);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $submission->delete();

        return redirect()->route('intern.microskill.index')->with('success', 'Bukti Mikro Skill berhasil dihapus.');
    }

    private function makeOneTimeMicroSkillPhotoUrl(?string $photoPath): ?string
    {
        if (!$photoPath) {
            return null;
        }

        $filename = basename($photoPath);
        $token = Str::random(64);
        $cacheKey = "intern-photo-token:{$token}";

        Cache::put(
            $cacheKey,
            [
                'user_id' => Auth::id(),
                'filename' => $filename,
            ],
            now()->addMinutes(5)
        );

        return route('intern.microskill.photo', ['filename' => $filename, 'token' => $token]);
    }

    public function servePhoto(Request $request, $filename)
    {
        if ($filename !== basename($filename)) {
            abort(404, 'File not found');
        }

        $filePath = storage_path('app/private/micro-skills/' . $filename);

        if (!str_starts_with(realpath($filePath) ?: '', realpath(storage_path('app/private/micro-skills')) ?: '')) {
            abort(404, 'File not found');
        }

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $token = $request->query('token');
        if ($token) {
            $cacheKey = "intern-photo-token:{$token}";
            $tokenData = Cache::get($cacheKey);

            if (!$tokenData || $tokenData['user_id'] != Auth::id() || $tokenData['filename'] !== $filename) {
                abort(404, 'File not found');
            }
        }

        $submission = MicroSkillSubmission::where('photo_path', 'private/micro-skills/' . $filename)
            ->first();

        if (!$submission) {
            abort(403, 'Unauthorized');
        }

        $this->authorize('view', $submission);

        return response()->file($filePath, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}