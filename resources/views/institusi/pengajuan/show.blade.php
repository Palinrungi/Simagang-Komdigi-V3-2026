@extends('layouts.app')

@section('title', 'Detail Pengajuan Magang')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-blue-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="bg-blue-600 shadow-lg rounded-lg p-6 mt-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-1">Detail Pengajuan Magang</h1>
                        <p class="text-blue-100 text-sm">
                            Informasi pengajuan dan calon anak magang
                        </p>
                    </div>

                    <a href="#"
                        class="inline-flex items-center gap-2 bg-white text-blue-700 font-semibold px-4 py-2 rounded-lg shadow">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                </div>
            </div>

            <!-- CARD -->
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden">

                <!-- INFORMASI MAGANG -->
                <div class="p-8 border-b">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">

                        <!-- Kiri: Icon + Judul -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-briefcase text-blue-600"></i>
                            </div>
                            <h2 class="text-lg font-bold text-blue-900">
                                Informasi Surat Pengajuan Magang
                            </h2>
                        </div>

                        <!-- Kanan: Tombol -->
                        <a href="{{ route('pengajuan.surat', $pengajuan) }}" target="_blank"
                            class="sm:ml-auto w-full sm:w-auto text-center text-blue-600 font-semibold bg-blue-100 px-4 py-2 rounded-lg hover:bg-blue-200 transition">

                            <i class="fas fa-download mr-2"></i>
                            Download Surat Pengajuan
                        </a>

                    </div>

                    <div class="text-gray-700">

                        <div>
                            <p class="text-sm text-gray-500">Nomor Surat Pengajuan</p>
                            <p class="font-semibold">{{ $pengajuan->no_surat }}</p>
                        </div>
                        <div class="mt-6">
                            <p class="text-sm text-gray-500">Penanggung Jawab</p>
                            <p class="font-semibold">{{ $pengajuan->tujuan_surat }}</p>
                        </div>
                        <div class="mt-6">
                            <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                            <p class="font-semibold">{{ $pengajuan->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 border-b bg-gray-50">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-clipboard text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">Informasi Pengajuan Magang</h2>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700">

                        <div>
                            <p class="text-sm text-gray-500">Keperluan</p>
                            <p class="font-semibold">{{ $pengajuan->keperluan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Masuk</p>
                            <p class="font-semibold">{{ $pengajuan->start_date }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Keluar</p>
                            <p class="font-semibold">{{ $pengajuan->end_date }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 border-b">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">

                        <!-- Kiri: Icon + Judul -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-briefcase text-blue-600"></i>
                            </div>
                            <h2 class="text-lg font-bold text-blue-900">
                                Status Pengajuan Magang
                            </h2>
                        </div>

                        <!-- Kanan: Tombol -->
                        @if ($pengajuan->status == 'approved')
                            <a href="{{ route('institusi.pengajuan.surat-balasan', $pengajuan) }}" target="_blank"
                                class="sm:ml-auto w-full sm:w-auto text-center text-green-600 font-semibold bg-green-100 px-4 py-2 rounded-lg hover:bg-green-200 transition">
                                <i class="fas fa-download mr-2"></i>
                                Download Surat Balasan
                            </a>
                        @endif

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 mt-6">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <div class="flex items-center mt-2">
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold
                                @if ($pengajuan->status == 'approved') bg-green-100 text-green-800
                                @elseif($pengajuan->status == 'rejected') bg-red-100 text-red-800
                                @elseif($pengajuan->status == 'revised') bg-orange-100 text-orange-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </div>

                            @if ($pengajuan->status == 'revised' && $pengajuan->admin_note)
                                <div class="mt-4 p-4 bg-orange-50 rounded-xl border border-orange-100">
                                    <h4 class="text-sm font-semibold text-orange-800 mb-2">Catatan Revisi</h4>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $pengajuan->admin_note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- PESERTA -->
                <div class="p-8 bg-gray-50">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-blue-900">Daftar Calon Anak Magang</h2>
                        </div>
                    </div>

                    @foreach ($pengajuan->details as $i => $peserta)
                        <div class="mb-6 bg-white p-6 rounded-xl border">

                            <h3 class="text-md font-semibold text-blue-700 mb-4">
                                Calon Anak Magang {{ $i + 1 }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700">

                                <div>
                                    <p class="text-sm text-gray-500">Nama</p>
                                    <p class="font-semibold">{{ $peserta->nama }}</p>
                                </div>


                                <div>
                                    <p class="text-sm text-gray-500">Jurusan</p>
                                    <p class="font-semibold">{{ $peserta->jurusan }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Jenis Kelamin</p>
                                    <p class="font-semibold">
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
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-semibold">{{ $peserta->email }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">No Telepon</p>
                                    <p class="font-semibold">{{ $peserta->no_telp }}</p>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- FOOTER -->
                <div class="p-6 border-t">
                    <a href="{{ route('institusi.pengajuan.index') }}" class="text-blue-600 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
