@extends('layouts.app')

@section('title', 'Nilai Sertifikat - Institusi')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

        *,
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .dash-bg {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%);
        }

        .hero-strip {
            background: linear-gradient(110deg, #1e3a8a 0%, #3b4fd8 55%, #4f46e5 100%);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(20, 40, 120, 0.16);
        }

        .hero-strip::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -50px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-strip::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: 18%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            pointer-events: none;
        }

        .stat-tile {
            background: #fff;
            border-radius: 18px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(20, 40, 120, 0.06), 0 4px 16px rgba(20, 40, 120, 0.06);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: transform .2s ease, box-shadow .2s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-tile::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 0 0 18px 18px;
        }

        .stat-tile:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(20, 40, 120, 0.12);
        }

        .tile-blue::after {
            background: linear-gradient(90deg, #3b82f6, #6366f1);
        }

        .tile-green::after {
            background: linear-gradient(90deg, #22c55e, #10b981);
        }

        .tile-indigo::after {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
        }

        .tile-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }

        .panel {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(20, 40, 120, 0.06), 0 4px 18px rgba(20, 40, 120, 0.06);
            overflow: hidden;
        }

        .panel-header {
            background: linear-gradient(100deg, #1e3a8a 0%, #3b4fd8 100%);
            padding: 16px 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panel-header h2 {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.01em;
            margin: 0;
        }

        .panel-body {
            padding: 20px 22px;
        }

        .field-input {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #dbe3f0;
            background: #fff;
            padding: 0.9rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #e8eeff;
            border-radius: 18px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="hero-strip px-6 py-7 sm:px-8 sm:py-8">
                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl text-white">
                        <p class="text-xs sm:text-sm uppercase tracking-[0.3em] text-blue-100/80">Institusi Dashboard</p>
                        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold leading-tight">Nilai Sertifikat Peserta Magang</h1>
                        <p class="mt-3 text-sm sm:text-base text-blue-100/90">Lihat dan cek nilai sertifikat untuk peserta
                            magang yang terdaftar di institusimu.</p>
                    </div>

                    <div
                        class="bg-white/10 border border-white/10 rounded-2xl px-4 py-4 text-white shadow-sm backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-blue-100/80">Data</p>
                                <p class="text-base font-bold">{{ $certificates->total() }} Sertifikat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-search text-blue-200 text-base"></i>
                    <h2>Cari Sertifikat</h2>
                </div>
                <div class="panel-body">
                    <form method="GET" action="{{ route('institusi.certificate.index') }}">
                        <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
                            <div class="flex-1">
                                <label for="search" class="field-label">Pencarian</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Cari nama intern atau nomor sertifikat..." class="field-input" />
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl whitespace-nowrap">
                                    <i class="fas fa-search mr-2"></i>Cari
                                </button>
                                @if (request()->filled('search'))
                                    <a href="{{ route('institusi.certificate.index') }}"
                                        class="inline-flex items-center justify-center rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-table text-blue-200 text-base"></i>
                    <h2>Data Sertifikat</h2>
                </div>
                <div class="panel-body">
                    <div class="table-wrap">
                        <table class="w-full divide-y divide-gray-200">
                            <thead>
                                <tr
                                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-left text-xs uppercase tracking-wider">
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Nama Peserta Magang</th>
                                    <th class="px-4 py-3">No. Sertifikat</th>
                                    <th class="px-4 py-3">Terbit</th>
                                    <th class="px-4 py-3">Nilai Sertifikat</th>
                                    <th class="px-4 py-3">Grade Sertifikat</th>
                                    <th class="px-4 py-3">Nilai Laporan</th>
                                    <th class="px-4 py-3">Grade Laporan</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($certificates as $certificate)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-4 py-4 text-sm font-semibold text-slate-700">
                                            {{ $loop->iteration + ($certificates->currentPage() - 1) * $certificates->perPage() }}
                                        </td>
                                        <td class="px-4 py-4 text-sm font-semibold text-slate-900">
                                            {{ $certificate->intern->name }}</td>
                                        <td class="px-4 py-4 text-sm text-slate-700">{{ $certificate->certificate_number }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            {{ optional($certificate->issue_date)->translatedFormat('d F Y') ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            {{ $certificate->score ? $certificate->score->average : '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            {{ $certificate->score ? scoreToGrade($certificate->score->average) : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            {{ $certificate->intern->finalReport ? $certificate->intern->finalReport->score ?? '-' : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            {{ $certificate->intern->finalReport ? $certificate->intern->finalReport->grade ?? '-' : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            <a href="{{ route('institusi.certificate.show', $certificate) }}"
                                                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-2 text-white shadow-sm transition hover:shadow-md">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4 shadow-md">
                                                    <i class="fas fa-certificate text-4xl text-gray-300"></i>
                                                </div>
                                                <p class="text-lg font-bold text-gray-700 mb-2">Belum ada sertifikat
                                                    tersedia</p>
                                                <p class="text-sm text-gray-400">Sertifikat akan muncul ketika data tersedia
                                                    untuk institusimu.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($certificates->hasPages())
                        <div class="mt-6">
                            {{ $certificates->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
