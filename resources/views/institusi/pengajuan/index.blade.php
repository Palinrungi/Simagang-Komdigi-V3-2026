@extends('layouts.app')

@section('title', 'Pengajuan Magang - Sistem Magang')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

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

        .tile-yellow::after {
            background: linear-gradient(90deg, #f59e0b, #f97316);
        }

        .tile-orange::after {
            background: linear-gradient(90deg, #fb923c, #f97316);
        }

        .tile-red::after {
            background: linear-gradient(90deg, #f43f5e, #e11d48);
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

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 14px;
            background: #f0f4ff;
            border: 1.5px solid #e0e7ff;
            color: #3b4fd8;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            text-decoration: none;
            width: 100%;
        }

        .action-btn:hover {
            background: #e8eeff;
            border-color: #a5b4fc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
            color: #3b4fd8;
            text-decoration: none;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="hero-strip px-5 sm:px-8 py-6 sm:py-8">
                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl text-white">
                        <p class="text-xs sm:text-sm uppercase tracking-[0.3em] text-blue-100/80">Institusi Dashboard</p>
                        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold leading-tight">Pengajuan Magang</h1>
                        <p class="mt-3 text-sm sm:text-base text-blue-100/90">Catat dan kelola pengajuan magangmu.</p>
                    </div>

                    <a href="{{ route('institusi.pengajuan.create') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-blue-700 shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-50">
                        <i class="fas fa-plus mr-2"></i>Tambah Pengajuan
                    </a>
                </div>
            </div>

            @php
                $stats = [
                    [
                        'label' => 'Total Pengajuan',
                        'value' => $totalPengajuan,
                        'tile' => 'tile-blue',
                        'iconBg' => 'linear-gradient(135deg,#3b82f6,#6366f1)',
                        'icon' => 'fa-calendar-check',
                    ],
                    [
                        'label' => 'Disetujui',
                        'value' => $pengajuanApproved,
                        'tile' => 'tile-green',
                        'iconBg' => 'linear-gradient(135deg,#22c55e,#10b981)',
                        'icon' => 'fa-circle-check',
                    ],
                    [
                        'label' => 'Menunggu Approval',
                        'value' => $pengajuanPending,
                        'tile' => 'tile-yellow',
                        'iconBg' => 'linear-gradient(135deg,#f59e0b,#f97316)',
                        'icon' => 'fa-hourglass-half',
                    ],
                    [
                        'label' => 'Revisi',
                        'value' => $pengajuanRevised,
                        'tile' => 'tile-orange',
                        'iconBg' => 'linear-gradient(135deg,#fb923c,#f97316)',
                        'icon' => 'fa-pen-to-square',
                    ],
                    [
                        'label' => 'Ditolak',
                        'value' => $pengajuanRejected,
                        'tile' => 'tile-red',
                        'iconBg' => 'linear-gradient(135deg,#f43f5e,#e11d48)',
                        'icon' => 'fa-circle-xmark',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 sm:gap-6">
                @foreach ($stats as $stat)
                    <div class="stat-tile {{ $stat['tile'] }}">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-xs font-semibold text-gray-500 leading-tight">{{ $stat['label'] }}</p>
                            <div class="tile-icon" style="background:{{ $stat['iconBg'] }};">
                                <i class="fas {{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-900 mono">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-list text-blue-200 text-base"></i>
                    <h2>Data Pengajuan Magang</h2>
                    <span
                        class="ml-auto rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-white">{{ $totalPengajuan }}
                        data</span>
                </div>

                <div class="panel-body">
                    <div class="overflow-x-auto overflow-y-auto max-h-[500px]">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-blue-50">
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg">
                                        Nomor Surat</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Tanggal Pengajuan</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($pengajuans as $pengajuan)
                                    @php
                                        $statusClass = match ($pengajuan->status) {
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'revised' => 'bg-orange-100 text-orange-800',
                                            default => 'bg-yellow-100 text-yellow-800',
                                        };
                                    @endphp
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-medium text-slate-700">{{ $pengajuan->no_surat }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <p class="text-sm text-slate-600">{{ $pengajuan->created_at->format('d/m/y') }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="status-pill {{ $statusClass }}">{{ ucfirst($pengajuan->status) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex gap-2 justify-center">
                                                <a href="{{ route('institusi.pengajuan.show', $pengajuan->id) }}"
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 text-green-700 transition-all duration-200 hover:bg-green-600 hover:text-white"
                                                    title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if ($pengajuan->status === 'revised')
                                                    <a href="{{ route('institusi.pengajuan.edit', $pengajuan->id) }}"
                                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-600 transition-all duration-200 hover:bg-blue-200"
                                                        title="Edit">
                                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform"
                                                            fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <button type="button"
                                                        onclick="alert('Pengajuan hanya bisa diedit ketika status revisi.')"
                                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed"
                                                        title="Edit tidak tersedia untuk status ini">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                                <form action="{{ route('institusi.pengajuan.destroy', $pengajuan->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 text-red-600 transition-all duration-200 hover:bg-red-200"
                                                        title="Hapus">
                                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform"
                                                            fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <i class="fas fa-book text-5xl mb-3 text-gray-300"></i>
                                                <p class="text-lg font-medium">Belum ada pengajuan.</p>
                                                <p class="text-sm mt-2">Mulai dengan membuat pengajuan pertama Anda.</p>
                                                <a href="{{ route('institusi.pengajuan.create') }}"
                                                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300">
                                                    <i class="fas fa-plus mr-2"></i>Tambah Pengajuan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
