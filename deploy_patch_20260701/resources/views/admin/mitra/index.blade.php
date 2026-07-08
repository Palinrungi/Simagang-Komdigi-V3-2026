@extends('layouts.app')

@section('title', 'Kelola Mitra')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">

    <div class="bg-gradient-to-r from-blue-900 to-indigo-600 rounded-3xl p-8 text-white mb-8">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold">Kelola Mitra</h1>
            <p class="text-blue-100 mt-2">
                Manajemen data mitra industri dan institusi
            </p>
        </div>

        <a href="{{ route('admin.mitra.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/15 hover:bg-white/25 border border-white/30 font-bold transition">
            <i class="fas fa-plus"></i>
            Tambah Mitra
        </a>
    </div>
</div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 text-green-700 px-5 py-4 font-semibold">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-7 py-5 text-white">
            <h2 class="text-2xl font-extrabold flex items-center gap-3">
                <i class="fas fa-filter"></i>
                Filter Data
            </h2>
        </div>

        <form method="GET" action="{{ route('admin.mitra.index') }}" class="p-7">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Cari Nama
                    </label>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Ketik nama mitra..."
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Jenis Mitra
                    </label>

                    <select name="jenis"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="semua" {{ request('jenis', 'semua') == 'semua' ? 'selected' : '' }}>
                            Semua Mitra
                        </option>

                        <option value="industri" {{ request('jenis') == 'industri' ? 'selected' : '' }}>
                            Industri
                        </option>

                        <option value="institusi" {{ request('jenis') == 'institusi' ? 'selected' : '' }}>
                            Institusi
                        </option>
                    </select>
                </div>

                <div>
                    <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-white font-bold hover:opacity-90 transition">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-7 py-5 text-white flex justify-between items-center">
            <h2 class="text-2xl font-extrabold flex items-center gap-3">
                <i class="fas fa-handshake"></i>
                Daftar Mitra
            </h2>

            <span class="bg-white text-indigo-700 px-4 py-1 rounded-full text-sm font-bold">
                {{ $mitra->total() }} mitra
            </span>
        </div>

        <div class="p-7 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-50 text-blue-900 text-sm uppercase">
                        <th class="px-5 py-4 text-left rounded-l-xl">Nama</th>
                        <th class="px-5 py-4 text-left">Email</th>
                        <th class="px-5 py-4 text-left">Jenis</th>
                        <th class="px-5 py-4 text-left">Kategori</th>
                        <th class="px-5 py-4 text-left">Kontak</th>
                        <th class="px-5 py-4 text-left">Status</th>
                        <th class="px-5 py-4 text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mitra as $item)
                        <tr class="border-b hover:bg-slate-50 transition">
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $item->nama }}
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $item->email }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->jenis == 'Industri' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $item->jenis }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $item->kategori }}
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $item->kontak }}
                            </td>

                            <td class="px-5 py-4">
                                @if($item->is_active ?? true)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                        <i class="fas fa-circle text-[7px]"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                        <i class="fas fa-circle text-[7px]"></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-3">

                                    <a href="{{ route('admin.mitra.edit', [$item->jenis_key, $item->id]) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.mitra.destroy', [$item->jenis_key, $item->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data mitra ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-500">
                                Data mitra belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $mitra->links() }}
            </div>
        </div>
    </div>

</div>
@endsection