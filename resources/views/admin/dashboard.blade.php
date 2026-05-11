@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Magang')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }
    body { background: #f0f4ff; }

    .dash-bg {
        background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%);
        min-height: 100vh;
    }

    /* ── Profile Strip (same as institusi) ── */
    .profile-strip {
        background: linear-gradient(100deg, #1e3a8a 0%, #3b4fd8 50%, #4f46e5 100%);
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(20, 40, 120, 0.16);
    }
    .profile-strip::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .profile-strip::after {
        content: '';
        position: absolute;
        bottom: -80px; left: 30%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    /* ── Avatar ring (same as institusi) ── */
    .avatar-ring {
        background: linear-gradient(135deg, #60a5fa, #818cf8);
        padding: 3px;
        border-radius: 9999px;
        display: inline-flex;
        flex-shrink: 0;
    }
    .avatar-inner {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        border-radius: 9999px;
        width: 80px; height: 80px;
        display: flex; align-items: center; justify-content: center;
    }

    /* ── Stat tiles ── */
    .stat-tile {
        background: #fff;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(30,58,138,0.06), 0 4px 20px rgba(30,58,138,0.06);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .stat-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(59,79,216,0.16);
    }
    .stat-tile::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        background: var(--stat-color, #3b82f6);
    }
    .stat-tile .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0.5rem 0 0;
        font-family: 'DM Mono', monospace;
    }
    .stat-tile .stat-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }
    .stat-icon {
        width: 3rem; height: 3rem;
        border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        color: white;
    }

    /* ── Panel (same as institusi) ── */
    .panel {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(30,58,138,0.06), 0 4px 20px rgba(30,58,138,0.06);
    }

    /* ── Section label (same as institusi) ── */
    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 14px;
    }

    /* ── Status items (same as institusi) ── */
    .status-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-radius: 14px;
        background: #f8faff;
        border: 1px solid #e8eeff;
        transition: all 0.2s ease;
        cursor: default;
    }
    .status-item:hover {
        background: #eff2ff;
        border-color: #c7d2fe;
        transform: translateX(4px);
    }

    /* ── Progress bar (same as institusi) ── */
    .stat-bar-track {
        height: 6px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
    }
    .stat-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 1.2s cubic-bezier(.4,0,.2,1);
        width: 0;
    }

    /* ── Count pill (same as institusi) ── */
    .count-pill {
        font-family: 'DM Mono', monospace;
        font-size: 13px;
        font-weight: 500;
        padding: 3px 12px;
        border-radius: 999px;
        min-width: 42px;
        text-align: center;
    }

    /* ── Quick action btn (same as institusi) ── */
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
        transition: all 0.2s ease;
        text-decoration: none;
        width: 100%;
    }
    .action-btn:hover {
        background: #e8eeff;
        border-color: #a5b4fc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99,102,241,0.15);
        color: #3b4fd8;
        text-decoration: none;
    }

    /* ── Attendance table ── */
    .attendance-table { min-width: 700px; }
    .attendance-table th {
        padding: 10px 16px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #3730a3;
        background: #f0f4ff;
        text-align: center;
    }
    .attendance-table td {
        padding: 12px 16px;
        font-size: 13px;
        color: #334155;
        text-align: center;
        vertical-align: middle;
    }
    .attendance-table tbody tr { transition: background 0.15s; }
    .attendance-table tbody tr:hover { background: #f8faff !important; }

    /* ── Status badges ── */
    .att-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    /* ── Donut chart (same as institusi) ── */
    .donut-svg circle {
        transition: stroke-dashoffset 1.4s cubic-bezier(.4,0,.2,1);
    }

    /* ── Leaderboard items (using status-item pattern) ── */
    .lb-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        border-radius: 14px;
        background: #f0f4ff;
        border: 1px solid #e0e7ff;
        transition: all 0.2s ease;
    }
    .lb-item:hover {
        background: #e8eeff;
        border-color: #c7d2fe;
        transform: translateX(4px);
    }

    /* ── Divider ── */
    .divider { height: 1px; background: #f1f5f9; margin: 4px 0; }

    /* ── CTA button ── */
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
        transition: all 0.2s ease;
    }
    .cta-btn:hover {
        box-shadow: 0 6px 16px rgba(59,79,216,0.3);
        transform: translateY(-1px);
        color: #fff;
        text-decoration: none;
    }

    /* ── Animations (same as institusi) ── */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-1 { animation: fadeSlideUp 0.5s ease both; }
    .anim-2 { animation: fadeSlideUp 0.5s ease 0.1s both; }
    .anim-3 { animation: fadeSlideUp 0.5s ease 0.2s both; }
    .anim-4 { animation: fadeSlideUp 0.5s ease 0.3s both; }

    @media (max-width: 640px) {
        .avatar-inner { width: 60px; height: 60px; }
        .stat-tile .stat-value { font-size: 1.5rem; }
        .panel { padding: 16px; }
    }
</style>
@endpush

@section('content')
@php
    $totalKehadiran = $totalHadir + $totalIzin + $totalSakit + $totalAlfa ?: 1;
    $pctHadir  = $totalHadir  ? round(($totalHadir  / $totalKehadiran) * 100) : 0;
    $pctIzin   = $totalIzin   ? round(($totalIzin   / $totalKehadiran) * 100) : 0;
    $pctSakit  = $totalSakit  ? round(($totalSakit  / $totalKehadiran) * 100) : 0;
    $pctAlfa   = $totalAlfa   ? round(($totalAlfa   / $totalKehadiran) * 100) : 0;

    // Donut: r=42 → C = 2π×42 ≈ 263.9
    $circ = 263.9;
    $hadirDash = $pctHadir / 100 * $circ;
    $izinDash  = $pctIzin  / 100 * $circ;
    $sakitDash = $pctSakit / 100 * $circ;
    $alfaDash  = $pctAlfa  / 100 * $circ;

    $izinStart  = $hadirDash;
    $sakitStart = $hadirDash + $izinDash;
    $alfaStart  = $hadirDash + $izinDash + $sakitDash;
@endphp

<div class="dash-bg py-8">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- ── PROFILE HEADER (mirrors institusi profile-strip) ── --}}
    <div class="profile-strip anim-1">
        <div class="px-6 py-7 flex flex-col sm:flex-row items-center sm:items-start gap-5 relative z-10">

            {{-- Avatar --}}
            <div class="avatar-ring flex-shrink-0">
                <div class="avatar-inner">
                    <i class="fas fa-tachometer-alt text-2xl text-white"></i>
                </div>
            </div>

            {{-- Identity --}}
            <div class="flex-1 text-center sm:text-left">
                <h1 class="text-xl font-bold text-white mb-1">Dashboard Admin</h1>
                <p class="text-blue-200 font-semibold text-base">BBPSDMP Komdigi Makassar</p>
                <p class="text-blue-300 text-sm mt-1">
                    <i class="fas fa-calendar-day mr-1"></i>
                    {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}
                </p>
            </div>

            {{-- Summary right --}}
            <div class="flex-shrink-0 text-center sm:text-right">
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Magang Aktif</p>
                <p class="text-5xl font-extrabold text-white mono">{{ $activeInterns }}</p>
                <p class="text-blue-300 text-xs mt-1">dari {{ $totalInterns }} total peserta</p>
            </div>
        </div>
    </div>

    {{-- ── STAT TILES ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 anim-2">
        <div class="stat-tile" style="--stat-color:#3b82f6;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="stat-label">Magang Aktif</p>
                    <p class="stat-value">{{ $activeInterns }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="stat-tile" style="--stat-color:#22c55e;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="stat-label">Total Hadir</p>
                    <p class="stat-value">{{ $totalHadir }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="stat-tile" style="--stat-color:#f59e0b;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="stat-label">Total Izin</p>
                    <p class="stat-value">{{ $totalIzin }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                    <i class="fas fa-calendar-times"></i>
                </div>
            </div>
        </div>
        <div class="stat-tile" style="--stat-color:#ef5350;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="stat-label">Total Sakit</p>
                    <p class="stat-value">{{ $totalSakit }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#ef5350,#e53935);">
                    <i class="fas fa-calendar-minus"></i>
                </div>
            </div>
        </div>
        <div class="stat-tile" style="--stat-color:#6b7280;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="stat-label">Tidak Hadir</p>
                    <p class="stat-value">{{ $totalAlfa }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#6b7280,#4b5563);">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
        </div>
        <div class="stat-tile" style="--stat-color:#8b5cf6;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="stat-label">Mikro Skill</p>
                    <p class="stat-value">{{ $microTotal }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Absensi + Aksi Cepat ── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Absensi Hari Ini ── --}}
            <div class="panel anim-3">
                <p class="section-label">Absensi Hari Ini</p>
                <div class="overflow-x-auto">
                    <table class="attendance-table min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="rounded-tl-xl">Nama</th>
                                <th>Status</th>
                                <th>Check In</th>
                                <th>Foto In</th>
                                <th>Check Out</th>
                                <th class="rounded-tr-xl">Foto Out</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">

                            {{-- Hadir ── --}}
                            @php $hadirCount = 0; @endphp
                            @foreach($todayAttendances->where('status', 'hadir') as $att)
                                @php
                                    $hadirCount++;
                                    $photoInUrl  = $att->photo_path     ? route('admin.attendance.photo', ['filename' => basename($att->photo_path)])     : null;
                                    $photoOutUrl = $att->photo_checkout  ? route('admin.attendance.photo', ['filename' => basename($att->photo_checkout)]) : null;
                                @endphp
                                <tr>
                                    <td class="font-semibold text-gray-800">{{ $att->intern->name }}</td>
                                    <td>
                                        <span class="att-badge" style="background:#dcfce7;color:#15803d;">
                                            <i class="fas fa-check-circle text-xs"></i> Hadir
                                        </span>
                                    </td>
                                    <td class="mono">{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '—' }}</td>
                                    <td>
                                        @if($photoInUrl)
                                            <img src="{{ $photoInUrl }}" alt="In"
                                                class="w-10 h-10 object-cover rounded-lg border-2 border-green-200 cursor-pointer hover:border-green-400 mx-auto"
                                                onclick="window.open('{{ $photoInUrl }}','_blank')" title="Lihat foto">
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="mono">{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '—' }}</td>
                                    <td>
                                        @if($photoOutUrl)
                                            <img src="{{ $photoOutUrl }}" alt="Out"
                                                class="w-10 h-10 object-cover rounded-lg border-2 border-green-200 cursor-pointer hover:border-green-400 mx-auto"
                                                onclick="window.open('{{ $photoOutUrl }}','_blank')" title="Lihat foto">
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Izin / Sakit / Alfa ── --}}
                            @foreach($todayAttendances->whereIn('status', ['izin','sakit','alfa']) as $att)
                                @php
                                    $photoInUrl  = $att->photo_path     ? route('admin.attendance.photo', ['filename' => basename($att->photo_path)])     : null;
                                    $photoOutUrl = $att->photo_checkout  ? route('admin.attendance.photo', ['filename' => basename($att->photo_checkout)]) : null;
                                    $badgeStyle  = match($att->status) {
                                        'izin'  => 'background:#fef9c3;color:#a16207;',
                                        'sakit' => 'background:#ffedd5;color:#c2410c;',
                                        default => 'background:#fee2e2;color:#b91c1c;',
                                    };
                                    $badgeIcon = match($att->status) {
                                        'izin'  => 'fa-clipboard',
                                        'sakit' => 'fa-heartbeat',
                                        default => 'fa-times-circle',
                                    };
                                    $badgeText = match($att->status) {
                                        'izin'  => 'Izin',
                                        'sakit' => 'Sakit',
                                        default => 'Tidak Hadir',
                                    };
                                @endphp
                                <tr style="background:#fffdf7;">
                                    <td class="font-semibold text-gray-800">{{ $att->intern->name }}</td>
                                    <td>
                                        <span class="att-badge" style="{{ $badgeStyle }}">
                                            <i class="fas {{ $badgeIcon }} text-xs"></i> {{ $badgeText }}
                                        </span>
                                    </td>
                                    <td class="mono">{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '—' }}</td>
                                    <td>
                                        @if($photoInUrl)
                                            <img src="{{ $photoInUrl }}" alt="In"
                                                class="w-10 h-10 object-cover rounded-lg border-2 border-yellow-200 cursor-pointer hover:border-yellow-400 mx-auto"
                                                onclick="window.open('{{ $photoInUrl }}','_blank')">
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="mono">{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '—' }}</td>
                                    <td>
                                        @if($photoOutUrl)
                                            <img src="{{ $photoOutUrl }}" alt="Out"
                                                class="w-10 h-10 object-cover rounded-lg border-2 border-yellow-200 cursor-pointer hover:border-yellow-400 mx-auto"
                                                onclick="window.open('{{ $photoOutUrl }}','_blank')">
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Belum Absen ── --}}
                            @forelse($todayAbsentInterns as $absentIntern)
                                <tr style="background:#fff8f8;">
                                    <td class="font-semibold text-gray-800">{{ $absentIntern->name }}</td>
                                    <td>
                                        <span class="att-badge" style="background:#fee2e2;color:#b91c1c;">
                                            <i class="fas fa-exclamation-circle text-xs"></i> Belum Absen
                                        </span>
                                    </td>
                                    <td colspan="4" class="text-gray-400 italic text-xs">Belum melakukan absensi hari ini</td>
                                </tr>
                            @empty
                                @if($hadirCount == 0 && $todayAttendances->isEmpty())
                                    <tr>
                                        <td colspan="6" class="py-10 text-center">
                                            <i class="fas fa-inbox text-4xl text-gray-200 mb-3 block"></i>
                                            <p class="text-sm text-gray-400">Belum ada absensi hari ini.</p>
                                        </td>
                                    </tr>
                                @endif
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Aksi Cepat (same pattern as institusi) ── --}}
            <div class="panel anim-4">
                <p class="section-label">Aksi Cepat</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('admin.attendance.index') }}" class="action-btn">
                        <i class="fas fa-clipboard-check"></i>
                        Rekap Absensi
                    </a>
                    <a href="{{ route('admin.intern.index') }}" class="action-btn">
                        <i class="fas fa-users"></i>
                        Data Peserta Magang
                    </a>
                    <a href="{{ route('admin.pengajuan.index') }}" class="action-btn">
                        <i class="fas fa-file-alt"></i>
                        Pengajuan Masuk
                    </a>
                    <a href="{{ route('admin.microskill.leaderboard') }}" class="action-btn">
                        <i class="fas fa-trophy"></i>
                        Leaderboard Mikro Skill
                        @if($microTotal > 0)
                            <span style="margin-left:auto;background:#ede9fe;color:#6d28d9;border-radius:999px;padding:1px 8px;font-size:11px;font-weight:700;">
                                {{ $microTotal }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

        </div>

        {{-- RIGHT: Donut + Leaderboard ── --}}
        <div class="space-y-5">

            {{-- Donut Distribusi Kehadiran (same structure as institusi donut) ── --}}
            <div class="panel anim-2 flex flex-col items-center">
                <p class="section-label w-full">Distribusi Kehadiran</p>

                <div class="relative" style="width:160px;height:160px;">
                    <svg width="160" height="160" viewBox="0 0 100 100" class="donut-svg" style="transform:rotate(-90deg);">
                        {{-- Track --}}
                        <circle cx="50" cy="50" r="42" fill="none" stroke="#e2e8f0" stroke-width="12"/>
                        {{-- Hadir --}}
                        <circle id="donut-hadir" cx="50" cy="50" r="42" fill="none"
                            stroke="#22c55e" stroke-width="12"
                            stroke-dasharray="{{ $circ }}"
                            stroke-dashoffset="{{ $circ }}"
                            stroke-linecap="butt"
                            style="transition:stroke-dashoffset 1.2s ease;"
                            data-dash="{{ $hadirDash }}"
                            data-offset="0"/>
                        {{-- Izin --}}
                        <circle id="donut-izin" cx="50" cy="50" r="42" fill="none"
                            stroke="#f59e0b" stroke-width="12"
                            stroke-dasharray="{{ $circ }}"
                            stroke-dashoffset="{{ $circ }}"
                            stroke-linecap="butt"
                            style="transition:stroke-dashoffset 1.2s ease 0.2s;"
                            data-dash="{{ $izinDash }}"
                            data-start="{{ $izinStart }}"/>
                        {{-- Sakit --}}
                        <circle id="donut-sakit" cx="50" cy="50" r="42" fill="none"
                            stroke="#ef5350" stroke-width="12"
                            stroke-dasharray="{{ $circ }}"
                            stroke-dashoffset="{{ $circ }}"
                            stroke-linecap="butt"
                            style="transition:stroke-dashoffset 1.2s ease 0.4s;"
                            data-dash="{{ $sakitDash }}"
                            data-start="{{ $sakitStart }}"/>
                        {{-- Alfa --}}
                        <circle id="donut-alfa" cx="50" cy="50" r="42" fill="none"
                            stroke="#6b7280" stroke-width="12"
                            stroke-dasharray="{{ $circ }}"
                            stroke-dashoffset="{{ $circ }}"
                            stroke-linecap="butt"
                            style="transition:stroke-dashoffset 1.2s ease 0.6s;"
                            data-dash="{{ $alfaDash }}"
                            data-start="{{ $alfaStart }}"/>
                    </svg>
                    {{-- Center label --}}
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <span class="mono" style="font-size:26px;font-weight:800;color:#1e3a8a;">{{ $totalHadir }}</span>
                        <span style="font-size:10px;color:#94a3b8;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;">Hadir</span>
                    </div>
                </div>

                {{-- Legend ── --}}
                <div class="mt-5 space-y-2 w-full">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span style="width:10px;height:10px;background:#22c55e;border-radius:3px;display:inline-block;"></span>
                            <span class="text-gray-600 font-medium">Hadir</span>
                        </div>
                        <span class="mono text-gray-800 font-semibold">{{ $pctHadir }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span style="width:10px;height:10px;background:#f59e0b;border-radius:3px;display:inline-block;"></span>
                            <span class="text-gray-600 font-medium">Izin</span>
                        </div>
                        <span class="mono text-gray-800 font-semibold">{{ $pctIzin }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span style="width:10px;height:10px;background:#ef5350;border-radius:3px;display:inline-block;"></span>
                            <span class="text-gray-600 font-medium">Sakit</span>
                        </div>
                        <span class="mono text-gray-800 font-semibold">{{ $pctSakit }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span style="width:10px;height:10px;background:#6b7280;border-radius:3px;display:inline-block;"></span>
                            <span class="text-gray-600 font-medium">Tidak Hadir</span>
                        </div>
                        <span class="mono text-gray-800 font-semibold">{{ $pctAlfa }}%</span>
                    </div>
                </div>
            </div>

            {{-- Leaderboard Mikro Skill (using status-item pattern) ── --}}
            <div class="panel anim-3">
                <p class="section-label">Leaderboard Mikro Skill</p>

                @if($topMicroSkills->count())
                    <div class="space-y-2">
                        @foreach($topMicroSkills->take(3) as $index => $row)
                            @php
                                $rankStyle = match($index) {
                                    0 => 'background:linear-gradient(135deg,#f59e0b,#d97706);',
                                    1 => 'background:linear-gradient(135deg,#94a3b8,#64748b);',
                                    2 => 'background:linear-gradient(135deg,#f97316,#ea580c);',
                                    default => 'background:linear-gradient(135deg,#3b82f6,#6366f1);',
                                };
                            @endphp
                            <div class="lb-item">
                                <div class="flex items-center gap-3 min-w-0">
                                    {{-- Rank badge ── --}}
                                    <div class="relative flex-shrink-0">
                                        <span style="width:34px;height:34px;border-radius:50%;{{ $rankStyle }}color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">
                                            {{ $index + 1 }}
                                        </span>
                                        @if($index < 3)
                                            <i class="fas fa-crown" style="position:absolute;top:-8px;right:-4px;font-size:10px;color:#f59e0b;"></i>
                                        @endif
                                    </div>

                                    {{-- Avatar ── --}}
                                    @if(!empty($row['photo_path']))
                                        <img src="{{ url('storage/'.$row['photo_path']) }}"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-white shadow flex-shrink-0">
                                    @else
                                        <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#818cf8);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                    @endif

                                    {{-- Info ── --}}
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $row['name'] }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $row['institution'] }}</p>
                                    </div>
                                </div>

                                <span class="count-pill flex-shrink-0" style="background:#e0e7ff;color:#3730a3;">
                                    {{ $row['total'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                        <i class="fas fa-chart-line text-4xl mb-3 text-gray-200"></i>
                        <p class="text-sm">Belum ada data mikro skill.</p>
                    </div>
                @endif

                <div class="mt-4 text-center">
                    <a href="{{ route('admin.microskill.leaderboard') }}" class="cta-btn">
                        Lihat Selengkapnya
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const circ = {{ $circ }};

    // Donut animation (same logic as institusi)
    setTimeout(() => {
        const segments = [
            { id: 'donut-hadir' },
            { id: 'donut-izin'  },
            { id: 'donut-sakit' },
            { id: 'donut-alfa'  },
        ];

        segments.forEach(({ id }) => {
            const el = document.getElementById(id);
            if (!el) return;
            const dash  = parseFloat(el.dataset.dash  || 0);
            const start = parseFloat(el.dataset.start || 0);
            el.style.strokeDasharray  = `${dash} ${circ - dash}`;
            el.style.strokeDashoffset = -start;
        });
    }, 400);
});
</script>
@endpush
@endsection