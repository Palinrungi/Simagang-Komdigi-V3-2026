<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SharingSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SharingSessionController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan semua sharing session
        $sessions = SharingSession::orderBy('session_date', 'desc')->get();

        // Optional: filter jika ada query params (contoh: status upcoming/past)
        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }
}
