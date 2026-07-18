<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SharingSession;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SharingSessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = SharingSession::with(['speakerUser.intern', 'moderatorUser.intern'])
            ->orderBy('session_date', 'desc')
            ->get()
            ->each(function ($session) {
                $session->append([
                    'speaker_name',
                    'moderator_name',
                    'documentation_photo_url',
                    'evaluation_is_open',
                    'material_status',
                    'is_speaker',
                    'is_moderator'
                ]);
            });

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    public function show($id)
    {
        $session = SharingSession::with(['speakerUser.intern', 'moderatorUser.intern'])->findOrFail($id);
        $session->append([
            'speaker_name',
            'moderator_name',
            'documentation_photo_url',
            'evaluation_is_open',
            'material_status',
            'is_speaker',
            'is_moderator'
        ]);

        return response()->json([
            'success' => true,
            'data' => $session
        ]);
    }

    public function updateMateri(Request $request, $id)
    {
        $sharingSession = SharingSession::findOrFail($id);

        $isSpeaker = (int) $sharingSession->speaker_user_id === (int) auth()->id();
        $isModerator = (int) $sharingSession->moderator_user_id === (int) auth()->id();

        if (!$isSpeaker && !$isModerator) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke sharing session ini.'
            ], 403);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'documentation_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($isSpeaker) {
            if ($request->has('title')) {
                $sharingSession->title = $request->title;
            }
            if ($request->has('description')) {
                $sharingSession->description = $request->description;
            }
        }

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

        $sharingSession->load(['speakerUser.intern', 'moderatorUser.intern']);
        $sharingSession->append([
            'speaker_name',
            'moderator_name',
            'documentation_photo_url',
            'evaluation_is_open',
            'material_status',
            'is_speaker',
            'is_moderator'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data sharing session berhasil diperbarui.',
            'data' => $sharingSession
        ]);
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
