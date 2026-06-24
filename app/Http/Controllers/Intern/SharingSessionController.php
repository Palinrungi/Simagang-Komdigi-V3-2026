<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\SharingSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SharingSessionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $roleFilter = $request->get('role_filter', 'semua');
        $today = Carbon::today();

        $sessions = SharingSession::with(['speakerUser', 'moderatorUser'])
            ->when($filter === 'hari-ini', function ($query) use ($today) {
                $query->whereDate('session_date', $today);
            })
            ->when($filter === 'akan-datang', function ($query) use ($today) {
                $query->whereDate('session_date', '>', $today);
            })
            ->when($filter === 'selesai', function ($query) use ($today) {
                $query->whereDate('session_date', '<', $today);
            })
            ->when($roleFilter === 'narasumber-saya', function ($query) {
                $query->where('speaker_user_id', auth()->id());
            })
            ->when($roleFilter === 'moderator-saya', function ($query) {
                $query->where('moderator_user_id', auth()->id());
            })
            ->orderBy('session_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('intern.sharing-session.index', compact(
            'sessions',
            'filter',
            'roleFilter'
        ));
    }

    public function show(SharingSession $sharingSession)
    {
        $sharingSession->load(['speakerUser', 'moderatorUser']);

        return view('intern.sharing-session.show', compact('sharingSession'));
    }

    public function editMateri(SharingSession $sharingSession)
    {
        $isSpeaker = (int) $sharingSession->speaker_user_id === (int) auth()->id();
        $isModerator = (int) $sharingSession->moderator_user_id === (int) auth()->id();

        if (!$isSpeaker && !$isModerator) {
            abort(403, 'Anda tidak memiliki akses ke sharing session ini.');
        }

        $sharingSession->load(['speakerUser', 'moderatorUser']);

        return view('intern.sharing-session.edit-materi', compact('sharingSession'));
    }

    public function updateMateri(Request $request, SharingSession $sharingSession)
{
    $isSpeaker = (int) $sharingSession->speaker_user_id === (int) auth()->id();
    $isModerator = (int) $sharingSession->moderator_user_id === (int) auth()->id();

    if (!$isSpeaker && !$isModerator) {
        abort(403, 'Anda tidak memiliki akses ke sharing session ini.');
    }

    $request->validate([
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'documentation_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Narasumber mengisi materi
    |--------------------------------------------------------------------------
    */
    if ($isSpeaker) {
        $sharingSession->title = $request->title;
        $sharingSession->description = $request->description;
    }

    /*
    |--------------------------------------------------------------------------
    | Moderator upload dokumentasi
    |--------------------------------------------------------------------------
    */
    if ($isModerator && $request->hasFile('documentation_photo')) {
        if (
            $sharingSession->documentation_photo &&
            Storage::disk('public')->exists($sharingSession->documentation_photo)
        ) {
            Storage::disk('public')->delete($sharingSession->documentation_photo);
        }

        $photoPath = $this->compressAndStoreImage(
            $request->file('documentation_photo')
        );

        $sharingSession->documentation_photo = $photoPath;
    }

    $sharingSession->save();

    return redirect()
        ->route('intern.sharing-session.show', $sharingSession)
        ->with('success', 'Data sharing session berhasil diperbarui.');
}

    private function compressAndStoreImage($file): string
    {
        $sourcePath = $file->getRealPath();
        $mime = $file->getMimeType();

        $imageSize = getimagesize($sourcePath);

        if (!$imageSize) {
            throw ValidationException::withMessages([
                'documentation_photo' => 'File yang diupload bukan gambar yang valid.',
            ]);
        }

        [$width, $height] = $imageSize;

        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);

                if (function_exists('exif_read_data')) {
                    $exif = @exif_read_data($sourcePath);

                    if (!empty($exif['Orientation'])) {
                        if ((int) $exif['Orientation'] === 3) {
                            $sourceImage = imagerotate($sourceImage, 180, 0);
                        } elseif ((int) $exif['Orientation'] === 6) {
                            $sourceImage = imagerotate($sourceImage, -90, 0);
                        } elseif ((int) $exif['Orientation'] === 8) {
                            $sourceImage = imagerotate($sourceImage, 90, 0);
                        }

                        $width = imagesx($sourceImage);
                        $height = imagesy($sourceImage);
                    }
                }

                break;

            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;

            case 'image/webp':
                if (!function_exists('imagecreatefromwebp')) {
                    throw ValidationException::withMessages([
                        'documentation_photo' => 'Format WEBP belum didukung di server ini.',
                    ]);
                }

                $sourceImage = imagecreatefromwebp($sourcePath);
                break;

            default:
                throw ValidationException::withMessages([
                    'documentation_photo' => 'Format gambar tidak didukung.',
                ]);
        }

        if (!$sourceImage) {
            throw ValidationException::withMessages([
                'documentation_photo' => 'Gambar gagal diproses.',
            ]);
        }

        $maxWidth = 1280;
        $quality = 75;

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) (($height / $width) * $newWidth);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $white);

        imagecopyresampled(
            $canvas,
            $sourceImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        Storage::disk('public')->makeDirectory('sharing-documentations');

        $filename = Str::uuid() . '.jpg';
        $relativePath = 'sharing-documentations/' . $filename;
        $absolutePath = Storage::disk('public')->path($relativePath);

        imagejpeg($canvas, $absolutePath, $quality);

        imagedestroy($sourceImage);
        imagedestroy($canvas);

        return $relativePath;
    }
}