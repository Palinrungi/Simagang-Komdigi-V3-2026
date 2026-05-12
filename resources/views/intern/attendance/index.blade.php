@extends('layouts.app')

@section('title', 'Absensi - Sistem Magang')

@section('content')
@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }
    
    body.page-attendance { background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%); }

    .dash-bg {
        background: transparent;
        min-height: 100vh;
    }

    /* ── Hero Header (match mentor) ── */
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

    /* ── Avatar ring ── */
    .avatar-ring {
        background: linear-gradient(135deg, #60a5fa, #818cf8);
        padding: 3px;
        border-radius: 9999px;
        display: inline-flex;
        flex-shrink: 0;
    }
    .avatar-inner {
        border-radius: 9999px;
        width: 80px; height: 80px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
    }

    /* ── Panel ── */
    .panel {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(30,58,138,0.06), 0 4px 20px rgba(30,58,138,0.06);
    }

    /* ── Stat tiles ── */
    .stat-tile {
        background: #fff;
        border-radius: 1.25rem;
        padding: 1.4rem;
        box-shadow: 0 1px 3px rgba(30,58,138,0.06), 0 4px 20px rgba(30,58,138,0.06);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .stat-tile:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(59,79,216,0.16); }
    .stat-tile::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: var(--stat-color, #3b82f6); }
    .stat-tile .stat-value { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin: 0.4rem 0 0; font-family: 'DM Mono', monospace; }
    .stat-tile .stat-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
    .stat-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: white; }

    /* ── Section label ── */
    .section-label { font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #94a3b8; margin-bottom: 14px; }

    /* ── Status items ── */
    .status-item { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-radius: 14px; background: #f8faff; border: 1px solid #e8eeff; transition: all 0.2s ease; cursor: default; }
    .status-item:hover { background: #eff2ff; border-color: #c7d2fe; transform: translateX(4px); }

    /* ── Stat bar ── */
    .stat-bar-track { height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden; }
    .stat-bar-fill { height: 100%; border-radius: 999px; transition: width 1.2s cubic-bezier(.4,0,.2,1); width: 0; }

    /* ── Count pill ── */
    .count-pill { font-family: 'DM Mono', monospace; font-size: 13px; font-weight: 500; padding: 3px 12px; border-radius: 999px; min-width: 42px; text-align: center; }

    /* ── Donut ── */
    .donut-svg circle { transition: stroke-dashoffset 1.4s cubic-bezier(.4,0,.2,1); }

    /* ── Action btn ── */
    .action-btn { display: flex; align-items: center; gap: 10px; padding: 13px 18px; border-radius: 14px; background: #f0f4ff; border: 1.5px solid #e0e7ff; color: #3b4fd8; font-weight: 600; font-size: 14px; transition: all 0.2s ease; text-decoration: none; width: 100%; }
    .action-btn:hover { background: #e8eeff; border-color: #a5b4fc; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99,102,241,0.15); color: #3b4fd8; text-decoration: none; }

    /* ── Modal ── */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.55); display:flex;align-items:center;justify-content:center; z-index:50; backdrop-filter: blur(3px); }
    .modal-box { background:#fff;border-radius:20px;padding:28px;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(30,58,138,0.18);position:relative }

    /* ── Leaderboard items ── */
    .lb-item { display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-radius:14px;background:#f0f4ff;border:1px solid #e0e7ff;transition: all .2s ease; }
    .lb-item:hover { background:#e8eeff;border-color:#c7d2fe;transform:translateX(4px);} .lb-item.is-me{background:#ede9fe;border-color:#c4b5fd}

    /* ── Divider ── */
    .divider { height: 1px; background: #f1f5f9; margin: 4px 0; }

    /* ── CTA ── */
    .cta-btn { display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:linear-gradient(110deg,#1e3a8a,#3b4fd8);color:#fff;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s ease }
    .cta-btn:hover { box-shadow:0 6px 16px rgba(59,79,216,0.3); transform:translateY(-1px); color:#fff }

    /* ── Attendance info rows ── */
    .info-row { display:flex;align-items:center;justify-content:space-between;padding:11px 14px;border-radius:10px;background:#f8faff;border:1px solid #e8eeff;font-size:14px }

    /* ── Animations ── */
    @keyframes fadeSlideUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    .anim-1{animation:fadeSlideUp .5s ease both}.anim-2{animation:fadeSlideUp .5s ease .1s both}.anim-3{animation:fadeSlideUp .5s ease .2s both}.anim-4{animation:fadeSlideUp .5s ease .3s both}

    @media (max-width:640px){ .avatar-inner{width:60px;height:60px} .panel{padding:16px} .stat-tile{padding:1rem} .stat-tile .stat-value{font-size:1.4rem} }
</style>
@endpush

    <div class="dash-bg py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="hero-strip shadow-xl a1 mb-6">
                <div class="relative z-10 px-6 py-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold leading-tight text-white mb-1">Riwayat Absensi</h1>
                            <p class="text-blue-200">Pantau dan kelola absensi harianmu</p>
                        </div>
                        @if ($cekaktif)
                            <a href="{{ route('intern.attendance.create') }}" class="cta-btn">
                                <i class="fas fa-plus"></i>
                                Absensi Baru
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Statistics Cards (Optional - untuk informasi tambahan) -->
            @if ($attendances->count() > 0 || $todayVirtualAbsent)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                    <!-- Total Hadir -->
                    <div class="stat-tile" style="--stat-color:#22c55e;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Kehadiran</p>
                                <p class="text-2xl font-extrabold mt-2">{{ $totalHadir }}</p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Izin -->
                    <div class="stat-tile" style="--stat-color:#f59e0b;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Izin</p>
                                <p class="text-2xl font-extrabold mt-2">{{ $totalIzin }}</p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sakit -->
                    <div class="stat-tile" style="--stat-color:#ef5350;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Sakit</p>
                                <p class="text-2xl font-extrabold mt-2">{{ $totalSakit }}</p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#ef5350,#e53935);">
                                <i class="fas fa-calendar-minus"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Tidak Hadir -->
                    <div class="stat-tile" style="--stat-color:#f97316;">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Tidak Hadir</p>
                                <p class="text-2xl font-extrabold mt-2">{{ $totalTidakHadir + ($todayVirtualAbsent ? 1 : 0) }}</p>
                            </div>
                            <div class="stat-icon" style="background:linear-gradient(135deg,#f97316,#ea580c);">
                                <i class="fas fa-user-times"></i>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            <!-- Attendance Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden mt-8">
                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Data Absensi
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
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Check In</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Foto Check In</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Check Out</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Foto Check Out</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Keterangan</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">
                                        Status Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                {{-- Baris virtual hari ini: belum absen di hari kerja --}}
                                @if ($cekaktif && $todayVirtualAbsent)
                                    <tr class="bg-red-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($todayWita)->format('d/m/Y') }}
                                            </span>
                                            <span class="ml-1 text-xs text-gray-400">(Hari ini)</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Tidak Hadir
                                            </span>
                                        </td>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-400 italic">
                                            Belum melakukan absensi hari ini
                                        </td>
                                    </tr>
                                @endif
                                @forelse($attendances as $attendance)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $attendance->date->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($attendance->status == 'hadir') bg-green-100 text-green-800
                                            @elseif($attendance->status == 'izin') bg-yellow-100 text-yellow-800
                                            @elseif($attendance->status == 'sakit') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800 @endif">
                                                @if ($attendance->status == 'alfa')
                                                    Tidak Hadir
                                                @else
                                                    {{ ucfirst($attendance->status) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                            @if ($attendance->check_in)
                                                <div class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}</div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($attendance->photo_path)
                                                @php
                                                    $filename = basename($attendance->photo_path);
                                                    $photoUrl = route('intern.attendance.photo', [
                                                        'filename' => $filename,
                                                    ]);
                                                @endphp
                                                <img src="{{ $photoUrl }}" alt="Check In"
                                                    class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all shadow-sm mx-auto"
                                                    onclick="window.open('{{ $photoUrl }}', '_blank')"
                                                    title="Klik untuk melihat full size">
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                            @if ($attendance->check_out)
                                                <div class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($attendance->photo_checkout)
                                                @php
                                                    $filename = basename($attendance->photo_checkout);
                                                    $photoUrl = route('intern.attendance.photo', [
                                                        'filename' => $filename,
                                                    ]);
                                                @endphp
                                                <img src="{{ $photoUrl }}" alt="Check Out"
                                                    class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all shadow-sm mx-auto"
                                                    onclick="window.open('{{ $photoUrl }}', '_blank')"
                                                    title="Klik untuk melihat full size">
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                            @if ($attendance->note)
                                                <div class="max-w-xs truncate mx-auto text-center"
                                                    title="{{ $attendance->note }}">
                                                    {{ $attendance->note }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($attendance->document_status)
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if ($attendance->document_status == 'approved') bg-green-100 text-green-800
                                                @elseif($attendance->document_status == 'rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                    @if ($attendance->document_status == 'approved')
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                    @elseif($attendance->document_status == 'rejected')
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                    @else
                                                        <i class="fas fa-clock mr-1"></i>
                                                    @endif
                                                    {{ ucfirst($attendance->document_status) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                                <p class="text-lg font-medium">Belum ada data absensi.</p>
                                                <p class="text-sm mt-2">Mulai dengan membuat absensi baru.</p>
                                                @if ($cekaktif)
                                                    <a href="{{ route('intern.attendance.create') }}"
                                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300">
                                                        <i class="fas fa-plus mr-2"></i>Buat Absensi
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
                    @if ($attendances->count() > 0)
                        <div class="mt-6">
                            {{ $attendances->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>document.body.classList.add('page-attendance');</script>
@endpush

@endsection
