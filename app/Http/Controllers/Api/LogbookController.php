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
            'photo_data' => ['nullable', 'string'],
        ]);

        $data = [
            'intern_id' => $intern->id,
            'date' => $validated['date'],
            'activity' => $validated['activity'],
        ];

        if ($request->filled('photo_data')) {
            $imageData = $request->input('photo_data');
            
            if (preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $imageData)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }
            $imageData = base64_decode($imageData);

            try {
                $filename = Str::uuid() . '.jpg';
                $path = 'private/logbook-photos/' . $filename;
                $destinationPath = storage_path('app/private/logbook-photos');
                
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                $manager = new ImageManager(new Driver());
                $image = $manager->read($imageData)->toJpeg(80);
                
                Storage::disk('local')->put($path, (string) $image);
                $data['photo_path'] = $path;
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Gagal upload foto.'], 500);
            }
        }

        $logbook = Logbook::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Logbook berhasil disimpan.',
            'data' => $logbook
        ]);
    }
}
