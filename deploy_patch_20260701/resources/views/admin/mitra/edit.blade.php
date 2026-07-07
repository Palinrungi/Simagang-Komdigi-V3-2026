@extends('layouts.app')

@section('title', 'Edit Mitra')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">

    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-sm overflow-hidden">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-7 py-5 text-white">
            <h1 class="text-2xl font-extrabold flex items-center gap-3">
                <i class="fas fa-handshake"></i>
                Formulir Edit Mitra
            </h1>
        </div>

        <form method="POST" action="{{ route('admin.mitra.update', [$jenis, $mitra->id]) }}" class="p-8">
            @csrf
            @method('PUT')

            {{-- Informasi Mitra --}}
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-building"></i>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-blue-900">Informasi Mitra</h2>
                        <p class="text-sm text-slate-500">
                            Data mitra {{ ucfirst($jenis) }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Nama Mitra <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="nama"
                               value="{{ old('nama', $jenis === 'industri' ? $mitra->nama_industri : $mitra->nama_institusi) }}"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                               required>

                        @error('nama')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email', $jenis === 'industri' ? ($mitra->email_industri ?? optional($mitra->user)->email) : optional($mitra->user)->email) }}"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">

                        @error('email')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Kategori
                        </label>

                        <input type="text"
                               name="kategori"
                               value="{{ old('kategori', $jenis === 'industri' ? $mitra->bidang_industri : $mitra->jenis_institusi) }}"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">

                        @error('kategori')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Kontak
                        </label>

                        <input type="text"
                               name="kontak"
                               value="{{ old('kontak', $jenis === 'industri' ? $mitra->nomor_telepon_industri : $mitra->no_hp) }}"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">

                        @error('kontak')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Keamanan dan Status --}}
            <div class="border-t border-slate-100 pt-8 mt-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-shield-alt"></i>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-blue-900">Keamanan & Status</h2>
                        <p class="text-sm text-slate-500">
                            Pengaturan password dan status aktif
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Password Baru
                        </label>

                        <input type="password"
                               name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">

                        <p class="text-sm text-slate-400 mt-2">
                            Minimal 8 karakter
                        </p>

                        @error('password')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Status Aktif
                        </label>

                        <label class="flex items-center gap-3 w-full rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 cursor-pointer">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="w-5 h-5 rounded text-blue-600"
                                   {{ old('is_active', $mitra->is_active ?? true) ? 'checked' : '' }}>

                            <span class="font-semibold text-blue-800">
                                Tandai sebagai aktif
                            </span>
                        </label>

                        @error('is_active')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 border-t pt-6 mt-8">
                <a href="{{ route('admin.mitra.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold hover:opacity-90 transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection