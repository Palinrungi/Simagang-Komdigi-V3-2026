@extends('layouts.app')

@section('title', 'Tambah Mitra')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">

    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-sm overflow-hidden"
         x-data="{ jenis: '{{ old('jenis_mitra', 'institusi') }}' }">

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-7 py-5 text-white">
            <h1 class="text-2xl font-extrabold flex items-center gap-3">
                <i class="fas fa-handshake"></i>
                Tambah Mitra
            </h1>
            <p class="text-blue-100 mt-1">
                Tambahkan admin institusi atau admin industri
            </p>
        </div>

        <form method="POST" action="{{ route('admin.mitra.store') }}" class="p-8">
            @csrf

            <div class="mb-8">
                <label class="block font-semibold text-slate-700 mb-3">
                    Jenis Mitra <span class="text-red-500">*</span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 rounded-2xl border px-5 py-4 cursor-pointer"
                           :class="jenis === 'institusi' ? 'border-blue-400 bg-blue-50 text-blue-800' : 'border-slate-200 bg-white text-slate-700'">
                        <input type="radio"
                               name="jenis_mitra"
                               value="institusi"
                               x-model="jenis"
                               class="text-blue-600">
                        <div>
                            <div class="font-bold">Institusi</div>
                            <div class="text-sm opacity-70">Sekolah atau kampus</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 rounded-2xl border px-5 py-4 cursor-pointer"
                           :class="jenis === 'industri' ? 'border-blue-400 bg-blue-50 text-blue-800' : 'border-slate-200 bg-white text-slate-700'">
                        <input type="radio"
                               name="jenis_mitra"
                               value="industri"
                               x-model="jenis"
                               class="text-blue-600">
                        <div>
                            <div class="font-bold">Industri</div>
                            <div class="text-sm opacity-70">Perusahaan atau mitra industri</div>
                        </div>
                    </label>
                </div>

                @error('jenis_mitra')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- LANGKAH 1 INSTITUSI --}}
            <div x-show="jenis === 'institusi'" x-cloak class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="font-extrabold">1</span>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-blue-900">Langkah 1</h2>
                        <p class="text-sm text-slate-500">Informasi institusi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Nama Institusi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_institusi"
                               value="{{ old('nama_institusi') }}"
                               placeholder="cth. SMK Negeri 1 Jakarta"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('nama_institusi')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Nomor Identitas
                        </label>
                        <input type="text"
                               name="nomor_identitas"
                               value="{{ old('nomor_identitas') }}"
                               placeholder="NPSN / Kode PT"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('nomor_identitas')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Jenis Institusi <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_institusi"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Pilih Jenis</option>
                            <option value="sekolah" {{ old('jenis_institusi') == 'sekolah' ? 'selected' : '' }}>
                                Sekolah
                            </option>
                            <option value="kampus" {{ old('jenis_institusi') == 'kampus' ? 'selected' : '' }}>
                                Kampus / Universitas
                            </option>
                        </select>
                        @error('jenis_institusi')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- LANGKAH 1 INDUSTRI --}}
            <div x-show="jenis === 'industri'" x-cloak class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="font-extrabold">1</span>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-blue-900">Langkah 1</h2>
                        <p class="text-sm text-slate-500">Informasi industri</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Nama Industri <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_industri"
                               value="{{ old('nama_industri') }}"
                               placeholder="cth. PT Digital Makassar"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('nama_industri')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Bidang Industri <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="bidang_industri"
                               value="{{ old('bidang_industri') }}"
                               placeholder="cth. Teknologi Informasi"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('bidang_industri')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Kota / Kabupaten
                        </label>
                        <input type="text"
                               name="kota_kabupaten"
                               value="{{ old('kota_kabupaten') }}"
                               placeholder="cth. Makassar"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('kota_kabupaten')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Alamat Industri
                        </label>
                        <input type="text"
                               name="alamat_industri"
                               value="{{ old('alamat_industri') }}"
                               placeholder="Alamat industri"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('alamat_industri')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- LANGKAH 2 ADMIN --}}
            <div class="border-t border-slate-100 pt-8 mt-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="font-extrabold">2</span>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-blue-900">Langkah 2</h2>
                        <p class="text-sm text-slate-500">Informasi admin</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_admin"
                               value="{{ old('nama_admin') }}"
                               placeholder="cth. Budi Santoso"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('nama_admin')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="aldisar2000@gmail.com"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('email')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="text"
                               name="no_hp"
                               value="{{ old('no_hp') }}"
                               placeholder="08xx-xxxx-xxxx"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @error('no_hp')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Role
                        </label>
                        <div class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-slate-50 font-semibold text-slate-600">
                            <span x-show="jenis === 'institusi'">Admin Institusi</span>
                            <span x-show="jenis === 'industri'">Admin Industri</span>
                            <span class="ml-2 text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">Default</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LANGKAH 3 KEAMANAN --}}
            <div class="border-t border-slate-100 pt-8 mt-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="font-extrabold">3</span>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-blue-900">Langkah 3</h2>
                        <p class="text-sm text-slate-500">Keamanan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>

                        <input type="password"
                               name="password"
                               placeholder="•••••••••"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">

                        <p class="text-sm text-slate-400 mt-2">
                            Gunakan huruf besar, angka, simbol, dan minimal 8 karakter.
                        </p>

                        @error('password')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 border-t pt-6 mt-8">
                <a href="{{ route('admin.mitra.index') }}"
                   class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>

                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold hover:opacity-90 transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection