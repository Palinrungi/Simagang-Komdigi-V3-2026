@extends('layouts.app')

@section('title', 'Monitoring Industri - Sistem Magang')

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
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: #1f2937;
            background: #fff;
        }

        .input-main:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .btn-filter {
            background: linear-gradient(to right, #2563eb, #4338ca);
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 0.5rem 1.25rem;
            border-radius: 0.6rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .15s ease;
            white-space: nowrap;
        }

        .btn-filter:hover {
            box-shadow: 0 4px 14px rgba(59, 79, 216, 0.3);
            transform: translateY(-1px);
        }

        .btn-reset {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
            font-weight: 700;
            font-size: 13px;
            padding: 0.5rem 0.75rem;
            border-radius: 0.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .15s ease;
        }

        .btn-reset:hover {
            background: #dbeafe;
            color: #1d4ed8;
            text-decoration: none;
        }

        .panel-header-blue {
            background: linear-gradient(to right, #2563eb, #4f46e5);
            padding: 1rem 1.5rem;
        }

        .data-table {
            min-width: 700px;
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead tr {
            background: #eff6ff;
        }

        .data-table th {
            padding: 0.75rem 1rem;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #1e3a8a;
            text-align: left;
            white-space: nowrap;
        }

        .data-table th:first-child { border-radius: 10px 0 0 10px; }
        .data-table th:last-child  { border-radius: 0 10px 10px 0; text-align: center; }

        .data-table td {
            padding: 0.85rem 1rem;
            font-size: 13px;
            color: #334155;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:hover { background: #eff6ff; }
        .data-table tbody tr:last-child td { border-bottom: none; }

        .icon-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f8faff;
            border: 1px solid #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }

        .logo-box-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #2563eb;
            background: #dbeafe;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .pill-blue   { background: #dbeafe; color: #1e40af; }
        .pill-green  { background: #dcfce7; color: #15803d; }
        .pill-orange { background: #ffedd5; color: #c2410c; }

        .count-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e40af;
        }

        .action-group { display: flex; align-items: center; justify-content: center; gap: 6px; }

        .action-btn-sm {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: all .15s ease;
            text-decoration: none;
        }

        .action-btn-sm:hover { transform: translateY(-1px); text-decoration: none; }
        .action-view       { background: #dcfce7; color: #15803d; }
        .action-view:hover { background: #bbf7d0; }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 10px; color: #e2e8f0; }
        .empty-state p { font-size: 13px; margin: 0; }

        @media (max-width: 768px) {
            .hero-title { font-size: 1.5rem; }
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- ── HERO STRIP ── --}}
            <div class="hero-strip mb-6">
                <div class="relative z-10 px-6 py-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="hero-title text-4xl font-bold mb-1">Monitoring Industri</h1>
                        <p class="text-blue-100">Ringkasan data peserta magang per industri mitra</p>
                    </div>
                </div>
            </div>

            {{-- ── FILTER PANEL ── --}}
            <div class="panel mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-filter mr-3"></i>Filter Data
                    </h2>
                </div>
                <form method="GET" action="{{ route('admin.monitoring.industri.index') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Cari Nama Industri</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="input-main"
                            placeholder="Ketik nama industri..."
                        />
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 btn-filter">
                            <i class="fas fa-filter"></i>Filter
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.monitoring.industri.index') }}" class="btn-reset">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ── TABEL INDUSTRI ── --}}
            <div class="panel overflow-hidden">
                <div class="panel-header-blue flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-building mr-3"></i>Daftar Industri Mitra
                    </h2>
                    <span class="count-badge">{{ $totalIndustri }} industri</span>
                </div>
                <div class="p-6">
                    <div class="table-responsive" style="overflow-x:auto;overflow-y:auto;max-height:600px;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="text-align:center" >Nama Industri</th>
                                    <th style="text-align:center">Alamat</th>
                                    <th style="text-align:center;">Total</th>
                                    <th style="text-align:center;">Aktif</th>
                                    <th style="text-align:center;">Alumni</th>
                                    <th style="text-align:center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($industris as $industri)
                                    <tr>
                                        <td>
                                            <div class="icon-cell">
                                                <div class="logo-box">
                                                    @if($industri->logo_industri)
                                                        <img
                                                            src="{{ asset('storage/' . $industri->logo_industri) }}"
                                                            alt="{{ $industri->nama_industri }}"
                                                            onerror="this.parentElement.innerHTML='<div class=\'logo-box-fallback\'><i class=\'fas fa-building\'></i></div>'">
                                                    @else
                                                        <div class="logo-box-fallback">
                                                            <i class="fas fa-building"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="font-semibold text-gray-900 text-sm">
                                                    {{ $industri->nama_industri }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-gray-500"
                                            style="max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $industri->alamat_industri ?? '—' }}
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="pill pill-blue">{{ $industri->total_intern }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="pill pill-green">{{ $industri->active_intern }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="pill pill-orange">{{ $industri->alumni_intern }}</span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('admin.monitoring.industri.show', $industri) }}"
                                                    class="action-btn-sm action-view"
                                                    title="Lihat detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="fas fa-building"></i>
                                                <p>Tidak ada data industri ditemukan.</p>
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