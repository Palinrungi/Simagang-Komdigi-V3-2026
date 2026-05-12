@extends('layouts.app')

@section('title', 'Logbook - Sistem Magang')

@section('content')
    @push('styles')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

            * {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .mono {
                font-family: 'DM Mono', monospace;
            }

            body.page-logbook {
                background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%);
            }

            .dash-bg {
                background: transparent;
                min-height: 100vh;
            }

            .hero-strip {
                background: linear-gradient(110deg, #0f2878 0%, #2d3ecb 55%, #4f46e5 100%);
                border-radius: 20px;
                position: relative;
                overflow: hidden;
                margin-bottom: 28px;
            }

            .hero-strip::before {
                content: '';
                position: absolute;
                top: -80px;
                right: -60px;
                width: 260px;
                height: 260px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 50%;
                pointer-events: none;
            }

            .hero-strip::after {
                content: '';
                position: absolute;
                bottom: -100px;
                left: 25%;
                width: 320px;
                height: 320px;
                background: rgba(255, 255, 255, 0.04);
                border-radius: 50%;
                pointer-events: none;
            }

            .avatar-ring {
                background: linear-gradient(135deg, #60a5fa, #818cf8);
                padding: 3px;
                border-radius: 9999px;
                display: inline-flex;
                flex-shrink: 0;
            }

            .avatar-inner {
                border-radius: 9999px;
                width: 80px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background: linear-gradient(135deg, #3b82f6, #6366f1);
            }

            .panel {
                background: #fff;
                border-radius: 20px;
                padding: 24px;
                box-shadow: 0 1px 3px rgba(30, 58, 138, 0.06), 0 4px 20px rgba(30, 58, 138, 0.06);
            }

            .stat-tile {
                background: #fff;
                border-radius: 1.25rem;
                padding: 1.4rem;
                box-shadow: 0 1px 3px rgba(30, 58, 138, 0.06), 0 4px 20px rgba(30, 58, 138, 0.06);
                position: relative;
                overflow: hidden;
                transition: all .3s ease;
            }

            .stat-tile:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 24px rgba(59, 79, 216, 0.16);
            }

            .stat-tile::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: var(--stat-color, #3b82f6);
            }

            .stat-tile .stat-value {
                font-size: 1.75rem;
                font-weight: 700;
                color: #1f2937;
                margin: 0.4rem 0 0;
                font-family: 'DM Mono', monospace;
            }

            .stat-tile .stat-label {
                font-size: 10px;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.08em;
            }

            .stat-icon {
                width: 2.75rem;
                height: 2.75rem;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                color: white;
            }

            .section-label {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                color: #94a3b8;
                margin-bottom: 14px;
            }

            .status-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 18px;
                border-radius: 14px;
                background: #f8faff;
                border: 1px solid #e8eeff;
                transition: all .2s ease;
            }

            .status-item:hover {
                background: #eff2ff;
                border-color: #c7d2fe;
                transform: translateX(4px);
            }

            .stat-bar-track {
                height: 6px;
                background: #e2e8f0;
                border-radius: 999px;
                overflow: hidden;
            }

            .stat-bar-fill {
                height: 100%;
                border-radius: 999px;
                transition: width 1.2s cubic-bezier(.4, 0, .2, 1);
                width: 0;
            }

            .count-pill {
                font-family: 'DM Mono', monospace;
                font-size: 13px;
                font-weight: 500;
                padding: 3px 12px;
                border-radius: 999px;
                min-width: 42px;
                text-align: center;
            }

            .donut-svg circle {
                transition: stroke-dashoffset 1.4s cubic-bezier(.4, 0, .2, 1);
            }

            .action-btn {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 13px 18px;
                border-radius: 14px;
                background: #f0f4ff;
                border: 1.5px solid #e0e7ff;
                color: #3b4fd8;
                font-weight: 600;
                font-size: 14px;
                transition: all .2s ease;
                width: 100%;
            }

            .action-btn:hover {
                background: #e8eeff;
                border-color: #a5b4fc;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
            }

            .cta-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 11px 24px;
                background: linear-gradient(110deg, #1e3a8a, #3b4fd8);
                color: #fff;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                transition: all .2s ease
            }

            .cta-btn:hover {
                box-shadow: 0 6px 16px rgba(59, 79, 216, 0.3);
                transform: translateY(-1px);
                color: #fff
            }

            .modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.55);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 50;
                backdrop-filter: blur(3px);
            }

            .modal-box {
                background: #fff;
                border-radius: 20px;
                padding: 28px;
                width: 100%;
                max-width: 420px;
                box-shadow: 0 20px 60px rgba(30, 58, 138, 0.18);
                position: relative
            }

            @keyframes fadeSlideUp {
                from {
                    opacity: 0;
                    transform: translateY(16px)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }

            .anim-1 {
                animation: fadeSlideUp .5s ease both
            }

            .anim-2 {
                animation: fadeSlideUp .5s ease .1s both
            }

            .anim-3 {
                animation: fadeSlideUp .5s ease .2s both
            }

            .anim-4 {
                animation: fadeSlideUp .5s ease .3s both
            }

            @media (max-width:640px) {
                .avatar-inner {
                    width: 60px;
                    height: 60px
                }

                .panel {
                    padding: 16px
                }

                .stat-tile {
                    padding: 1rem
                }

                .stat-tile .stat-value {
                    font-size: 1.4rem
                }
            }
        </style>
    @endpush

    <div class="dash-bg py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="hero-strip shadow-xl a1 mb-6">
                <div class="relative z-10 px-6 py-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold leading-tight text-white mb-1">Logbook Harian</h1>
                            <p class="text-blue-200">Catat dan kelola aktivitas harianmu</p>
                        </div>
                        @if ($cekaktif)
                            <a href="{{ route('intern.logbook.create') }}" class="cta-btn">
                                <i class="fas fa-plus"></i>
                                Tambah Logbook
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Cards (kept original logic, restyled) -->
            @if ($logbooks->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    <div class="stat-tile" style="--stat-color:#3b82f6;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Total Logbook</p>
                                <p class="text-2xl font-extrabold mt-2">{{ $totalLogbooks ?? $logbooks->total() }}</p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#2563eb,#3b82f6);">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-tile" style="--stat-color:#10b981;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Dengan Foto</p>
                                <p class="text-2xl font-extrabold mt-2">
                                    {{ $withPhotoCount ?? $logbooks->where('photo_path', '!=', null)->count() }}</p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#10b981,#059669);">
                                <i class="fas fa-image"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-tile" style="--stat-color:#8b5cf6;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Bulan Ini</p>
                                <p class="text-2xl font-extrabold mt-2">
                                    {{ $thisMonthCount ?? $logbooks->where('date', '>=', now()->startOfMonth())->count() }}
                                </p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Logbook Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden mt-8">
                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-list mr-3"></i>
                        Data Logbook
                    </h2>
                </div>
                <div class="p-6">
                    <div
                        class="overflow-x-auto overflow-y-auto max-h-[500px] scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-blue-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-blue-50">
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Aktivitas</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Foto</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($logbooks as $logbook)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center">
                                                <span
                                                    class="text-sm font-medium text-gray-900">{{ $logbook->date->format('d/m/y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-left">
                                            <div class="text-sm text-gray-900 max-w-md">
                                                {{ Str::limit($logbook->activity, 150) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($logbook->photo_path)
                                                @php $filename = basename($logbook->photo_path); @endphp
                                                @can('view', $logbook)
                                                    <img src="{{ route('intern.logbook.photo', $filename) }}"
                                                        alt="Logbook photo"
                                                        class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all shadow-sm mx-auto"
                                                        onclick="window.open('{{ route('intern.logbook.photo', $filename) }}', '_blank')"
                                                        title="Klik untuk melihat full size">
                                                @else
                                                    <span class="text-gray-400">Tidak ada akses</span>
                                                @endcan
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex gap-2 justify-center">
                                                <a href="{{ route('intern.logbook.edit', $logbook) }}"
                                                    class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 hover:bg-blue-200 rounded-lg transition-all duration-200 group"
                                                    title="Edit">
                                                    <svg class="w-5 h-5 text-blue-600 group-hover:scale-110 transition-transform"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('intern.logbook.destroy', $logbook) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus logbook ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 rounded-lg transition-all duration-200 group"
                                                        title="Hapus">
                                                        <svg class="w-5 h-5 text-red-600 group-hover:scale-110 transition-transform"
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
                                                <p class="text-lg font-medium">Belum ada logbook.</p>
                                                <p class="text-sm mt-2">Mulai dengan membuat logbook pertama.</p>
                                                @if ($cekaktif)
                                                    <a href="{{ route('intern.logbook.create') }}"
                                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300">
                                                        <i class="fas fa-plus mr-2"></i>Tambah Logbook
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($logbooks->count() > 0)
                        <div class="mt-6">{{ $logbooks->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.body.classList.add('page-logbook');
        </script>
    @endpush

@endsection
