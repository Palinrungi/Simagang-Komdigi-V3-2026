<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $intern = $request->user()->intern;

        $logbooks = Logbook::where('intern_id', $intern->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Convert the paginated items to array to append photo URLs if needed
        $items = collect($logbooks->items())->map(function ($item) {
            // Because photos are private, in a real API we might need a signed URL
            // For now we'll just return the raw path or a route for mobile to fetch
            // using the Bearer token (similar to attendance).
            $item->photo_url = $item->photo_path ? url('api/logbooks/photo/' . basename($item->photo_path)) : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'current_page' => $logbooks->currentPage(),
                'last_page' => $logbooks->lastPage(),
                'total' => $logbooks->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $intern = $request->user()->intern;

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'activity' => ['required', 'string', 'max:1000'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:4096'
            ],
        ]);

        $data = [
            'intern_id' => $intern->id,
            'date' => $validated['date'],
            'activity' => $validated['activity'],
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $allowedMimeTypes = ['image/jpeg', 'image/png'];

            if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
                return response()->json(['success' => false, 'message' => 'Tipe file tidak valid.'], 400);
            }

            if ($photo->isValid() && $photo->getError() === UPLOAD_ERR_OK) {
                try {
                    $filename = Str::uuid() . '.jpg';
                    $path = 'private/logbook-photos/' . $filename;
                    $destinationPath = storage_path('app/private/logbook-photos');

                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($photo)->toJpeg(80);

                    Storage::disk('local')->put($path, (string) $image);
                    $data['photo_path'] = $path;
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal upload foto: ' . $e->getMessage()], 500);
                }
            }
        }

        $logbook = Logbook::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Logbook berhasil disimpan.',
            'data' => $logbook
        ]);
    }

    public function update(Request $request, $id)
    {
        $intern = $request->user()->intern;
        $logbook = Logbook::where('id', $id)->where('intern_id', $intern->id)->firstOrFail();

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'activity' => ['required', 'string', 'max:1000'],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:4096'
            ],
        ]);

        $data = [
            'date' => $validated['date'],
            'activity' => $validated['activity'],
        ];

        if ($request->hasFile('photo')) {
            if ($logbook->photo_path) {
                $oldPath = storage_path('app/' . $logbook->photo_path);
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
                    $path = 'private/logbook-photos/' . $filename;
                    $destinationPath = storage_path('app/private/logbook-photos');

                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($photo)->toJpeg(80);

                    Storage::disk('local')->put($path, (string) $image);
                    $data['photo_path'] = $path;
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal upload foto: ' . $e->getMessage()], 500);
                }
            }
        }

        $logbook->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Logbook berhasil diperbarui.',
            'data' => $logbook
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $intern = $request->user()->intern;
        $logbook = Logbook::where('id', $id)->where('intern_id', $intern->id)->firstOrFail();

        if ($logbook->photo_path) {
            $photoPath = storage_path('app/' . $logbook->photo_path);
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $logbook->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logbook berhasil dihapus.'
        ]);
    }

    public function servePhoto(Request $request, $filename)
    {
        $intern = $request->user()->intern;
        
        if ($filename !== basename($filename)) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $filePath = storage_path('app/private/logbook-photos/' . $filename);

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $logbook = Logbook::where('photo_path', 'private/logbook-photos/' . $filename)
            ->where('intern_id', $intern->id)
            ->first();

        if (!$logbook) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->file($filePath);
    }
}
