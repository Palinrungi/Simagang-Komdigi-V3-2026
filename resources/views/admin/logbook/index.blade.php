@extends('layouts.app')

@section('title', 'Pantau Logbook - Sistem Magang')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .dash-bg {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%);
        }

        .hero-strip {
            background: linear-gradient(100deg, #1e3a8a 0%, #3b4fd8 50%, #4f46e5 100%);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(20, 40, 120, 0.16);
            color: #fff;
        }

        .hero-strip::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .hero-strip::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: 30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .panel {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(30, 58, 138, 0.06), 0 4px 20px rgba(30, 58, 138, 0.06);
            overflow: hidden;
        }

        .input-main {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border: 1px solid #d1d5db;
            border-radius: 0.6rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: all .15s ease;
        }

        .input-main:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        /* FIX: wrapper harus overflow-x: auto agar tabel bisa scroll horizontal */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 560px;
            -webkit-overflow-scrolling: touch;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
        }

        /* FIX: tabel tidak boleh wrap, biarkan scroll */
        .table-wrapper table {
            width: 100%;
            min-width: 860px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* Lebar kolom agar proporsional */
        .col-nama       { width: 200px; }
        .col-institusi  { width: 180px; }
        .col-tanggal    { width: 110px; }
        .col-aktivitas  { width: auto;  }   /* kolom ini yang fleksibel */
        .col-foto       { width: 90px;  }

        /* Header sticky saat scroll vertikal */
        .table-wrapper thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #eff6ff;
        }

        /* Aktivitas: bisa wrap biar tidak terlalu panjang */
        .cell-aktivitas {
            white-space: normal;
            word-break: break-word;
            min-width: 200px;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.6rem;
            }

            .action-mobile {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Hero -->
            <div class="hero-strip mb-6">
                <div class="relative z-10 px-6 py-7">
                    <h1 class="hero-title text-4xl font-bold mb-1">Pantau Logbook Peserta Magang</h1>
                    <p class="text-blue-100">Monitoring aktivitas harian peserta magang</p>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="panel mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-filter mr-3"></i>Filter Data
                    </h2>
                </div>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Cari (nama/institusi)</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="input-main"
                            placeholder="Ketik untuk mencari..." />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-main" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Hingga Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-main" />
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="action-mobile flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if (request()->anyFilled(['q', 'date_from', 'date_to']))
                            <a href="{{ route('admin.logbook.index') }}"
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Logbook Table -->
            <div class="panel overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-book mr-3"></i>Data Logbook
                    </h2>
                </div>
                <div class="p-6">

                    {{-- FIX: gunakan .table-wrapper dengan overflow-x:auto dan overflow-y:auto --}}
                    <div class="table-wrapper">
                        <table>
                            <colgroup>
                                <col class="col-nama">
                                <col class="col-institusi">
                                <col class="col-tanggal">
                                <col class="col-aktivitas">
                                <col class="col-foto">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Nama</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Institusi</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Aktivitas</th>
                                    <th class="px-5 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Foto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($logbooks as $l)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">

                                        {{-- Nama --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                @if ($l->intern->photo_path)
                                                    <img src="{{ url('storage/' . $l->intern->photo_path) }}"
                                                        class="w-9 h-9 rounded-full object-cover border-2 border-blue-200 shrink-0"
                                                        alt="{{ $l->intern->name }}">
                                                @else
                                                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-200 shrink-0">
                                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                                    </div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-900 truncate max-w-[130px]"
                                                    title="{{ $l->intern->name }}">
                                                    {{ $l->intern->name }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Institusi --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600 truncate block max-w-[160px]"
                                                title="{{ $l->intern->institution }}">
                                                {{ $l->intern->institution }}
                                            </span>
                                        </td>

                                        {{-- Tanggal --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($l->date)->format('d/m/Y') }}
                                            </span>
                                        </td>

                                        {{-- Aktivitas --}}
                                        <td class="px-5 py-4 cell-aktivitas">
                                            <p class="text-sm text-gray-900 leading-relaxed">{{ $l->activity }}</p>
                                        </td>

                                        {{-- Foto --}}
                                        <td class="px-5 py-4 text-center whitespace-nowrap">
                                            @if ($l->photo_path)
                                                @php
                                                    $logbookFilename = basename($l->photo_path);
                                                    $logbookUrl = URL::temporarySignedRoute(
                                                        'admin.logbook.photo',
                                                        now()->addSeconds(30),
                                                        ['filename' => $logbookFilename]
                                                    );
                                                @endphp
                                                @can('view', $l)
                                                    <img src="{{ $logbookUrl }}" alt="Logbook"
                                                        class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all mx-auto"
                                                        onclick="window.open('{{ $logbookUrl }}', '_blank')"
                                                        title="Klik untuk melihat full size">
                                                @else
                                                    <span class="text-xs text-gray-400">Tidak ada akses</span>
                                                @endcan
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                                <p class="text-sm">Tidak ada data logbook.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">{{ $logbooks->links() }}</div>
                </div>
            </div>

        </div>
    </div>
@endsection