@extends('layouts.app')

@section('title', 'Upload Bukti Mikro Skill - Sistem Magang')

@section('content')
@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body.page-microskill { background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%); }

    .dash-bg { background: transparent; min-height: 100vh; }
    .hero-strip { background: linear-gradient(110deg, #0f2878 0%, #2d3ecb 55%, #4f46e5 100%); border-radius: 20px; position: relative; overflow: hidden; margin-bottom: 28px; }
    .hero-strip::before { content: ''; position: absolute; top: -80px; right: -60px; width: 260px; height: 260px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none; }
    .hero-strip::after { content: ''; position: absolute; bottom: -100px; left: 25%; width: 320px; height: 320px; background: rgba(255,255,255,0.04); border-radius: 50%; pointer-events: none; }
    .cta-btn { display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:linear-gradient(110deg,#1e3a8a,#3b4fd8);color:#fff;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s ease }
    .cta-btn:hover { box-shadow:0 6px 16px rgba(59,79,216,0.3); transform:translateY(-1px); color:#fff }
</style>
@endpush
<div class="min-h-screen bg-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="hero-strip shadow-xl a1 mb-6">
            <div class="relative z-10 px-6 py-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold leading-tight text-white mb-1">Upload Bukti Mikro Skill</h1>
                        <p class="text-blue-200">Unggah bukti pengumpulan mikro skill Anda</p>
                    </div>
                    <a href="{{ route('intern.microskill.index') }}" class="cta-btn" style="width:auto;">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-upload mr-3"></i>
                    Data Micro Skill
                </h2>
            </div>

            <form method="POST" action="{{ route('intern.microskill.store') }}" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <!-- Title Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>
                        Judul <span class="text-red-500">*</span>
                    </label>
                          <input type="text" 
                              name="title" 
                              value="{{ old('title', $suggestedTitle ?? '') }}" 
                           required 
                           placeholder="Masukkan judul mikro skill"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Deskripsi <span class="text-gray-500 font-normal text-xs">(Opsional)</span>
                    </label>
                    <textarea name="description" 
                              rows="4" 
                              placeholder="Jelaskan detail dari bukti mikro skill ini..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Photo Field -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-image mr-2 text-blue-500"></i>
                        Foto Bukti <span class="text-red-500">*</span>
                    </label>
                    
                    <input type="file" 
                           name="photo" 
                           accept="image/*" 
                           required 
                           id="photo-input"
                           class="w-full px-4 py-3 border-2 border-dashed border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">

                    <div class="mt-4 bg-blue-100 border border-blue-300 rounded-lg p-3">
                        <p class="text-xs text-blue-800 flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Pastikan foto jelas dan memenuhi kriteria bukti mikro skill yang valid. Format: JPG, PNG, GIF (Max. 4MB)</span>
                        </p>
                    </div>

                    @error('photo')
                        <p class="mt-3 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('intern.microskill.index') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <i class="fas fa-check mr-2"></i>Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>document.body.classList.add('page-microskill');</script>
    @endpush

</div>
@endsection