<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $role = auth()->check() ? auth()->user()->role : 'guest';

        Log::info('Chatbot query', ['msg' => $request->input('message'), 'role' => $role]);

        try {
            $response = Http::withToken(config('services.rag.api_key'))
                ->timeout(30)
                ->post(config('services.rag.url') . '/query', [
                    'question'  => $request->input('message'),
                    'user_role' => $role,
                ]);

            // Handle rate limit (429)
            if ($response->status() === 429) {
                $data = $response->json();
                $answer = $data['answer'] ?? 'Maaf, layanan chatbot sedang sibuk. Silakan coba lagi dalam beberapa saat.';
                
                Log::warning('Chatbot rate limited', [
                    'error_type' => $data['error_type'] ?? 'unknown',
                    'retry_after' => $data['retry_after'] ?? null,
                ]);

                return response()->json([
                    'answer' => $answer,
                    'sources' => [],
                    'from_cache' => false,
                    'error_type' => $data['error_type'] ?? 'rate_limit',
                ], 200); // Return 200 ke frontend agar pesan tampil normal di chat bubble
            }

            // Handle service unavailable (503)
            if ($response->status() === 503) {
                Log::error('RAG service unavailable');
                return response()->json([
                    'answer' => 'Maaf, layanan AI sedang tidak tersedia. Silakan coba lagi dalam beberapa saat.',
                    'sources' => [],
                    'from_cache' => false,
                    'error_type' => 'service_unavailable',
                ], 200);
            }

            // Handle other errors (500, etc)
            if ($response->failed()) {
                Log::error('RAG API error', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json([
                    'answer' => 'Maaf, terjadi kesalahan pada sistem. Silakan coba lagi.',
                    'sources' => [],
                    'from_cache' => false,
                    'error_type' => 'server_error',
                ], 200);
            }

            // Success
            return response()->json($response->json());

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('RAG service connection failed', ['error' => $e->getMessage()]);
            return response()->json([
                'answer' => 'Maaf, tidak dapat terhubung ke layanan chatbot. Pastikan server RAG sedang berjalan.',
                'sources' => [],
                'from_cache' => false,
                'error_type' => 'connection_error',
            ], 200);
        }
    }
}

