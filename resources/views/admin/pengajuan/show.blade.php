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
                </div>
            </div>

            <!-- CARD -->
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden">

                <!-- INFORMASI MAGANG -->
                <div class="p-8 border-b bg-gray-50">
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

                <div class="p-8 border-b">
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

                {{-- Form Update Status --}}
                <div class="p-8 bg-white shadow-lg border border-blue-100 overflow-hidden">
                    <div class="bg-gradient-to-r rounded-t-2xl from-blue-600 to-indigo-600 px-6 py-4">
                        <div class="flex items-center bg-blue-700 bg-opacity-20">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Update Status Pengajuan</h3>
                        </div>
                    </div>

                    <div class="p-8 bg-gray-50 rounded-b-2xl border border-blue-100">
                        <form id="update-status-form" method="POST"
                            action="{{ route('admin.pengajuan.update-status', $pengajuan) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="notify_whatsapp" id="notify_whatsapp" value="0">

                            <div class="mb-6">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Pengajuan Calon Anak Magang
                                </label>
                                <select name="status" id="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    {{ in_array($pengajuan->status, ['approved', 'rejected'], true) ? 'disabled' : '' }}>
                                    {{-- <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option> --}}
                                    <option value="approved" {{ $pengajuan->status == 'approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>
                                    <option value="revised" {{ $pengajuan->status == 'revised' ? 'selected' : '' }}>
                                        Revisi
                                    </option>
                                    <option value="rejected" {{ $pengajuan->status == 'rejected' ? 'selected' : '' }}>
                                        Rejected
                                    </option>
                                </select>
                                <p class="mt-2 text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-2 mt-0.5 text-blue-500"></i>
                                    <span>Perbarui status pengajuan magang sesuai dengan keputusan Anda</span>
                                </p>
                            </div>

                            <div class="mb-6" id="no_surat_block">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Surat Balasan
                                </label>
                                <input type="text" name="nomor_surat_balasan"
                                    placeholder="contoh: B-747/BBPSDMP.73/UM.01.01/12/2025"
                                    value="{{ old('nomor_surat_balasan', $pengajuan->nomor_surat_balasan ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>

                            <div class="mb-6" id="admin-note-block"
                                style="display: {{ $pengajuan->status == 'revised' ? 'block' : 'none' }};">
                                <label for="admin_note" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catatan Revisi (opsional)
                                </label>
                                <textarea name="admin_note" id="admin_note" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ old('admin_note', $pengajuan->admin_note) }}</textarea>
                                <p class="mt-2 text-sm text-gray-500">
                                    Tuliskan apa saja yang perlu direvisi oleh institusi/anak magang.
                                </p>
                            </div>

                            <script>
                                (function() {
                                    const statusEl = document.getElementById('status');
                                    const noSuratBlock = document.getElementById('no_surat_block');
                                    const noteBlock = document.getElementById('admin-note-block');

                                    function updateUI(value) {

                                        // NOMOR SURAT
                                        if (value === 'rejected' || value === 'revised') {
                                            noSuratBlock.style.display = 'none';
                                        } else {
                                            noSuratBlock.style.display = 'block';
                                        }

                                        // CATATAN REVISI
                                        if (value === 'revised') {
                                            noteBlock.style.display = 'block';
                                        } else {
                                            noteBlock.style.display = 'none';
                                        }
                                    }

                                    // trigger saat change
                                    statusEl.addEventListener('change', function(e) {
                                        updateUI(e.target.value);
                                    });

                                    // 🔥 trigger awal (PENTING)
                                    updateUI(statusEl.value);

                                })();
                            </script>

                            <!-- Modal Konfirmasi Simpan -->
                            <div id="save-confirm-modal"
                                class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
                                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                                <div class="relative max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden">
                                    <div class="p-6 text-center">
                                        <div
                                            class="mx-auto w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-3">
                                            <span class="text-blue-600 text-xl">?</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800">Simpan Perubahan?</h3>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Status akan diperbarui terlebih dahulu. Setelah itu Anda bisa memilih apakah
                                            ingin langsung mengirim notifikasi WhatsApp.
                                        </p>
                                    </div>
                                    <div class="px-4 pb-4 pt-2 bg-gray-50 flex gap-3 justify-center">
                                        <button id="save-confirm-cancel" type="button"
                                            class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 font-semibold hover:bg-gray-100">
                                            Tidak
                                        </button>
                                        <button id="save-confirm-accept" type="button"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                                            Ya, Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal WhatsApp -->
                            <div id="whatsapp-confirm-modal"
                                class="hidden fixed inset-0 z-50 flex items-center justify-center px-4">
                                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                                <div class="relative max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden">
                                    <div class="p-6 text-center">
                                        <div
                                            class="mx-auto w-14 h-14 rounded-full bg-green-50 flex items-center justify-center mb-3">
                                            <i class="fab fa-whatsapp text-green-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800" id="whatsapp-modal-title">Kirim
                                            Notifikasi WhatsApp?</h3>
                                        <p class="mt-2 text-sm text-gray-500" id="whatsapp-modal-message">
                                            Status sudah tersimpan. Apakah Anda ingin langsung membuka WhatsApp dengan
                                            template pesan yang sudah siap?
                                        </p>
                                    </div>
                                    <div class="px-4 pb-4 pt-2 bg-gray-50 flex gap-3 justify-center">
                                        <button id="whatsapp-confirm-cancel" type="button"
                                            class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 font-semibold hover:bg-gray-100">
                                            Tidak
                                        </button>
                                        <button id="whatsapp-confirm-accept" type="button"
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">
                                            Ya, Kirim WhatsApp
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                (function() {
                                    const form = document.getElementById('update-status-form');
                                    const statusEl = document.getElementById('status');
                                    const saveModal = document.getElementById('save-confirm-modal');
                                    const whatsappModal = document.getElementById('whatsapp-confirm-modal');
                                    const saveCancelButton = document.getElementById('save-confirm-cancel');
                                    const saveAcceptButton = document.getElementById('save-confirm-accept');
                                    const whatsappCancelButton = document.getElementById('whatsapp-confirm-cancel');
                                    const whatsappAcceptButton = document.getElementById('whatsapp-confirm-accept');
                                    const whatsappModalTitle = document.getElementById('whatsapp-modal-title');
                                    const whatsappModalMessage = document.getElementById('whatsapp-modal-message');
                                    const whatsappSection = document.getElementById('whatsapp-resend-section');
                                    const whatsappLink = document.getElementById('whatsapp-resend-link');

                                    let latestWhatsappUrl = @json($whatsapp['url'] ?? null);
                                    let isSaving = false;

                                    function openModal(modal) {
                                        modal.classList.remove('hidden');
                                    }

                                    function closeModal(modal) {
                                        modal.classList.add('hidden');
                                    }

                                    function updateWhatsappButton(url) {
                                        latestWhatsappUrl = url || null;

                                        if (whatsappLink) {
                                            whatsappLink.href = url || '#';
                                        }

                                        if (whatsappSection) {
                                            if (url) {
                                                whatsappSection.classList.remove('hidden');
                                            } else {
                                                whatsappSection.classList.add('hidden');
                                            }
                                        }
                                    }

                                    function buildWhatsappMessageLabel(status) {
                                        if (status === 'approved') {
                                            return 'Pengajuan sudah disetujui. Apakah Anda ingin langsung membuka WhatsApp untuk mengirim notifikasi ke institusi?';
                                        }

                                        if (status === 'rejected') {
                                            return 'Pengajuan sudah ditolak. Apakah Anda ingin langsung membuka WhatsApp untuk mengirim notifikasi ke institusi?';
                                        }

                                        if (status === 'revised') {
                                            return 'Pengajuan perlu revisi. Apakah Anda ingin langsung membuka WhatsApp untuk mengirim catatan revisi ke institusi?';
                                        }

                                        return 'Status sudah tersimpan. Apakah Anda ingin langsung membuka WhatsApp untuk mengirim notifikasi ke institusi?';
                                    }

                                    async function saveStatus() {
                                        if (isSaving) {
                                            return;
                                        }

                                        isSaving = true;
                                        saveAcceptButton.disabled = true;
                                        saveCancelButton.disabled = true;
                                        saveAcceptButton.textContent = 'Menyimpan...';

                                        try {
                                            const formData = new FormData(form);

                                            const response = await fetch(form.action, {
                                                method: 'POST',
                                                headers: {
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                    'Accept': 'application/json',
                                                },
                                                body: formData,
                                            });

                                            const payload = await response.json();

                                            if (!response.ok) {
                                                const errorMessages = payload.errors ?
                                                    Object.values(payload.errors).flat().join('\n') :
                                                    (payload.message || 'Gagal menyimpan status.');
                                                throw new Error(errorMessages);
                                            }

                                            updateWhatsappButton(payload.whatsapp?.url || null);
                                            whatsappModalTitle.textContent = 'Kirim Notifikasi WhatsApp?';
                                            whatsappModalMessage.textContent = buildWhatsappMessageLabel(payload.status);

                                            closeModal(saveModal);
                                            openModal(whatsappModal);
                                        } catch (error) {
                                            alert(error.message || 'Gagal menyimpan status.');
                                        } finally {
                                            isSaving = false;
                                            saveAcceptButton.disabled = false;
                                            saveCancelButton.disabled = false;
                                            saveAcceptButton.textContent = 'Ya, Simpan';
                                        }
                                    }

                                    form.addEventListener('submit', function(event) {
                                        event.preventDefault();

                                        openModal(saveModal);
                                        saveCancelButton.focus();
                                    });

                                    saveCancelButton.addEventListener('click', function() {
                                        closeModal(saveModal);
                                    });

                                    saveAcceptButton.addEventListener('click', function() {
                                        saveStatus();
                                    });

                                    whatsappCancelButton.addEventListener('click', function() {
                                        closeModal(whatsappModal);
                                        window.location.reload();
                                    });

                                    whatsappAcceptButton.addEventListener('click', function() {
                                        if (!latestWhatsappUrl) {
                                            closeModal(whatsappModal);
                                            return;
                                        }

                                        window.open(latestWhatsappUrl, '_blank', 'noopener');
                                        closeModal(whatsappModal);
                                    });
                                })();
                            </script>

                            <div class="flex flex-col pt-4 border-gray-200">
                                @if ($pengajuan->status == 'approved')
                                    <a href="{{ route('admin.pengajuan.surat-balasan', $pengajuan) }}" target="_blank"
                                        class="w-full inline-flex items-center px-6 py-3 bg-green-100 hover:bg-green-200 text-green-700 justify-center font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                        <i class="fas fa-download mr-2"></i>
                                        Download Surat Balasan
                                    </a>
                                    <button type="button" disabled
                                        class="mt-6 w-full inline-flex items-center px-6 py-3 bg-gray-300 text-gray-700 justify-center font-bold rounded-xl shadow-sm transition-all duration-300">
                                        Sudah Disetujui — Tidak dapat diubah
                                    </button>
                                @elseif($pengajuan->status == 'rejected')
                                    <button type="button" disabled
                                        class="w-full inline-flex items-center px-6 py-3 bg-gray-300 text-gray-700 justify-center font-bold rounded-xl shadow-sm transition-all duration-300">
                                        Sudah Ditolak — Tidak dapat diubah
                                    </button>
                                @else
                                    <button type="submit"
                                        class="w-full inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white justify-center font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                        Simpan Status
                                    </button>
                                @endif

                                <div id="whatsapp-resend-section"
                                    class="mt-6 {{ empty($whatsapp['url']) ? 'hidden' : '' }}">
                                    <a id="whatsapp-resend-link" href="{{ $whatsapp['url'] ?? '#' }}" target="_blank"
                                        rel="noopener"
                                        class="w-full inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white justify-center font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                                        <i class="fab fa-whatsapp mr-2"></i>
                                        Kirim Notifikasi WhatsApp
                                    </a>
                                    <p class="text-xs text-gray-500 text-center mt-2">
                                        Gunakan tombol ini jika Anda memilih tidak mengirim WhatsApp setelah status
                                        disimpan.
                                    </p>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="p-6 border-t">
                    <a href="{{ route('admin.pengajuan.index') }}" class="text-blue-600 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
