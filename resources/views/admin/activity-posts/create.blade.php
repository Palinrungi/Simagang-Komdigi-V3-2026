@extends('layouts.app')

@section('title', 'Tambah Aktivitas')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('admin.aktivitas.index') }}"
               class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 mb-4">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>

            <h1 class="text-3xl font-extrabold text-slate-900">
                Tambah Aktivitas
            </h1>

            <p class="text-slate-500 mt-2">
                Buat artikel atau konten YouTube untuk landing page.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4">
                <p class="font-bold mb-2">Ada data yang perlu diperbaiki:</p>
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.aktivitas.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
            @csrf

            {{-- Tipe Konten --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Tipe Konten
                </label>

                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio"
                               name="type"
                               value="artikel"
                               class="hidden activity-type"
                               {{ old('type', 'artikel') === 'artikel' ? 'checked' : '' }}>

                        <div class="type-card rounded-2xl border-2 p-4 text-center">
                            <i class="fas fa-newspaper text-2xl mb-2 text-blue-600"></i>
                            <p class="font-extrabold text-slate-800">Artikel</p>
                            <p class="text-xs text-slate-400 mt-1">Upload thumbnail sendiri</p>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio"
                               name="type"
                               value="youtube"
                               class="hidden activity-type"
                               {{ old('type') === 'youtube' ? 'checked' : '' }}>

                        <div class="type-card rounded-2xl border-2 p-4 text-center">
                            <i class="fab fa-youtube text-2xl mb-2 text-red-600"></i>
                            <p class="font-extrabold text-slate-800">YouTube</p>
                            <p class="text-xs text-slate-400 mt-1">Thumbnail otomatis dari link</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Judul
                </label>

                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan judul aktivitas">
            </div>

            {{-- Thumbnail Artikel --}}
            <div id="thumbnailField">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Thumbnail Artikel
                </label>

                <input type="file"
                       name="thumbnail"
                       accept="image/*"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white">

                <p class="text-xs text-slate-400 mt-2">
                    Khusus artikel. Untuk YouTube, thumbnail otomatis diambil dari link YouTube.
                </p>
            </div>

            {{-- Info thumbnail YouTube --}}
            <div id="youtubeThumbnailInfo"
                 class="hidden rounded-2xl bg-red-50 border border-red-100 px-5 py-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <i class="fab fa-youtube"></i>
                    </div>

                    <div>
                        <p class="font-bold text-red-700 text-sm">
                            Thumbnail YouTube otomatis
                        </p>
                        <p class="text-sm text-red-500 mt-1 leading-relaxed">
                            Kamu tidak perlu upload gambar. Sistem akan mengambil thumbnail langsung dari link YouTube yang dimasukkan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Ringkasan
                </label>

                <textarea name="excerpt"
                          rows="3"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:outline-none"
                          placeholder="Ringkasan singkat untuk card landing page">{{ old('excerpt') }}</textarea>
            </div>

            {{-- Link YouTube --}}
            <div id="youtubeField">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Link YouTube
                </label>

                <input type="url"
                       name="youtube_url"
                       value="{{ old('youtube_url') }}"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:outline-none"
                       placeholder="https://www.youtube.com/watch?v=...">

                <p class="text-xs text-slate-400 mt-2">
                    Bisa pakai link YouTube biasa, youtu.be, Shorts, atau embed.
                </p>
            </div>

            {{-- Isi Artikel --}}
            <div id="contentField">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Isi Artikel
                </label>

                <textarea name="content"
                          rows="10"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:outline-none"
                          placeholder="Tulis isi artikel di sini">{{ old('content') }}</textarea>
            </div>

            {{-- Deskripsi Video --}}
            <div id="youtubeDescriptionField">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Deskripsi Video
                </label>

                <textarea name="content_youtube_display"
                          rows="5"
                          class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-red-200 focus:outline-none"
                          placeholder="Opsional: tulis deskripsi singkat video YouTube">{{ old('content') }}</textarea>

                <p class="text-xs text-slate-400 mt-2">
                    Deskripsi ini opsional. Kalau kosong, sistem tetap menampilkan video dari link YouTube.
                </p>
            </div>

            {{-- Hidden content final untuk YouTube --}}
            <input type="hidden" name="content" id="finalContent" value="{{ old('content') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Tanggal Publikasi
                    </label>

                    <input type="date"
                           name="published_at"
                           value="{{ old('published_at', now()->format('Y-m-d')) }}"
                           class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:outline-none">
                </div>

                <div class="flex items-end">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               class="w-5 h-5 rounded"
                               {{ old('is_published', true) ? 'checked' : '' }}>

                        <span class="font-bold text-slate-700">
                            Publish sekarang
                        </span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.aktivitas.index') }}"
                   class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-600 font-bold">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-3 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700">
                    Simpan Aktivitas
                </button>
            </div>
        </form>

    </div>
</div>

<style>
    .activity-type:checked + .type-card {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .type-card {
        border-color: #e2e8f0;
        transition: all .2s ease;
    }

    .type-card:hover {
        border-color: #93c5fd;
        background: #f8fbff;
    }
</style>

<script>
    function toggleActivityFields() {
        const selected = document.querySelector('input[name="type"]:checked')?.value || 'artikel';

        const youtubeField = document.getElementById('youtubeField');
        const contentField = document.getElementById('contentField');
        const thumbnailField = document.getElementById('thumbnailField');
        const youtubeThumbnailInfo = document.getElementById('youtubeThumbnailInfo');
        const youtubeDescriptionField = document.getElementById('youtubeDescriptionField');

        const finalContent = document.getElementById('finalContent');
        const articleTextarea = contentField ? contentField.querySelector('textarea') : null;
        const youtubeTextarea = youtubeDescriptionField ? youtubeDescriptionField.querySelector('textarea') : null;

        if (selected === 'youtube') {
            youtubeField.style.display = 'block';
            youtubeThumbnailInfo.style.display = 'block';
            youtubeDescriptionField.style.display = 'block';

            thumbnailField.style.display = 'none';
            contentField.style.display = 'none';

            if (articleTextarea) {
                articleTextarea.removeAttribute('name');
            }

            if (youtubeTextarea) {
                youtubeTextarea.setAttribute('name', 'content');
            }

            if (finalContent) {
                finalContent.removeAttribute('name');
            }
        } else {
            youtubeField.style.display = 'none';
            youtubeThumbnailInfo.style.display = 'none';
            youtubeDescriptionField.style.display = 'none';

            thumbnailField.style.display = 'block';
            contentField.style.display = 'block';

            if (articleTextarea) {
                articleTextarea.setAttribute('name', 'content');
            }

            if (youtubeTextarea) {
                youtubeTextarea.removeAttribute('name');
            }

            if (finalContent) {
                finalContent.removeAttribute('name');
            }
        }
    }

    document.querySelectorAll('input[name="type"]').forEach(input => {
        input.addEventListener('change', toggleActivityFields);
    });

    toggleActivityFields();
</script>
@endsection