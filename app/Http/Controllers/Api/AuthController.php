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
        $user->load('intern.institusi'); // load intern and institusi relation
        
        $summary = null;
        if ($user->intern) {
            $internId = $user->intern->id;
            $hadir = \App\Models\Attendance::where('intern_id', $internId)->where('status', 'hadir')->count();
            $sakit = \App\Models\Attendance::where('intern_id', $internId)->where('status', 'sakit')->count();
            $logbook = \App\Models\Logbook::where('intern_id', $internId)->count();
            $microSkill = \App\Models\MicroSkillSubmission::where('intern_id', $internId)->count();
            
            $summary = [
                'hadir' => $hadir,
                'sakit' => $sakit,
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
}
