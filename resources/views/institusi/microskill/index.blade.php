@extends('layouts.app')

@section('title', 'Mikro Skill Peserta Magang - Sistem Magang')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

        *,
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .mono {
            font-family: 'DM Mono', monospace;
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

        .section-card {
            background: #f8faff;
            border: 1px solid #e8eeff;
            border-radius: 18px;
        }

        .field-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e3a8a;
        }

        .field-select {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #dbe3f0;
            background: #fff;
            padding: 0.9rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field-select:focus {
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
                        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold leading-tight">Mikro Skill Peserta Magang</h1>
                        <p class="mt-3 text-sm sm:text-base text-blue-100/90">Pantau pencapaian dan pengembangan keterampilan
                            peserta magang.</p>
                    </div>

                    <div
                        class="bg-white/10 border border-white/10 rounded-2xl px-4 py-4 text-white shadow-sm backdrop-blur-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-blue-100/80">Data</p>
                                <p class="text-base font-bold">{{ $submissions->total() }} Submission</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="stat-tile tile-blue">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Submissions</p>
                            <h3 class="mt-2 text-3xl font-extrabold text-slate-900">{{ $submissions->total() }}</h3>
                        </div>
                        <div class="tile-icon bg-gradient-to-br from-blue-500 to-indigo-600">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500">Seluruh submission mikro skill.</p>
                </div>

                <div class="stat-tile tile-indigo">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Unique Interns</p>
                            <h3 class="mt-2 text-3xl font-extrabold text-slate-900">
                                {{ $submissions->pluck('intern_id')->unique()->count() }}</h3>
                        </div>
                        <div class="tile-icon bg-gradient-to-br from-indigo-500 to-purple-600">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500">Jumlah peserta magang.</p>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-filter text-blue-200 text-base"></i>
                    <h2>Filter Data</h2>
                </div>
                <div class="panel-body">
                    <form method="GET" action="{{ route('institusi.microskill.index') }}">
                        <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                            <div class="flex-1">
                                <label class="field-label">Peserta Magang</label>
                                <select name="intern_id" class="field-select">
                                    <option value="">-- Semua Peserta Magang --</option>
                                    @foreach ($interns as $intern)
                                        <option value="{{ $intern->id }}" @selected(request('intern_id') == $intern->id)>
                                            {{ $intern->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl">
                                    <i class="fas fa-search mr-2"></i>Cari
                                </button>
                                @if (request()->filled('intern_id'))
                                    <a href="{{ route('institusi.microskill.index') }}"
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
                    <h2>Data Mikro Skill</h2>
                </div>
                <div class="panel-body">
                    <div class="table-wrap">
                        <table class="w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-600 to-indigo-600">
                                    <th
                                        class="px-3 sm:px-6 py-3 sm:py-5 text-left text-xs font-bold text-white uppercase tracking-widest">
                                        Nama</th>
                                    <th
                                        class="px-3 sm:px-6 py-3 sm:py-5 text-left text-xs font-bold text-white uppercase tracking-widest">
                                        Judul Course</th>
                                    <th
                                        class="px-3 sm:px-6 py-3 sm:py-5 text-left text-xs font-bold text-white uppercase tracking-widest">
                                        Waktu Submit</th>
                                    <th
                                        class="px-3 sm:px-6 py-3 sm:py-5 text-left text-xs font-bold text-white uppercase tracking-widest">
                                        Dokumentasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($submissions as $s)
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                                        <td class="px-3 sm:px-6 py-3 sm:py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                @if ($s->intern->photo_path)
                                                    <img src="{{ url('storage/' . $s->intern->photo_path) }}"
                                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover object-center border-2 border-blue-300 shadow-md ring-2 ring-blue-100 flex-shrink-0 aspect-square"
                                                        alt="{{ $s->intern->name }}" />
                                                @else
                                                    <div
                                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-md ring-2 ring-blue-100 flex-shrink-0 aspect-square">
                                                        <i class="fas fa-user text-white text-sm sm:text-base"></i>
                                                    </div>
                                                @endif
                                                <span
                                                    class="text-xs sm:text-sm font-semibold text-slate-900 truncate">{{ $s->intern->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-5">
                                            <span class="text-xs sm:text-sm font-medium text-slate-700">
                                                {{ Str::limit($s->title, 40) }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                                                <span class="font-semibold text-slate-700">
                                                    {{ $s->submitted_at ? \Carbon\Carbon::parse($s->submitted_at)->format('d/m/y') : '-' }}
                                                </span>
                                                <span class="text-slate-500 text-xs hidden sm:inline">
                                                    {{ $s->submitted_at ? \Carbon\Carbon::parse($s->submitted_at)->format('H:i') : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-5 whitespace-nowrap">
                                            @if ($s->photo_path)
                                                @can('view', $s)
                                                    <img src="{{ route('institusi.microskill.photo', basename($s->photo_path)) }}"
                                                        alt="Documentation"
                                                        class="w-10 h-10 sm:w-12 sm:h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all shadow-sm hover:shadow-lg transform hover:scale-110 aspect-square"
                                                        onclick="window.open('{{ route('institusi.microskill.photo', basename($s->photo_path)) }}', '_blank')"
                                                        title="Klik untuk melihat full size" />
                                                @else
                                                    <div
                                                        class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-slate-100 rounded-lg border-2 border-slate-200">
                                                        <span class="text-xs text-slate-500">Tidak ada akses</span>
                                                    </div>
                                                @endcan
                                            @else
                                                <div
                                                    class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-slate-100 rounded-lg border-2 border-slate-200">
                                                    <i class="fas fa-image text-slate-300 text-sm sm:text-lg"></i>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 sm:px-6 py-8 sm:py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-3 sm:mb-5 shadow-md">
                                                    <i class="fas fa-certificate text-3xl sm:text-5xl text-gray-300"></i>
                                                </div>
                                                <p class="text-base sm:text-lg font-bold text-gray-700 mb-1 sm:mb-2">Tidak
                                                    ada data mikro skill</p>
                                                <p class="text-xs sm:text-sm text-gray-500">Data akan muncul ketika peserta
                                                    magang telah mengerjakan mikro skill</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $submissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
