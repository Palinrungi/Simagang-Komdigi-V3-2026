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
                            <!-- <option value="sop">SOP Internal (Dokumen Prosedur)</option> -->
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
                                @php
                                    $ext = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
                                    $isEditable = in_array($ext, ['txt', 'md']);
                                @endphp
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
                                        <div class="flex items-center justify-end gap-2">
                                            @if($isEditable)
                                            <button type="button" 
                                                    onclick="openEditModal('{{ $file['folder'] }}', '{{ $file['filename'] }}')"
                                                    class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200 transition"
                                                    title="Edit konten file">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>
                                            @else
                                            <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed" title="File PDF tidak bisa diedit langsung">
                                                <i class="fas fa-pen text-xs"></i>
                                            </span>
                                            @endif

                                            <form action="{{ route('admin.rag.destroy', ['folder' => $file['folder'], 'filename' => $file['filename']]) }}" method="POST" id="form-delete-{{ $loop->index }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmAction('form-delete-{{ $loop->index }}', 'Hapus Dokumen?', 'Anda tidak akan bisa mengembalikan dokumen ini setelah dihapus.')"
                                                        class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center hover:bg-red-200 transition">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
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

<!-- Modal Edit File -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-4 md:inset-8 lg:inset-12 bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-800 to-indigo-600 px-6 py-5 flex items-center justify-between flex-shrink-0">
            <div>
                <h3 class="text-white text-lg font-bold flex items-center gap-2">
                    <i class="fas fa-file-alt"></i>
                    <span id="editModalTitle">Edit File</span>
                </h3>
                <p class="text-blue-200 text-sm mt-1">
                    Kategori: <span id="editModalFolder" class="font-semibold text-white"></span>
                </p>
            </div>
            <button onclick="closeEditModal()" class="w-10 h-10 rounded-xl bg-white/20 text-white hover:bg-white/30 flex items-center justify-center transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Helper Info -->
        <div class="px-6 py-3 bg-blue-50 border-b border-blue-100 flex-shrink-0">
            <div class="flex items-start gap-2 text-sm text-blue-700">
                <i class="fas fa-info-circle mt-0.5"></i>
                <div>
                    <strong>Format FAQ:</strong> Gunakan <code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs font-mono">Q:</code> untuk pertanyaan dan <code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs font-mono">A:</code> untuk jawaban. Pisahkan tiap pasangan Q&A dengan satu baris kosong.
                    <span class="text-blue-500 ml-1">Setelah menyimpan, jangan lupa klik "Sync AI Database".</span>
                </div>
            </div>
        </div>

        <!-- Editor -->
        <div class="flex-1 p-6 overflow-hidden flex flex-col">
            <!-- Loading State -->
            <div id="editLoading" class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-500 mb-3"></i>
                    <p class="text-gray-500">Memuat konten file...</p>
                </div>
            </div>

            <!-- Textarea -->
            <textarea id="editContent" 
                      class="hidden flex-1 w-full border border-gray-200 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none outline-none"
                      style="font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace; line-height: 1.7; tab-size: 4;"
                      spellcheck="false"
                      placeholder="Tulis konten file di sini..."></textarea>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between flex-shrink-0">
            <p class="text-xs text-gray-400" id="editCharCount">0 karakter</p>
            <div class="flex gap-3">
                <button onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-100 transition">
                    Batal
                </button>
                <button onclick="saveFileContent()" id="editSaveBtn" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ========================
    // Confirm Action (existing)
    // ========================
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

    // ========================
    // Edit Modal Logic
    // ========================
    let currentEditFolder = '';
    let currentEditFilename = '';
    let originalContent = '';

    function openEditModal(folder, filename) {
        currentEditFolder = folder;
        currentEditFilename = filename;

        // Set modal info
        document.getElementById('editModalTitle').textContent = 'Edit: ' + filename;
        document.getElementById('editModalFolder').textContent = folder.replace('_', ' ').toUpperCase();

        // Show modal with loading state
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editLoading').classList.remove('hidden');
        document.getElementById('editContent').classList.add('hidden');
        document.body.style.overflow = 'hidden';

        // Fetch file content via AJAX
        const url = `{{ url('admin/rag-knowledge') }}/${folder}/${filename}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw new Error(data.error || 'Gagal memuat file.'); });
            }
            return response.json();
        })
        .then(data => {
            const textarea = document.getElementById('editContent');
            textarea.value = data.content;
            originalContent = data.content;

            // Show textarea, hide loading
            document.getElementById('editLoading').classList.add('hidden');
            textarea.classList.remove('hidden');
            updateCharCount();
            textarea.focus();
        })
        .catch(error => {
            closeEditModal();
            Swal.fire({
                title: 'Gagal Memuat File',
                text: error.message,
                icon: 'error',
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-3xl shadow-xl border border-gray-100',
                    confirmButton: 'rounded-xl font-bold px-6 py-2.5'
                }
            });
        });
    }

    function closeEditModal() {
        const textarea = document.getElementById('editContent');
        const currentContent = textarea.value;

        // If content changed, confirm before closing
        if (currentContent && currentContent !== originalContent) {
            Swal.fire({
                title: 'Tutup tanpa menyimpan?',
                text: 'Perubahan Anda belum disimpan dan akan hilang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tutup',
                cancelButtonText: 'Kembali ke Editor',
                customClass: {
                    popup: 'rounded-3xl shadow-xl border border-gray-100',
                    confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                    cancelButton: 'rounded-xl font-bold px-6 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    forceCloseModal();
                }
            });
        } else {
            forceCloseModal();
        }
    }

    function forceCloseModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editContent').value = '';
        originalContent = '';
        document.body.style.overflow = '';
    }

    function saveFileContent() {
        const content = document.getElementById('editContent').value;
        const saveBtn = document.getElementById('editSaveBtn');

        if (!content.trim()) {
            Swal.fire({
                title: 'Konten Kosong',
                text: 'Konten file tidak boleh kosong.',
                icon: 'warning',
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-3xl shadow-xl border border-gray-100',
                    confirmButton: 'rounded-xl font-bold px-6 py-2.5'
                }
            });
            return;
        }

        // Confirm save
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'File akan diperbarui. Jangan lupa klik "Sync AI Database" agar perubahan terbaca oleh AI.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl shadow-xl border border-gray-100',
                confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                cancelButton: 'rounded-xl font-bold px-6 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show saving state
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

                const url = `{{ url('admin/rag-knowledge') }}/${currentEditFolder}/${currentEditFilename}`;

                fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => { throw new Error(data.error || 'Gagal menyimpan.'); });
                    }
                    return response.json();
                })
                .then(data => {
                    originalContent = content; // Update original so close won't warn
                    forceCloseModal();
                    
                    Swal.fire({
                        title: 'Berhasil!',
                        html: `${data.message}<br><small class="text-gray-500 mt-2 block">Cache yang di-invalidate: ${data.cache_invalidated || 0} entri</small>`,
                        icon: 'success',
                        confirmButtonColor: '#3b82f6',
                        customClass: {
                            popup: 'rounded-3xl shadow-xl border border-gray-100',
                            confirmButton: 'rounded-xl font-bold px-6 py-2.5'
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Gagal Menyimpan',
                        text: error.message,
                        icon: 'error',
                        confirmButtonColor: '#3b82f6',
                        customClass: {
                            popup: 'rounded-3xl shadow-xl border border-gray-100',
                            confirmButton: 'rounded-xl font-bold px-6 py-2.5'
                        }
                    });
                })
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
                });
            }
        });
    }

    // Character counter
    function updateCharCount() {
        const textarea = document.getElementById('editContent');
        const count = textarea.value.length;
        document.getElementById('editCharCount').textContent = count.toLocaleString('id-ID') + ' karakter';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('editContent');
        textarea.addEventListener('input', updateCharCount);

        // Handle Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            }
        });
    });
</script>
@endsection
