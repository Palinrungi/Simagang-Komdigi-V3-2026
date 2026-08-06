@extends('layouts.app')

@section('title', 'Edit Slide Hero - Admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Slide Hero</h1>
                <p class="text-slate-500 text-sm">Perbarui informasi slide carousel</p>
            </div>
            <a href="{{ route('admin.hero-sliders.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-sm transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('admin.hero-sliders.update', $heroSlider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Slide</label>
                        <select name="type" id="sliderType" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" onchange="toggleTypeForm()">
                            <option value="general" {{ old('type', $heroSlider->type) === 'general' ? 'selected' : '' }}>General (Teks, Statistik & Tombol)</option>
                            <option value="youtube" {{ old('type', $heroSlider->type) === 'youtube' ? 'selected' : '' }}>Video YouTube (Panduan / Event)</option>
                            <option value="sharing_session" {{ old('type', $heroSlider->type) === 'sharing_session' ? 'selected' : '' }}>Jadwal Sharing Session (Otomatis)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil (Order)</label>
                        <input type="number" name="order" value="{{ old('order', $heroSlider->order) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>
                </div>

                <div class="mb-6" id="titleSection">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Slide *</label>
                    <input type="text" name="title" value="{{ old('title', $heroSlider->title) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div class="mb-6" id="subtitleSection">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi / Subjudul</label>
                    <textarea name="subtitle" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">{{ old('subtitle', $heroSlider->subtitle) }}</textarea>
                </div>

                <div class="mb-6" id="imageSection">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Gambar Background</label>
                    @if($heroSlider->image_path)
                        <div class="mb-3">
                            <img src="{{ $heroSlider->image_url }}" class="w-32 h-20 object-cover rounded-xl border" alt="Existing Image">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-sm bg-slate-50">
                    <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</p>
                </div>

                <div id="youtubeSection" class="{{ $heroSlider->type === 'youtube' ? '' : 'hidden' }} p-4 bg-red-50 rounded-xl border border-red-200 mb-6">
                    <label class="block text-xs font-bold text-red-800 uppercase tracking-wider mb-2"><i class="fab fa-youtube text-red-600 mr-1"></i> Link Video YouTube</label>
                    <input type="url" name="youtube_url" value="{{ old('youtube_url', $heroSlider->youtube_url) }}" class="w-full px-4 py-2.5 rounded-xl border border-red-300 text-sm focus:ring-2 focus:ring-red-400 focus:outline-none">
                </div>

                <div id="buttonSection" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Teks Tombol Utama</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $heroSlider->button_text) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Link Target Tombol</label>
                        <input type="text" name="button_url" value="{{ old('button_url', $heroSlider->button_url) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                    </div>
                </div>

                <div class="mb-6 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $heroSlider->is_active ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                    <label for="is_active" class="text-sm font-semibold text-slate-700">Aktifkan Slide Ini di Landing Page</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-md transition">
                        <i class="fas fa-save mr-1"></i> Perbarui Slide
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function toggleTypeForm() {
    const type = document.getElementById('sliderType').value;
    const ytSection = document.getElementById('youtubeSection');
    const titleSec = document.getElementById('titleSection');
    const subSec = document.getElementById('subtitleSection');
    const btnSec = document.getElementById('buttonSection');

    if (type === 'youtube') {
        ytSection.classList.remove('hidden');
        titleSec.style.display = 'block';
        subSec.style.display = 'block';
        btnSec.style.display = 'grid';
    } else if (type === 'sharing_session') {
        ytSection.classList.add('hidden');
        titleSec.style.display = 'none';
        subSec.style.display = 'none';
        btnSec.style.display = 'none';
    } else {
        ytSection.classList.add('hidden');
        titleSec.style.display = 'block';
        subSec.style.display = 'block';
        btnSec.style.display = 'grid';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleTypeForm();
});
</script>
@endsection