<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminRagController extends Controller
{
    private $ragBaseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->ragBaseUrl = env('RAG_SERVICE_URL');
        $this->apiKey = env('RAG_SERVICE_API_KEY');
    }

    public function index()
    {
        try {
            $response = Http::withToken($this->apiKey)->get($this->ragBaseUrl . '/knowledge/files');
            $files = $response->json('files') ?? [];
        } catch (\Exception $e) {
            $files = [];
            session()->flash('error', 'Gagal terhubung ke RAG Service: ' . $e->getMessage());
        }

        return view('admin.rag.index', compact('files'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:pdf,txt|max:10240', // max 10MB
            'folder' => 'required|in:public_faq,sop'
        ]);

        try {
            $file = $request->file('file');
            
            $response = Http::withToken($this->apiKey)
                ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post($this->ragBaseUrl . '/knowledge/upload', [
                    'folder' => $request->folder
                ]);

            if ($response->successful()) {
                return back()->with('success', 'File berhasil diunggah!');
            }
            return back()->with('error', 'Gagal mengunggah file: ' . $response->body());
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($folder, $filename)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->delete($this->ragBaseUrl . "/knowledge/{$folder}/{$filename}");

            if ($response->successful()) {
                return back()->with('success', 'File berhasil dihapus!');
            }
            return back()->with('error', 'Gagal menghapus file: ' . $response->body());
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function sync()
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->post($this->ragBaseUrl . '/ingest');

            if ($response->successful()) {
                return back()->with('success', 'Proses sinkronisasi AI sedang berjalan di latar belakang!');
            }
            return back()->with('error', 'Gagal memulai sinkronisasi: ' . $response->body());
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->delete($this->ragBaseUrl . '/cache/clear');

            if ($response->successful()) {
                return back()->with('success', 'Semantic cache AI berhasil direset!');
            }
            return back()->with('error', 'Gagal mereset cache: ' . $response->body());
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($folder, $filename)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->get($this->ragBaseUrl . "/knowledge/{$folder}/{$filename}/content");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error' => $response->json('detail') ?? 'Gagal membaca file.'
            ], $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $folder, $filename)
    {
        $request->validate([
            'content' => 'required|string|max:10485760', // max ~10MB
        ]);

        try {
            $response = Http::withToken($this->apiKey)
                ->put($this->ragBaseUrl . "/knowledge/{$folder}/{$filename}/content", [
                    'content' => $request->input('content')
                ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error' => $response->json('detail') ?? 'Gagal memperbarui file.'
            ], $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
