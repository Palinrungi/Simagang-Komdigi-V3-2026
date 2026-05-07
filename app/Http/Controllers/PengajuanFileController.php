<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengajuanFileController extends Controller
{
    public function __invoke(Pengajuan $pengajuan)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        if (!$this->canAccessPengajuan($user, $pengajuan)) {
            abort(403, 'Unauthorized access.');
        }

        $relativePath = $this->normalizePath($pengajuan->surat_path);
        if (!$relativePath || !Str::startsWith($relativePath, 'surat_magang/')) {
            abort(404);
        }

        $fullPath = $this->resolveStoragePath($relativePath);
        if (!$fullPath) {
            abort(404);
        }

        return response()->download($fullPath, basename($relativePath));
    }

    private function canAccessPengajuan($user, Pengajuan $pengajuan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstitusi()) {
            return optional($user->institusi)->id === $pengajuan->institusi_id;
        }

        return false;
    }

    private function normalizePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $normalized = str_replace('\\', '/', $path);
        $normalized = ltrim($normalized, '/');

        if ($normalized === '' || Str::contains($normalized, ['../', '..\\']) || Str::startsWith($normalized, '..')) {
            return null;
        }

        return $normalized;
    }

    private function resolveStoragePath(string $relativePath): ?string
    {
        $privatePath = storage_path('app/private/' . $relativePath);
        if (file_exists($privatePath)) {
            return $privatePath;
        }

        $legacyPublicPath = storage_path('app/public/' . $relativePath);
        if (!file_exists($legacyPublicPath)) {
            return null;
        }

        $privateDir = dirname($privatePath);
        if (!file_exists($privateDir)) {
            mkdir($privateDir, 0755, true);
        }

        if (@rename($legacyPublicPath, $privatePath)) {
            return $privatePath;
        }

        if (@copy($legacyPublicPath, $privatePath)) {
            @unlink($legacyPublicPath);

            if (file_exists($privatePath)) {
                return $privatePath;
            }
        }

        return null;
    }
}