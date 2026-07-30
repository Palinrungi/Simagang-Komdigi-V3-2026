@extends('layouts.app')

@section('title', 'Kelola Posisi Magang')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
    <div class="bg-gradient-to-r from-blue-700 to-indigo-600 rounded-3xl p-8 text-white shadow-lg flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Kelola Posisi Magang</h1>
            <p class="text-blue-100 text-sm mt-1">Kelola daftar posisi/role magang untuk penempatan dan lowongan.</p>
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-white text-blue-600 font-bold px-5 py-3 rounded-xl hover:bg-blue-50 transition-all">
            + Tambah Posisi
        </button>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white text-xs uppercase font-bold">
                    <th class="py-4 px-6">NO</th>
                    <th class="py-4 px-6">NAMA POSISI</th>
                    <th class="py-4 px-6">TIM / BAGIAN</th>
                    <th class="py-4 px-6 text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($positions as $index => $pos)
                <tr class="hover:bg-blue-50/50 transition-all">
                    <td class="py-4 px-6 font-semibold text-gray-500">{{ $index + 1 }}</td>
                    <td class="py-4 px-6 font-bold text-gray-800">{{ $pos->name }}</td>
                    <td class="py-4 px-6 text-gray-600">{{ $pos->team->name ?? '-' }}</td>
                    <td class="py-4 px-6 text-center">
                        <form action="{{ route('admin.positions.destroy', $pos) }}" method="POST" onsubmit="return confirm('Hapus posisi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-400">Belum ada data posisi magang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Posisi -->
<div id="modalTambah" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <h3 class="text-lg font-bold text-gray-800">Tambah Posisi Magang</h3>
        <form action="{{ route('admin.positions.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Tim / Bagian</label>
                <select name="team_id" class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    <option value="">-- Pilih Tim (Opsional) --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Posisi Magang</label>
                <input type="text" name="name" required placeholder="Contoh: Frontend Developer" class="w-full px-4 py-3 rounded-xl border border-gray-200">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 bg-gray-100 rounded-xl font-semibold text-gray-600">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection