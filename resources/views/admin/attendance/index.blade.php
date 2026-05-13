@extends('layouts.app')

@section('title', 'Monitoring Absensi - Sistem Magang')

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

        /* ── Tabel scroll container ── */
        .table-scroll {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 520px;
        }

        /* Sticky header agar tetap terlihat saat scroll */
        .table-scroll thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #eff6ff;
        }

        .table-min-w {
            min-width: 1100px;
            width: 100%;
            border-collapse: collapse;
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

            {{-- ── HERO STRIP ── --}}
            <div class="hero-strip mb-6">
                <div class="relative z-10 px-6 py-7">
                    <h1 class="hero-title text-4xl font-bold mb-1">Monitoring Absensi</h1>
                    <p class="text-blue-100">Pantau kehadiran dan aktivitas check in/out peserta magang</p>
                </div>
            </div>

            {{-- ── FILTER FORM ── --}}
            <div class="panel mb-6">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-blue-900 flex items-center">
                        <i class="fas fa-filter mr-2 text-blue-600"></i>Filter & Pencarian
                    </h2>
                </div>
                <form method="GET" action="{{ route('admin.attendance.index') }}"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4 p-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Peserta Magang</label>
                        <select name="intern_id" id="intern_id" class="input-main">
                            <option value="">Semua</option>
                            @foreach ($interns as $intern)
                                <option value="{{ $intern->id }}"
                                    {{ request('intern_id') == $intern->id ? 'selected' : '' }}>
                                    {{ $intern->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select name="status" id="status" class="input-main">
                            <option value="">Semua</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin"  {{ request('status') == 'izin'  ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alfa"  {{ request('status') == 'alfa'  ? 'selected' : '' }}>Tidak Hadir</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-main">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Hingga Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-main">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="action-mobile flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if (request()->query())
                            <a href="{{ route('admin.attendance.index') }}"
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ── TABEL ABSENSI ── --}}
            <div class="panel overflow-hidden">

                {{-- Header panel --}}
                <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-calendar-check mr-3"></i>Data Absensi
                    </h2>
                </div>

                {{-- Scroll wrapper — HANYA tabel yang scroll, bukan pagination --}}
                <div class="table-scroll">
                    <table class="table-min-w divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-blue-50">
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Nama</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Check In</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Foto Check In</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Check Out</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Foto Check Out</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Status Dokumen</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">

                            {{-- Baris peserta yang belum absen hari ini --}}
                            @foreach ($todayAbsentInterns as $absentIntern)
                                <tr class="bg-red-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($todayWita)->format('d/m/y') }}
                                            <span class="text-xs text-gray-400">(Hari ini)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $absentIntern->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Tidak Hadir
                                        </span>
                                    </td>
                                    <td colspan="6" class="px-6 py-4 text-sm text-gray-400 italic text-center">
                                        Belum melakukan absensi hari ini
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Baris data absensi --}}
                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900">
                                            {{ $attendance->date->format('d/m/y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900">{{ $attendance->intern->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
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
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-600">
                                            {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($attendance->photo_path)
                                            @php $photoUrl = route('admin.attendance.photo', ['filename' => basename($attendance->photo_path)]); @endphp
                                            @can('view', $attendance)
                                                <img src="{{ $photoUrl }}" alt="Check In"
                                                    class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all"
                                                    onclick="window.open('{{ $photoUrl }}','_blank')"
                                                    title="Klik untuk melihat full size">
                                            @else
                                                <span class="text-gray-400 text-xs">Tidak ada akses</span>
                                            @endcan
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-600">
                                            {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($attendance->photo_checkout)
                                            @php $photoUrl = route('admin.attendance.photo', ['filename' => basename($attendance->photo_checkout)]); @endphp
                                            @can('view', $attendance)
                                                <img src="{{ $photoUrl }}" alt="Check Out"
                                                    class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all"
                                                    onclick="window.open('{{ $photoUrl }}','_blank')"
                                                    title="Klik untuk melihat full size">
                                            @else
                                                <span class="text-gray-400 text-xs">Tidak ada akses</span>
                                            @endcan
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($attendance->document_status)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if ($attendance->document_status == 'approved') bg-green-100 text-green-800
                                                @elseif($attendance->document_status == 'rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($attendance->document_status) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.attendance.show', $attendance) }}"
                                            class="text-blue-600 hover:text-blue-900 inline-block">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-sm">Belum ada data absensi.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>{{-- end .table-scroll --}}

                @if ($attendances->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $attendances->links() }}
                    </div>
                @endif

            </div>{{-- end .panel --}}

        </div>
    </div>
@endsection