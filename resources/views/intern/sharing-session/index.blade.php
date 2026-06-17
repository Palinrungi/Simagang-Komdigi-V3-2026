@extends('layouts.app')

@section('title', 'Sharing Session')

@section('content')
<div class="min-h-screen bg-[#F4F7FF] px-6 py-8">

    <div class="mb-8 bg-gradient-to-r from-blue-800 to-indigo-600 rounded-3xl p-8 text-white shadow-lg">
        <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider mb-2">
            Peserta Magang
        </p>

        <h1 class="text-3xl font-bold">
            Sharing Session
        </h1>

        <p class="text-blue-100 mt-2">
            Lihat jadwal sharing session dan isi evaluasi pada hari pelaksanaan.
        </p>
    </div>

    <div class="mb-6 flex justify-end">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">

        <select name="filter"
                onchange="this.form.submit()"
                class="bg-white border border-gray-200 rounded-2xl px-5 py-3 shadow-sm text-gray-700 font-medium">

            <option value="semua"
                {{ ($filter ?? 'semua') == 'semua' ? 'selected' : '' }}>
                Semua Jadwal
            </option>

            <option value="hari-ini"
                {{ ($filter ?? 'semua') == 'hari-ini' ? 'selected' : '' }}>
                Hari Ini
            </option>

            <option value="akan-datang"
                {{ ($filter ?? 'semua') == 'akan-datang' ? 'selected' : '' }}>
                Akan Datang
            </option>

            <option value="selesai"
                {{ ($filter ?? 'semua') == 'selesai' ? 'selected' : '' }}>
                Selesai
            </option>
        </select>

        <select name="role_filter"
                onchange="this.form.submit()"
                class="bg-white border border-gray-200 rounded-2xl px-5 py-3 shadow-sm text-gray-700 font-medium">

            <option value="semua"
                {{ ($roleFilter ?? 'semua') == 'semua' ? 'selected' : '' }}>
                Semua Peran
            </option>

            <option value="narasumber-saya"
                {{ ($roleFilter ?? 'semua') == 'narasumber-saya' ? 'selected' : '' }}>
                Saya Narasumber
            </option>

            <option value="moderator-saya"
                {{ ($roleFilter ?? 'semua') == 'moderator-saya' ? 'selected' : '' }}>
                Saya Moderator
            </option>
        </select>

    </form>
</div>

    <div class="grid gap-6">

        @forelse($sessions as $session)

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">

                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                    <div class="flex gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-comments text-blue-600 text-xl"></i>
                        </div>

                        <div>

                            <div class="flex flex-wrap items-center gap-2 mb-2">

                                <h2 class="text-xl font-bold text-gray-800">
                                    {{ $session->title ?? 'Materi Belum Diisi' }}
                                </h2>

                                @if($session->session_date->isToday())
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                        HARI INI
                                    </span>

                                @elseif($session->session_date->isTomorrow())
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">
                                        BESOK
                                    </span>

                                @elseif($session->session_date->isPast())
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-xs font-bold">
                                        SELESAI
                                    </span>

                                @else
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                        AKAN DATANG
                                    </span>
                                @endif

                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-500">

                                <p>
                                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                    {{ $session->session_date->format('d M Y') }}
                                </p>

                                <p>
                                    <i class="fas fa-clock text-green-500 mr-2"></i>
                                    {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '-' }}
                                    WITA - Selesai
                                </p>

                                <p>
                                    <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                                    {{ $session->location ?? '-' }}
                                </p>

                                <p>
                                <i class="fas fa-user-tie text-purple-500 mr-2"></i>
                                Narasumber: {{ $session->speakerUser?->name ?? '-' }}
                                </p>

                                <p>
                                <i class="fas fa-user-check text-indigo-500 mr-2"></i>
                                Moderator: {{ $session->moderatorUser?->name ?? '-' }}
                                </p>

                            </div>

                            @if($session->description)
                                <div class="mt-4 bg-gray-50 rounded-2xl p-4 text-gray-600 text-sm leading-relaxed">
                                    {{ $session->description }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="lg:text-right flex flex-col gap-2">

    @if($session->speaker_user_id === auth()->id())
        <a href="{{ route('intern.sharing-session.edit-materi', $session) }}"
           class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-semibold shadow">
            Lengkapi Materi
        </a>
    @endif

    @if($session->session_date->isToday())

        @if($session->evaluation_form_link)
            <a href="{{ $session->evaluation_form_link }}"
               target="_blank"
               class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-semibold shadow">
                Isi Evaluasi
            </a>
        @else
            <span class="inline-flex items-center justify-center gap-2 bg-yellow-50 text-yellow-700 border border-yellow-200 px-6 py-3 rounded-2xl font-semibold">
                Menunggu Link Evaluasi
            </span>
        @endif

    @elseif($session->session_date->lt(today()))

        <span class="inline-flex items-center justify-center gap-2 bg-red-50 text-red-600 border border-red-200 px-6 py-3 rounded-2xl font-semibold">
            Form Sudah Ditutup
        </span>

    @else

        <span class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-500 border border-gray-200 px-6 py-3 rounded-2xl font-semibold">
            Form Belum Dibuka
        </span>

    @endif

</div>

                </div>

            </div>

        @empty

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">

                <p class="font-semibold text-lg">
                    Belum ada jadwal sharing session.
                </p>

            </div>

        @endforelse

    </div>
@if($sessions->hasPages())
    <div class="mt-8">
        {{ $sessions->links() }}
    </div>
@endif
</div>
@endsection