@extends('layouts.app')

@section('title', 'Edit Bukti Mikro Skill - Sistem Magang')

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
                            <h1 class="text-3xl font-bold leading-tight text-white mb-1">Edit Bukti Mikro Skill</h1>
                            <p class="text-blue-200">Perbarui bukti pengumpulan mikro skill Anda</p>
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
                        <i class="fas fa-edit mr-3"></i>
                        Form Edit
                    </h2>
                </div>

                <form id="updateForm" method="POST" action="{{ route('intern.microskill.update', $submission->id) }}"
                    enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Title Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-heading mr-2 text-blue-500"></i>
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $submission->title) }}" required
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
                        <textarea name="description" rows="4" placeholder="Jelaskan detail dari bukti mikro skill ini..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none">{{ old('description', $submission->description) }}</textarea>
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
                            Foto Bukti <span class="text-gray-500 font-normal text-xs">(Opsional)</span>
                        </label>

                        <!-- Current Photo Display -->
                        @if ($submission->photo_path)
                            <div class="mb-6">
                                <div class="bg-white rounded-lg p-4 border border-blue-200">
                                    <p class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-image mr-2 text-blue-500"></i>
                                        Foto Saat Ini
                                    </p>
                                    <div class="flex justify-center">
                                        <img src="{{ $submission->photo_url }}" alt="Current Photo"
                                            class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg h-auto rounded-lg border-2 border-blue-300 shadow-md mx-auto object-contain">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- File Input -->
                        <input type="file" name="photo" accept="image/*" id="photo-input"
                            class="w-full px-4 py-3 border-2 border-dashed border-blue-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer">

                        <div class="mt-4 bg-blue-100 border border-blue-300 rounded-lg p-3">
                            <p class="text-xs text-blue-800 flex items-start">
                                <i class="fas fa-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, GIF (Max. 4MB)</span>
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
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-update-modal'))"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Update Confirmation Modal -->
    <div x-data="{ showUpdateModal: false }" @open-update-modal.window="showUpdateModal = true">
        <!-- Modal Backdrop -->
        <div x-show="showUpdateModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900 bg-opacity-50 backdrop-blur-sm" x-transition.opacity>
            <!-- Modal Content -->
            <div @click.away="showUpdateModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all" x-show="showUpdateModal" x-transition.scale.origin.bottom>
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-question text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi Perubahan</h3>
                <p class="text-center text-gray-600 mb-6">Apakah Anda yakin ingin menyimpan perubahan mikro skill ini?</p>
                <div class="flex justify-center gap-3">
                    <button type="button" @click="showUpdateModal = false" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="document.getElementById('updateForm').submit()" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i> Ya, Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>document.body.classList.add('page-microskill');</script>
    @endpush

@endsection
