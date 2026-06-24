<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityPostController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'semua');

        $posts = ActivityPost::query()
            ->when(in_array($type, ['artikel', 'youtube']), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(8)
            ->withQueryString();

        $totalAll = ActivityPost::count();

        $totalArtikel = ActivityPost::where('type', 'artikel')->count();

        $totalYoutube = ActivityPost::where('type', 'youtube')->count();

        return view('admin.activity-posts.index', compact(
            'posts',
            'type',
            'totalAll',
            'totalArtikel',
            'totalYoutube'
        ));
    }

    public function create()
    {
        return view('admin.activity-posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:artikel,youtube',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'nullable|string',
            'youtube_url'  => 'nullable|url',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul aktivitas wajib diisi.',
            'type.required' => 'Tipe konten wajib dipilih.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format thumbnail harus JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5 MB.',
            'youtube_url.url' => 'Link YouTube harus berupa URL yang valid.',
        ]);

        if ($request->type === 'artikel' && empty($request->content)) {
            return back()
                ->withInput()
                ->withErrors([
                    'content' => 'Isi artikel wajib diisi untuk tipe Artikel.',
                ]);
        }

        if ($request->type === 'youtube' && empty($request->youtube_url)) {
            return back()
                ->withInput()
                ->withErrors([
                    'youtube_url' => 'Link YouTube wajib diisi untuk tipe YouTube.',
                ]);
        }

        $thumbnailPath = null;

        if ($request->type === 'artikel' && $request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('activity-posts', 'public');
        }

        ActivityPost::create([
            'created_by'   => Auth::id(),
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']) . '-' . time(),
            'type'         => $validated['type'],
            'thumbnail'    => $request->type === 'artikel' ? $thumbnailPath : null,
            'excerpt'      => $validated['excerpt'] ?? null,
            'content'      => $validated['content'] ?? null,
            'youtube_url'  => $request->type === 'youtube' ? ($validated['youtube_url'] ?? null) : null,
            'published_at' => $validated['published_at'] ?? now(),
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()
            ->route('admin.aktivitas.index', ['type' => $request->type])
            ->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function show(ActivityPost $aktivita)
    {
        return redirect()->route('admin.aktivitas.edit', $aktivita);
    }

    public function edit(ActivityPost $aktivita)
    {
        $post = $aktivita;

        return view('admin.activity-posts.edit', compact('post'));
    }

    public function update(Request $request, ActivityPost $aktivita)
    {
        $post = $aktivita;

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:artikel,youtube',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'nullable|string',
            'youtube_url'  => 'nullable|url',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul aktivitas wajib diisi.',
            'type.required' => 'Tipe konten wajib dipilih.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format thumbnail harus JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5 MB.',
            'youtube_url.url' => 'Link YouTube harus berupa URL yang valid.',
        ]);

        if ($request->type === 'artikel' && empty($request->content)) {
            return back()
                ->withInput()
                ->withErrors([
                    'content' => 'Isi artikel wajib diisi untuk tipe Artikel.',
                ]);
        }

        if ($request->type === 'youtube' && empty($request->youtube_url)) {
            return back()
                ->withInput()
                ->withErrors([
                    'youtube_url' => 'Link YouTube wajib diisi untuk tipe YouTube.',
                ]);
        }

        $thumbnailPath = $post->thumbnail;

        if ($request->type === 'youtube') {
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            $thumbnailPath = null;
        }

        if ($request->type === 'artikel' && $request->hasFile('thumbnail')) {
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            $thumbnailPath = $request->file('thumbnail')->store('activity-posts', 'public');
        }

        $post->update([
            'title'        => $validated['title'],
            'type'         => $validated['type'],
            'thumbnail'    => $request->type === 'artikel' ? $thumbnailPath : null,
            'excerpt'      => $validated['excerpt'] ?? null,
            'content'      => $validated['content'] ?? null,
            'youtube_url'  => $request->type === 'youtube' ? ($validated['youtube_url'] ?? null) : null,
            'published_at' => $validated['published_at'] ?? now(),
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()
            ->route('admin.aktivitas.index', ['type' => $request->type])
            ->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(ActivityPost $aktivita)
    {
        $post = $aktivita;

        $redirectType = $post->type;

        if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        return redirect()
            ->route('admin.aktivitas.index', ['type' => $redirectType])
            ->with('success', 'Aktivitas berhasil dihapus.');
    }
}