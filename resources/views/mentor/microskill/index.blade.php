@extends('layouts.app')

@section('title', 'Mikro Skill Anak Bimbingan - Sistem Magang')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    *, body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }

    /* ── Page background ── */
    .dash-bg {
        min-height: 100vh;
        background: #f1f5ff;
    }

    /* ── Header strip ── */
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
        top: -80px; right: -60px;
        width: 260px; height: 260px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-strip::after {
        content: '';
        position: absolute;
        bottom: -100px; left: 25%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
        pointer-events: none;
    }

    /* ── Stat tiles ── */
    .stat-tile {
        background: #fff;
        border-radius: 18px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(20,40,120,0.06), 0 4px 16px rgba(20,40,120,0.06);
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
        bottom: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 0 0 18px 18px;
    }
    .stat-tile:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(20,40,120,0.12); }
    .tile-blue::after   { background: linear-gradient(90deg,#3b82f6,#6366f1); }
    .tile-indigo::after { background: linear-gradient(90deg,#6366f1,#8b5cf6); }
    .tile-purple::after { background: linear-gradient(90deg,#8b5cf6,#a855f7); }

    .tile-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .tile-value {
        font-size: 28px;
        font-weight: 800;
        color: #1f2937;
        font-family: 'DM Mono', monospace;
    }

    /* ── Panel ── */
    .panel {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(20,40,120,0.06), 0 4px 18px rgba(20,40,120,0.06);
        overflow: hidden;
        margin-bottom: 24px;
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
    .panel-body { padding: 20px 22px; }

    /* ── Filter Form ── */
    .filter-wrapper {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 200px;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .filter-select {
        padding: 10px 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 13px;
        font-family: inherit;
        background: #fff;
        cursor: pointer;
        transition: all .2s ease;
    }
    .filter-select:focus {
        outline: none;
        border-color: #3b4fd8;
        box-shadow: 0 0 0 3px rgba(59,79,216,0.1);
    }

    .btn-filter {
        padding: 10px 16px;
        background: linear-gradient(135deg, #3b4fd8, #3b82f6);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59,79,216,0.3);
    }

    .btn-clear {
        padding: 10px 14px;
        background: #e5e7eb;
        color: #374151;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
    }
    .btn-clear:hover {
        background: #d1d5db;
    }

    /* ── Table ── */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        background: #f0f4ff;
    }
    thead th {
        padding: 12px 14px;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        color: #3b4fd8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: 2px solid #e5e7eb;
    }
    tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #e8eeff;
        font-size: 13px;
        color: #374151;
    }
    tbody tr {
        transition: background .15s ease;
    }
    tbody tr:hover {
        background: #f9fafb;
    }

    /* ── Avatar ── */
    .avatar-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    /* ── Title text ── */
    .title-text {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #374151;
        font-weight: 600;
        font-size: 13px;
    }

    /* ── Date display ── */
    .date-cell {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #6b7280;
        font-size: 12px;
    }
    .date-cell .time {
        color: #9ca3af;
        font-size: 11px;
        font-family: 'DM Mono', monospace;
    }

    /* ── Photo preview ── */
    .photo-thumbnail {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #dbeafe;
        cursor: pointer;
        transition: all .2s ease;
        flex-shrink: 0;
    }
    .photo-thumbnail:hover {
        border-color: #3b82f6;
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(59,79,216,0.3);
    }

    .photo-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f3f4f6;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
        font-size: 16px;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #9ca3af;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    .empty-state p {
        font-size: 13px;
        margin: 4px 0;
    }

    /* ── Animations ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .a1 { animation: fadeUp .5s ease both; }
    .a2 { animation: fadeUp .5s .08s ease both; }
    .a3 { animation: fadeUp .5s .16s ease both; }
</style>
@endpush

@section('content')
<div class="dash-bg py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ── HERO HEADER ──────────────────────────────── --}}
        <div class="hero-strip shadow-xl a1">
            <div class="relative z-10 px-6 py-8">
                <h1 class="text-2xl font-bold text-white mb-1">Mikro Skill Anak Bimbingan</h1>
                <p class="text-blue-200 text-sm">Pantau pencapaian dan pengembangan keterampilan anak magang Anda</p>
            </div>
        </div>

        {{-- ── STAT TILES ────────────────────────────────── --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;" class="a2">
            <div class="stat-tile tile-blue">
                <p class="tile-label">Total Submissions</p>
                <p class="tile-value">{{ $submissions->total() }}</p>
            </div>

            <div class="stat-tile tile-indigo">
                <p class="tile-label">Unique Interns</p>
                <p class="tile-value">{{ $submissions->pluck('intern_id')->unique()->count() }}</p>
            </div>

            <div class="stat-tile tile-purple">
                <p class="tile-label">Current Page</p>
                <p class="tile-value">{{ $submissions->currentPage() }}<span style="font-size: 14px; color: #9ca3af;">/ {{ $submissions->lastPage() }}</span></p>
            </div>
        </div>

        {{-- ── FILTER PANEL ────────────────────────────── --}}
        <div class="panel a2">
            <div class="panel-header">
                <i class="fas fa-sliders-h text-blue-200"></i>
                <h2>Filter Data</h2>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('mentor.microskill.index') }}">
                    <div class="filter-wrapper">
                        <div class="filter-group">
                            <label class="filter-label">Anak Magang</label>
                            <select name="intern_id" class="filter-select">
                                <option value="">Semua Anak Magang</option>
                                @foreach ($interns as $intern)
                                    <option value="{{ $intern->id }}" @selected(request('intern_id') == $intern->id)>
                                        {{ $intern->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display: flex; gap: 8px;">
                            <button type="submit" class="btn-filter">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            @if (request()->filled('intern_id'))
                                <a href="{{ route('mentor.microskill.index') }}" class="btn-clear">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── MICROSKILL TABLE PANEL ──────────────────– --}}
        <div class="panel a3">
            <div class="panel-header">
                <i class="fas fa-star text-blue-200"></i>
                <h2>Data Mikro Skill</h2>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th style="min-width: 150px;">Nama</th>
                                <th style="min-width: 220px;">Judul Course</th>
                                <th style="min-width: 140px;">Waktu Submit</th>
                                <th style="min-width: 50px;">Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $s)
                                <tr>
                                    <td>
                                        <div class="avatar-cell">
                                            @if ($s->intern->photo_path)
                                                <img src="{{ url('storage/' . $s->intern->photo_path) }}"
                                                    alt="{{ $s->intern->name }}" class="avatar">
                                            @else
                                                <div class="avatar" style="background: linear-gradient(135deg,#3b82f6,#6366f1); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:12px;">
                                                    {{ strtoupper(substr($s->intern->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="title-text" style="max-width: none;">{{ $s->intern->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="title-text">{{ Str::limit($s->title, 50) }}</span>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            @if($s->submitted_at)
                                                <span>{{ \Carbon\Carbon::parse($s->submitted_at)->translatedFormat('d M Y') }}</span>
                                                <span class="time hidden sm:inline">{{ \Carbon\Carbon::parse($s->submitted_at)->format('H:i') }}</span>
                                            @else
                                                <span>—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($s->photo_path)
                                            <img src="{{ url('storage/' . $s->photo_path) }}" alt="Documentation"
                                                class="photo-thumbnail"
                                                onclick="window.open('{{ url('storage/' . $s->photo_path) }}', '_blank')"
                                                title="Klik untuk melihat full size">
                                        @else
                                            <div class="photo-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="fas fa-certificate"></i>
                                            <p class="font-semibold">Tidak ada data mikro skill</p>
                                            <p style="font-size:12px;">Data akan muncul ketika anak magang mensubmit course</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($submissions, 'links'))
                    <div style="margin-top: 20px;">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
