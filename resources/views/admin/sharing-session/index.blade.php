@extends('layouts.app')

@section('title', 'Sharing Session')

@section('content')
@php
    $totalJadwal = $sessions->count();
    $hariIni = $sessions->filter(fn($s) => $s->session_date->isToday())->count();
    $akanDatang = $sessions->filter(fn($s) => $s->session_date->isFuture())->count();
    $selesai = $sessions->filter(fn($s) => $s->session_date->isPast() && !$s->session_date->isToday())->count();
@endphp

<div class="min-h-screen bg-[#F4F7FF] px-6 py-8">
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-800 to-indigo-600 rounded-3xl p-8 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider mb-2">Admin Panel</p>
                    <h1 class="text-3xl font-bold">Kelola Sharing Session</h1>
                    <p class="text-blue-100 mt-2">Atur jadwal sharing session dan link Google Form evaluasi peserta magang.</p>
                </div>

                <a href="{{ route('admin.sharing-session.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-5 py-3 rounded-2xl font-semibold shadow hover:bg-blue-50 transition">
                    <i class="fas fa-plus"></i>
                    Tambah Jadwal
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-blue-50">
            <p class="text-sm text-gray-400 font-semibold uppercase">Total Jadwal</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalJadwal }}</h2>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-50">
            <p class="text-sm text-gray-400 font-semibold uppercase">Hari Ini</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">{{ $hariIni }}</h2>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-yellow-50">
            <p class="text-sm text-gray-400 font-semibold uppercase">Akan Datang</p>
            <h2 class="text-3xl font-bold text-yellow-600 mt-2">{{ $akanDatang }}</h2>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-red-50">
            <p class="text-sm text-gray-400 font-semibold uppercase">Selesai</p>
            <h2 class="text-3xl font-bold text-red-500 mt-2">{{ $selesai }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Daftar Sharing Session</h2>
                <p class="text-sm text-gray-400 mt-1">Jadwal kegiatan sharing session di kantor</p>
            </div>

            <form method="GET">
                <select name="filter"
                        onchange="this.form.submit()"
                        class="border border-gray-200 rounded-2xl px-4 py-2 text-sm font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="semua" {{ ($filter ?? 'semua') == 'semua' ? 'selected' : '' }}>📋 Semua</option>
                    <option value="hari-ini" {{ ($filter ?? 'semua') == 'hari-ini' ? 'selected' : '' }}>🔥 Hari Ini</option>
                    <option value="akan-datang" {{ ($filter ?? 'semua') == 'akan-datang' ? 'selected' : '' }}>📅 Akan Datang</option>
                    <option value="selesai" {{ ($filter ?? 'semua') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Jadwal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Narasumber</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Moderator</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Link</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="px-6 py-5">
                                <div class="font-bold text-gray-800">{{ $session->title }}</div>
                                <div class="text-sm text-gray-400 mt-1">{{ $session->description ?? 'Tidak ada deskripsi' }}</div>
                            </td>

                            <td class="px-6 py-5 text-gray-600">
                                <div>
                                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                    {{ $session->session_date->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-400 mt-1">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '-' }} WITA - Selesai
                                </div>
                            </td>

                            <td class="px-6 py-5 text-gray-600">
                                <i class="fas fa-user-tie text-purple-500 mr-2"></i>
                                {{ $session->speaker?->name ?? '-' }}
                            </td>

                            <td class="px-6 py-5 text-gray-600">
                                <i class="fas fa-user-check text-indigo-500 mr-2"></i>
                                {{ $session->moderator?->name ?? '-' }}
                            </td>

                            <td class="px-6 py-5 text-gray-600">
                                <i class="fas fa-map-marker-alt text-orange-400 mr-2"></i>
                                {{ $session->location ?? '-' }}
                            </td>

                            <td class="px-6 py-5">
                                @if($session->session_date->isToday())
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">Hari Ini</span>
                                @elseif($session->session_date->isPast())
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm font-semibold">Selesai</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">Akan Datang</span>
                                @endif
                            </td>

                            <td class="px-6 py-5">
                                @if($session->evaluation_form_link)
                                    <a href="{{ $session->evaluation_form_link }}" target="_blank"
                                       class="text-blue-600 font-semibold hover:text-blue-800">
                                        Buka Form
                                    </a>
                                @else
                                    <span class="text-gray-400">Belum ada</span>
                                @endif
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.sharing-session.edit', $session) }}"
                                       class="w-9 h-9 rounded-xl bg-yellow-100 text-yellow-700 flex items-center justify-center">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.sharing-session.destroy', $session) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Hapus jadwal ini?')"
                                                class="w-9 h-9 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                Belum ada jadwal sharing session.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection