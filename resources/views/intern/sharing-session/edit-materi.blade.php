@extends('layouts.app')

@section('title', 'Lengkapi Materi Sharing Session')

@section('content')
<div class="min-h-screen bg-[#F4F7FF] px-6 py-8">
    <div class="mb-8 bg-gradient-to-r from-blue-800 to-indigo-600 rounded-3xl p-8 text-white shadow-lg">
        <h1 class="text-3xl font-bold">Lengkapi Materi Sharing Session</h1>
        <p class="text-blue-100 mt-2">
            Isi judul, deskripsi, dan link evaluasi untuk jadwal yang Anda bawakan.
        </p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6 bg-blue-50 border border-blue-100 rounded-2xl p-5 text-sm text-gray-600">
            <p><strong>Tanggal:</strong> {{ $sharingSession->session_date->format('d M Y') }}</p>
            <p><strong>Jam:</strong> {{ $sharingSession->start_time ? \Carbon\Carbon::parse($sharingSession->start_time)->format('H:i') : '-' }} WITA - Selesai</p>
            <p><strong>Lokasi:</strong> {{ $sharingSession->location ?? '-' }}</p>
            <p><strong>Moderator:</strong> {{ $sharingSession->moderator?->name ?? '-' }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                <strong>Terdapat kesalahan input:</strong>
                <ul class="list-disc list-inside text-sm mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('intern.sharing-session.update-materi', $sharingSession) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        Judul Sharing Session
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $sharingSession->title ?? '') }}"
                           placeholder="Contoh: Cyber Security Awareness"
                           class="w-full border border-gray-200 rounded-2xl px-4 py-3"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        Deskripsi Materi
                    </label>
                    <textarea name="description"
                              rows="5"
                              placeholder="Tuliskan ringkasan materi sharing session..."
                              class="w-full border border-gray-200 rounded-2xl px-4 py-3">{{ old('description', $sharingSession->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        Link Google Form Evaluasi
                    </label>
                    <input type="url"
                           name="evaluation_form_link"
                           value="{{ old('evaluation_form_link', $sharingSession->evaluation_form_link ?? '') }}"
                           placeholder="https://forms.gle/..."
                           class="w-full border border-gray-200 rounded-2xl px-4 py-3">
                    <p class="text-xs text-gray-400 mt-2">
                        Link ini akan muncul untuk peserta pada hari pelaksanaan sharing session.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('intern.sharing-session.index') }}"
                   class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 font-semibold">
                    Batal
                </a>

                <button class="px-6 py-3 rounded-2xl bg-blue-600 text-white font-semibold">
                    Simpan Materi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection