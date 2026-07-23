<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        if (!$user->isIntern()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya akun peserta magang yang dapat login ke aplikasi mobile',
            ], 403);
        }

        // Revoke old tokens for this user from mobile app if needed, or just let them pile up
        // $user->tokens()->where('name', 'mobile-app')->delete();

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'intern',
            ],
        ]);
    }

    public function profile(Request $request)
    {
        $user = clone $request->user();
        $user->load('intern'); // load intern relation
        
        $summary = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpha' => 0,
            'disiplin' => 0,
            'logbook' => 0,
            'micro_skill' => 0
        ];
        if ($user->intern) {
            $internId = $user->intern->id;
            $hadir = \App\Models\Attendance::where('intern_id', $internId)->where('status', 'hadir')->count();
            $izin = \App\Models\Attendance::where('intern_id', $internId)->where('status', 'izin')->count();
            $sakit = \App\Models\Attendance::where('intern_id', $internId)->where('status', 'sakit')->count();
            $alpha = \App\Models\Attendance::where('intern_id', $internId)->where('status', 'alpha')->count();
            $logbook = \App\Models\Logbook::where('intern_id', $internId)->count();
            $microSkill = \App\Models\MicroSkillSubmission::where('intern_id', $internId)->count();
            
            $totalDays = $hadir + $izin + $sakit + $alpha;
            $disiplin = $totalDays > 0 ? round(($hadir / $totalDays) * 100) : 0;
            
            $summary = [
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'disiplin' => $disiplin,
                'logbook' => $logbook,
                'micro_skill' => $microSkill
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'intern_data' => $user->intern,
                'summary' => $summary,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        // Placeholder for FCM token update if needed
        return response()->json([
            'success' => true,
            'message' => 'FCM Token updated (placeholder)',
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // 5MB max
        ]);

        $user = $request->user();
        $intern = $user->intern;

        if (!$intern) {
            return response()->json(['success' => false, 'message' => 'Data intern tidak ditemukan.'], 404);
        }

        try {
            // Hapus foto lama jika ada
            if ($intern->photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($intern->photo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($intern->photo_path);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('profile-photos', 'public');
            $intern->update(['photo_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'photo_path' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah foto: ' . $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = $request->user();
        $intern = $user->intern;

        // Update user data
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update data intern
        if ($intern && isset($validated['phone'])) {
            $intern->update(['phone' => $validated['phone']]);
        }

        // Update password jika diisi
        if (!empty($validated['old_password']) && !empty($validated['new_password'])) {
            if (!Hash::check($validated['old_password'], $user->password)) {
                return response()->json(['success' => false, 'message' => 'Kata sandi lama salah.'], 422);
            }
            $user->update(['password' => Hash::make($validated['new_password'])]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }
}
