@extends('layouts.app')

@section('title', 'Manajemen AI Knowledge Base')

@section('content')
<div class="min-h-screen bg-[#F4F7FF] px-6 py-8">
    
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-800 to-indigo-600 rounded-3xl p-8 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider mb-2">Admin Panel / AI Setup</p>
                    <h1 class="text-3xl font-bold">Kelola Knowledge Base Chatbot SIMA</h1>
                    <p class="text-blue-100 mt-2">Unggah dokumen SOP dan FAQ agar Chatbot AI menjadi lebih pintar.</p>
                </div>

                <div class="flex gap-3">
                    <form action="{{ route('admin.rag.clearCache') }}" method="POST" id="form-clear-cache">
                        @csrf
                        <button type="button" onclick="confirmAction('form-clear-cache', 'Reset Cache AI?', 'Chatbot akan berpikir sedikit lebih lama untuk pertanyaan berikutnya.')"
                           class="inline-flex items-center justify-center gap-2 bg-yellow-500 text-white hover:bg-yellow-600 px-5 py-3 rounded-2xl font-semibold shadow transition">
                            <i class="fas fa-broom"></i>
                            Clear Cache
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.rag.sync') }}" method="POST" id="form-sync-db">
                        @csrf
                        <button type="button" onclick="confirmAction('form-sync-db', 'Sinkronisasi Database AI?', 'Proses ini akan memakan waktu dan menimpa data AI lama dengan yang baru.', 'info')"
                           class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-5 py-3 rounded-2xl font-semibold shadow hover:bg-blue-50 transition">
                            <i class="fas fa-sync-alt"></i>
                            Sync AI Database
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Form Upload -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Unggah Dokumen Baru</h2>
                <form action="{{ route('admin.rag.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih File (PDF/TXT)</label>
                        <input type="file" name="file" required accept=".pdf,.txt" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori Visibilitas</label>
                        <select name="folder" required class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="public_faq">Publik (Bisa diakses siapa saja)</option>
                            <option value="faq">Internal FAQ (Hanya untuk Intern/Magang)</option>
                            <option value="sop">SOP Internal (Dokumen Prosedur)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-2">Pilih kategori yang sesuai agar AI tahu siapa yang berhak membaca informasi ini.</p>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-blue-700 transition shadow">
                        <i class="fas fa-upload mr-2"></i> Unggah Dokumen
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Dokumen -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Dokumen AI</h2>
                    <p class="text-sm text-gray-400 mt-1">File yang saat ini dibaca oleh Chatbot</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Nama File</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Ukuran</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($files as $file)
                                <tr class="hover:bg-blue-50/40 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800">{{ $file['filename'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                            {{ $file['folder'] == 'public_faq' ? 'bg-green-100 text-green-700' : 
                                              ($file['folder'] == 'faq' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                            {{ strtoupper(str_replace('_', ' ', $file['folder'])) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-sm">
                                        {{ number_format($file['size'] / 1024, 2) }} KB
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.rag.destroy', ['folder' => $file['folder'], 'filename' => $file['filename']]) }}" method="POST" id="form-delete-{{ $loop->index }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmAction('form-delete-{{ $loop->index }}', 'Hapus Dokumen?', 'Anda tidak akan bisa mengembalikan dokumen ini setelah dihapus.')"
                                                    class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center hover:bg-red-200 transition inline-flex">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada dokumen yang diunggah.
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

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAction(formId, title, text, icon = 'warning') {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3b82f6', // blue-500
            cancelButtonColor: '#ef4444', // red-500
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-xl border border-gray-100',
                confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                cancelButton: 'rounded-xl font-bold px-6 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endsection
