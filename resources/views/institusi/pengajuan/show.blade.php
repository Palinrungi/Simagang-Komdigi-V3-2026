@extends('layouts.app')

@section('title', 'Detail Pengajuan Magang')

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

        .info-card {
            background: #fff;
            border: 1px solid #e8eeff;
            border-radius: 18px;
            box-shadow: 0 1px 3px rgba(20, 40, 120, 0.05);
        }

        .soft-badge {
            background: linear-gradient(135deg, #eff6ff, #f5f3ff);
            border: 1px solid #dbeafe;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.02em;
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
                        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold leading-tight">Detail Pengajuan Magang</h1>
                        <p class="mt-3 text-sm sm:text-base text-blue-100/90">Informasi pengajuan dan daftar calon anak
                            magang dalam tampilan yang selaras dengan dashboard.</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('institusi.pengajuan.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm border border-white/10 transition hover:bg-white/15">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>

                        <a href="#"
                            class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-blue-700 shadow-lg transition hover:-translate-y-0.5 hover:bg-blue-50">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-file-lines text-blue-200 text-base"></i>
                    <h2>Detail Pengajuan</h2>
                </div>

                <div class="panel-body space-y-6">
                    <div class="section-card p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                                    <i class="fas fa-briefcase text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Informasi Surat</p>
                                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Informasi Surat Pengajuan
                                        Magang</h2>
                                </div>
                            </div>

                            <a href="{{ route('pengajuan.surat', $pengajuan) }}" target="_blank"
                                class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                <i class="fas fa-download mr-2"></i>
                                Download Surat Pengajuan
                            </a>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="info-card p-4 sm:p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Nomor Surat Pengajuan</p>
                                <p class="mt-1 text-sm sm:text-base font-semibold text-slate-900">{{ $pengajuan->no_surat }}
                                </p>
                            </div>
                            <div class="info-card p-4 sm:p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Penanggung Jawab</p>
                                <p class="mt-1 text-sm sm:text-base font-semibold text-slate-900">
                                    {{ $pengajuan->tujuan_surat }}</p>
                            </div>
                            <div class="info-card p-4 sm:p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Tanggal Pengajuan</p>
                                <p class="mt-1 text-sm sm:text-base font-semibold text-slate-900">
                                    {{ $pengajuan->created_at->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="section-card p-5 sm:p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                                <i class="fas fa-clipboard-list text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Pengajuan</p>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Informasi Pengajuan Magang</h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="info-card p-4 sm:p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Keperluan</p>
                                <p class="mt-1 text-sm sm:text-base font-semibold text-slate-900">
                                    {{ $pengajuan->keperluan }}</p>
                            </div>
                            <div class="info-card p-4 sm:p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Tanggal Masuk</p>
                                <p class="mt-1 text-sm sm:text-base font-semibold text-slate-900">
                                    {{ $pengajuan->start_date }}</p>
                            </div>
                            <div class="info-card p-4 sm:p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-400">Tanggal Keluar</p>
                                <p class="mt-1 text-sm sm:text-base font-semibold text-slate-900">{{ $pengajuan->end_date }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="section-card p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                                    <i class="fas fa-circle-info text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Status</p>
                                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Status Pengajuan Magang</h2>
                                </div>
                            </div>

                            @if ($pengajuan->status == 'approved')
                                <a href="{{ route('institusi.pengajuan.surat-balasan', $pengajuan) }}" target="_blank"
                                    class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
                                    <i class="fas fa-download mr-2"></i>
                                    Download Surat Balasan
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="info-card p-4 sm:p-5">
                                <p class="text-xs uppercase tracking-wide text-slate-400 mb-2">Status</p>
                                @php
                                    $statusClass = match ($pengajuan->status) {
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'revised' => 'bg-orange-100 text-orange-800',
                                        default => 'bg-yellow-100 text-yellow-800',
                                    };
                                @endphp
                                <span class="status-pill {{ $statusClass }}">{{ ucfirst($pengajuan->status) }}</span>

                                @if ($pengajuan->status == 'revised' && $pengajuan->admin_note)
                                    <div class="mt-4 rounded-2xl border border-orange-100 bg-orange-50 p-4">
                                        <h4 class="text-sm font-semibold text-orange-800 mb-2">Catatan Revisi</h4>
                                        <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $pengajuan->admin_note }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="section-card p-5 sm:p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Peserta</p>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900">Daftar Calon Peserta Magang</h2>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach ($pengajuan->details as $i => $peserta)
                                <div class="info-card p-5 sm:p-6">
                                    <div class="flex items-center justify-between gap-4 mb-4">
                                        <h3 class="text-md font-semibold text-blue-700">Calon Peserta Magang
                                            {{ $i + 1 }}</h3>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-slate-400">Nama</p>
                                            <p class="mt-1 font-semibold text-slate-900">{{ $peserta->nama }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-slate-400">Jurusan</p>
                                            <p class="mt-1 font-semibold text-slate-900">{{ $peserta->jurusan }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-slate-400">Jenis Kelamin</p>
                                            <p class="mt-1 font-semibold text-slate-900">
                                                @if ($peserta->jenis_kelamin == 'L')
                                                    Laki-laki
                                                @elseif($peserta->jenis_kelamin == 'P')
                                                    Perempuan
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-slate-400">Email</p>
                                            <p class="mt-1 font-semibold text-slate-900">{{ $peserta->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-slate-400">No Telepon</p>
                                            <p class="mt-1 font-semibold text-slate-900">{{ $peserta->no_telp }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-start border-t border-slate-100 pt-4">
                        <a href="{{ route('institusi.pengajuan.index') }}"
                            class="inline-flex items-center font-semibold text-blue-600 transition hover:text-blue-700">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
