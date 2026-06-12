<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $role = auth()->check() ? auth()->user()->role : 'guest';

        $response = Http::withToken(config('services.rag.api_key'))
            ->timeout(30)
            ->post(config('services.rag.url') . '/query', [
                'question'  => $request->input('message'),
                'user_role' => $role,
            ]);

        return response()->json($response->json());
    }
}
