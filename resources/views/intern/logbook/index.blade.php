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

            .cta-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 11px 20px;
                background: linear-gradient(110deg, #1e3a8a, #3b4fd8);
                color: #fff;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                transition: all .2s ease;
            }

            .cta-btn:hover {
                box-shadow: 0 6px 16px rgba(59, 79, 216, 0.3);
                transform: translateY(-1px);
                color: #fff;
            }

            .excel-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 11px 20px;
                background: linear-gradient(110deg, #059669, #10b981);
                color: #fff;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                transition: all .2s ease;
            }

            .excel-btn:hover {
                box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
                transform: translateY(-1px);
                color: #fff;
            }

            @keyframes fadeSlideUp {
                from {
                    opacity: 0;
                    transform: translateY(16px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .anim-1 { animation: fadeSlideUp .5s ease both; }
            .anim-2 { animation: fadeSlideUp .5s ease .1s both; }
            .anim-3 { animation: fadeSlideUp .5s ease .2s both; }
            .anim-4 { animation: fadeSlideUp .5s ease .3s both; }

            @media (max-width:640px) {
                .panel { padding: 16px; }
                .stat-tile { padding: 1rem; }
            }
        </style>
    @endpush

    <div class="dash-bg py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="hero-strip shadow-xl anim-1 mb-6">
                <div class="relative z-10 px-6 py-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold leading-tight text-white mb-1">Logbook Harian</h1>
                            <p class="text-blue-200">Catat dan kelola aktivitas harianmu</p>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <!-- Tombol Cetak Excel -->
                            <a href="{{ route('intern.logbook.export') }}" class="excel-btn">
                                <i class="fas fa-file-excel"></i> Cetak Excel
                            </a>
                            @if ($cekaktif)
                                <a href="{{ route('intern.logbook.create') }}" class="cta-btn">
                                    <i class="fas fa-plus"></i> Tambah Logbook
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            @if ($logbooks->count() > 0 || true)
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mt-6">
                    <div class="stat-tile anim-2" style="--stat-color:#3b82f6;">
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
                    
                    <div class="stat-tile anim-3" style="--stat-color:#8b5cf6;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">LogBook Bulan Ini</p>
                                <p class="text-2xl font-extrabold mt-2">
                                    {{ $thisMonthCount ?? 0 }}
                                </p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-tile col-span-2 lg:col-span-1 anim-4" style="--stat-color:#ef4444;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Logbook yang harus diselesaikan</p>
                                <p class="text-2xl font-extrabold mt-2">
                                    {{ $belumDikerjakan ?? 0 }}
                                </p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                                <i class="fas fa-hourglass-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Logbook Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden mt-8">
                <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-list mr-3"></i>
                        Data Logbook Aktivitas
                    </h2>
                    <span class="text-xs font-bold bg-blue-700/60 text-white px-3 py-1 rounded-full border border-blue-400/30">
                        Riwayat Harian
                    </span>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto overflow-y-auto max-h-[500px] scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-blue-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-blue-50">
                                    <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg w-32">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Aktivitas</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider w-32">Foto</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($logbooks as $logbook)
                                    <tr class="{{ ($logbook->approval_status ?? '') === 'approved' ? 'bg-emerald-50' : 'hover:bg-blue-50' }} transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($logbook->date)->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-left">
                                            <div class="text-sm text-gray-900 max-w-xl">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 font-medium leading-relaxed">{{ Str::limit($logbook->activity, 150) }}</div>
                                                    @if (($logbook->approval_status ?? '') === 'approved')
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Disetujui</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($logbook->photo_url)
                                                <img src="{{ $logbook->photo_url }}"
                                                    alt="Logbook photo"
                                                    class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all shadow-sm mx-auto"
                                                    onclick="window.open('{{ $logbook->photo_url }}', '_blank')"
                                                    title="Klik untuk melihat full size">
                                            @else
                                                <span class="text-gray-400 text-xs italic">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex gap-2 justify-center">
                                                <a href="{{ route('intern.logbook.show', $logbook) }}"
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 group"
                                                    title="Detail">
                                                    <svg class="w-4 h-4 text-gray-700 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8z" />
                                                        <circle cx="12" cy="12" r="2.5" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('intern.logbook.edit', $logbook) }}"
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 hover:bg-blue-200 rounded-lg transition-all duration-200 group"
                                                    title="Edit">
                                                    <svg class="w-4 h-4 text-blue-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                    </svg>
                                                </a>
                                                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { url: '{{ route('intern.logbook.destroy', $logbook) }}' } }))"
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-red-100 hover:bg-red-200 rounded-lg transition-all duration-200 group"
                                                    title="Hapus">
                                                    <svg class="w-4 h-4 text-red-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                    </svg>
                                                </button>
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
                    @if ($logbooks->hasPages())
                        <div class="mt-6">{{ $logbooks->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ showDeleteModal: false, deleteUrl: '' }" @open-delete-modal.window="showDeleteModal = true; deleteUrl = $event.detail.url">
        <!-- Modal Backdrop -->
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900 bg-opacity-50 backdrop-blur-sm" x-transition.opacity>
            <!-- Modal Content -->
            <div @click.away="showDeleteModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all" x-show="showDeleteModal" x-transition.scale.origin.bottom>
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-center text-gray-600 mb-6">Apakah Anda yakin ingin menghapus logbook ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex justify-center gap-3">
                    <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors flex items-center gap-2">
                            <i class="fas fa-trash"></i> Ya, Hapus
                        </button>
                    </form>
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