<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::orderBy('order', 'asc')->get();
        return view('admin.hero_sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.hero_sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'type' => 'required|in:general,youtube,sharing_session', // <-- Diperbarui di sini
            'order' => 'required|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hero-sliders', 'public');
        }

        HeroSlider::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'image_path' => $imagePath,
            'button_text' => $validated['button_text'],
            'button_url' => $validated['button_url'],
            'youtube_url' => $validated['youtube_url'],
            'type' => $validated['type'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slide hero berhasil ditambahkan!');
    }

    public function edit(HeroSlider $heroSlider)
    {
        return view('admin.hero_sliders.edit', compact('heroSlider'));
    }

    public function update(Request $request, HeroSlider $heroSlider)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'type' => 'required|in:general,youtube,sharing_session', // <-- Diperbarui di sini
            'order' => 'required|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $heroSlider->image_path;
        if ($request->hasFile('image')) {
            if ($heroSlider->image_path && Storage::disk('public')->exists($heroSlider->image_path)) {
                Storage::disk('public')->delete($heroSlider->image_path);
            }
            $imagePath = $request->file('image')->store('hero-sliders', 'public');
        }

        $heroSlider->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'image_path' => $imagePath,
            'button_text' => $validated['button_text'],
            'button_url' => $validated['button_url'],
            'youtube_url' => $validated['youtube_url'],
            'type' => $validated['type'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slide hero berhasil diperbarui!');
    }

    public function destroy(HeroSlider $heroSlider)
    {
        if ($heroSlider->image_path && Storage::disk('public')->exists($heroSlider->image_path)) {
            Storage::disk('public')->delete($heroSlider->image_path);
        }

        $heroSlider->delete();

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slide hero berhasil dihapus!');
    }
}