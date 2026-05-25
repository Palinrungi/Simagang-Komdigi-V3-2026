@extends('layouts.app')

@section('title', 'Tambah Pengajuan Magang - Sistem Magang')

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
            box-shadow: 0 1px 3px rgba(30, 58, 138, 0.06), 0 4px 18px rgba(30, 58, 138, 0.06);
            overflow: hidden;
        }

        .section-card {
            background: #f8faff;
            border: 1px solid #e8eeff;
            border-radius: 18px;
        }

        .soft-badge {
            background: linear-gradient(135deg, #eff6ff, #f5f3ff);
            border: 1px solid #dbeafe;
        }

        .field-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e3a8a;
        }

        .field-input,
        .field-select {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #dbe3f0;
            background: #fff;
            padding: 0.9rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .field-input:focus,
        .field-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .upload-box {
            width: 100%;
            border-radius: 16px;
            border: 1px dashed #c7d2fe;
            background: #f8faff;
            padding: 0.9rem 1rem;
        }

        .intern-card {
            background: #fff;
            border: 1px solid #e8eeff;
            border-radius: 18px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(30, 58, 138, 0.05);
        }

        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #2563eb;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .action-link:hover {
            color: #1d4ed8;
        }

        .primary-btn {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="hero-strip px-5 sm:px-8 py-6 sm:py-8">
                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-3xl text-white">
                        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                            Tambah Pengajuan Magang
                        </h1>
                    </div>
                    <div class="soft-badge rounded-2xl px-4 py-4 text-sm font-semibold text-slate-700 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm">
                                <i class="fas fa-file-pen"></i>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Form</p>
                                <p class="text-base font-bold text-slate-900">Pengajuan Magang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="border-b border-slate-100 bg-white px-5 sm:px-8 py-4 sm:py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <i class="fas fa-edit text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Lengkapi Data</p>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Informasi Pengajuan</h2>
                        </div>
                    </div>
                </div>

                <div class="p-5 sm:p-8">
                    <form id="pengajuan-form" method="POST" action="{{ route('institusi.pengajuan.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="section-card p-5 sm:p-6 mb-6 sm:mb-8">
                            <div class="flex items-start gap-4 mb-6">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 flex-shrink-0">
                                    <i class="fas fa-briefcase text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Informasi Pengerjaan Magang
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-500">Isi periode magang, tujuan kegiatan, dan
                                        unggah surat pengajuan.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                                <div>
                                    <label for="no_surat" class="field-label">
                                        Nomor Surat Pengajuan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="no_surat" id="no_surat" value="{{ old('no_surat') }}"
                                        placeholder="contoh: 13035/UN4.1.17/HM.01.01/2025" required class="field-input">
                                    @error('no_surat')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tujuan_surat" class="field-label">
                                        Penandatangan Surat <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="tujuan_surat" id="tujuan_surat"
                                        value="{{ old('tujuan_surat') }}"
                                        placeholder="contoh: Wakil Dekan Bidang Akademik dan Kemahasiswaan" required
                                        class="field-input">
                                    @error('tujuan_surat')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="field-label">
                                        Tanggal Masuk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                                        class="field-input">
                                    @error('start_date')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="field-label">Tanggal Keluar <span class="text-red-500">*</span></label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                                        class="field-input">
                                    @error('end_date')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="field-label">
                                        Keperluan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="keperluan" id="keperluan" required class="field-select">
                                        <option value="" disabled {{ old('keperluan') ? '' : 'selected' }}>Pilih
                                        </option>
                                        @foreach (['Magang', 'KKN Profesi', 'PKL', 'Praktek Industri', 'Magang Industri', 'Guru Magang Industri', 'Job on Training'] as $p)
                                            <option value="{{ $p }}"
                                                {{ old('keperluan') == $p ? 'selected' : '' }}>{{ $p }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('keperluan')
                                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="surat_magang" class="field-label">
                                        Surat Magang (.pdf) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="surat_magang" id="surat_magang" accept=".pdf" required
                                        class="upload-box">
                                    @error('surat_magang')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="section-card p-5 sm:p-6 mb-6 sm:mb-8">
                            <div class="flex items-start gap-4 mb-6">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 flex-shrink-0">
                                    <i class="fas fa-user text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Informasi Calon Peserta Magang
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-500">Isi data dan kontak calon peserta magang.</p>
                                </div>
                            </div>

                            <div id="intern-container">
                                <div class="intern-card mb-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-md font-semibold text-blue-700 peserta-title">
                                            Calon Peserta Magang 1
                                        </h3>
                                        <button type="button" onclick="removeIntern(this)"
                                            class="delete-btn text-red-500 hover:text-red-700 font-semibold transition-colors hidden">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>

                                    <div class="mb-6">
                                        <label class="field-label">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name[]" required class="field-input">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                                        <div>
                                            <label class="field-label">
                                                Email <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" name="email[]" required class="field-input">
                                        </div>

                                        <div>
                                            <label class="field-label">
                                                Jurusan <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="jurusan[]" required class="field-input">
                                        </div>

                                        <div>
                                            <label class="field-label">
                                                Jenis Kelamin <span class="text-red-500">*</span>
                                            </label>
                                            <select name="jenis_kelamin[]" required class="field-select">
                                                <option value="">Pilih</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="field-label">
                                                Nomor Telepon <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="no_telp[]" required class="field-input">
                                        </div>

                                        <div>
                                            <label for="soft_skill" class="field-label">Soft Skill</label>
                                            <input type="text" name="soft_skill[]" id="soft_skill"
                                                placeholder="contoh: Komunikasi" class="field-input">
                                        </div>

                                        <div>
                                            <label for="hard_skill" class="field-label">Hard Skill</label>
                                            <input type="text" name="hard_skill[]" id="hard_skill"
                                                placeholder="contoh: Pemrograman" class="field-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <span class="text-sm text-slate-500" id="count-info">1 calon peserta magang ditambahkan</span>
                            <button type="button" onclick="addIntern()"
                                class="inline-flex items-center justify-center rounded-2xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-100">
                                <i class="fas fa-plus mr-2"></i> Tambah Peserta
                            </button>
                        </div>

                        <div
                            class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('institusi.pengajuan.index') }}" class="action-link">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="button" id="btn-open-submit-modal"
                                class="primary-btn inline-flex w-full sm:w-auto items-center justify-center rounded-2xl px-8 py-3 text-sm sm:text-base font-semibold text-white shadow-lg transition hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- ── CONFIRMATION MODAL ── --}}
    <div id="submit-confirm-modal"
        style="display:none;"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900 bg-opacity-50 backdrop-blur-sm">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

            <div class="flex items-center justify-center w-14 h-14 mx-auto bg-blue-100 rounded-full mb-4">
                <i class="fas fa-paper-plane text-blue-600 text-2xl"></i>
            </div>

            <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi Simpan Pengajuan</h3>

            <p class="text-center text-gray-600 text-sm mb-4">
                Pastikan semua data yang diisi sudah benar sebelum menyimpan pengajuan.
            </p>

            <div class="my-4 p-4 bg-blue-50 rounded-xl border border-blue-200 space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 flex-shrink-0">Nomor Surat</span>
                    <span class="font-semibold text-gray-900 text-right truncate" id="modal-no-surat">–</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 flex-shrink-0">Periode Magang</span>
                    <span class="font-semibold text-gray-900 text-right" id="modal-periode">–</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 flex-shrink-0">Keperluan</span>
                    <span class="font-semibold text-gray-900 text-right" id="modal-keperluan">–</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 flex-shrink-0">Jumlah Peserta</span>
                    <span class="font-semibold text-gray-900 text-right" id="modal-peserta">–</span>
                </div>
            </div>

            <p class="text-center text-gray-500 text-xs mb-6">
                Data yang sudah dikirim dapat ditinjau kembali oleh admin.
            </p>

            <div class="flex gap-3">
                <button type="button" id="btn-submit-cancel"
                    class="flex-1 px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="button" id="btn-submit-confirm"
                    class="flex-1 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Ya, Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
        // ── Intern cards ──
        let count = 1;

        function updateTitles() {
            const cards = document.querySelectorAll('.intern-card');
            cards.forEach((card, index) => {
                card.querySelector('.peserta-title').innerText = 'Calon Peserta Magang ' + (index + 1);

                const deleteBtn = card.querySelector('.delete-btn');
                if (cards.length > 1) {
                    deleteBtn.classList.remove('hidden');
                } else {
                    deleteBtn.classList.add('hidden');
                }
            });
            document.getElementById('count-info').innerText = cards.length + ' calon peserta magang ditambahkan';
        }

        function addIntern() {
            const container = document.getElementById('intern-container');
            const firstCard = container.querySelector('.intern-card');

            const newCard = firstCard.cloneNode(true);
            newCard.querySelectorAll('input').forEach(input => input.value = '');
            newCard.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            container.appendChild(newCard);
            count++;
            updateTitles();
        }

        function removeIntern(button) {
            const card = button.closest('.intern-card');
            const container = document.getElementById('intern-container');

            if (container.querySelectorAll('.intern-card').length > 1) {
                card.remove();
                updateTitles();
            } else {
                alert('Minimal harus ada 1 peserta');
            }
        }

        // ── Confirmation modal ──
        const submitModal   = document.getElementById('submit-confirm-modal');
        const pengajuanForm = document.getElementById('pengajuan-form');

        function formatDate(val) {
            if (!val) return '–';
            const [y, m, d] = val.split('-');
            return d + '/' + m + '/' + y;
        }

        document.getElementById('btn-open-submit-modal').addEventListener('click', function () {
            if (!pengajuanForm.reportValidity()) return;

            const noSurat   = document.getElementById('no_surat').value || '–';
            const startDate = document.getElementById('start_date').value;
            const endDate   = document.getElementById('end_date').value;
            const keperluan = document.getElementById('keperluan').value || '–';
            const jumlah    = document.querySelectorAll('.intern-card').length;

            document.getElementById('modal-no-surat').textContent  = noSurat;
            document.getElementById('modal-periode').textContent   = formatDate(startDate) + ' – ' + formatDate(endDate);
            document.getElementById('modal-keperluan').textContent = keperluan;
            document.getElementById('modal-peserta').textContent   = jumlah + ' orang';

            submitModal.style.display = 'flex';
        });

        document.getElementById('btn-submit-cancel').addEventListener('click', function () {
            submitModal.style.display = 'none';
        });

        document.getElementById('btn-submit-confirm').addEventListener('click', function () {
            submitModal.style.display = 'none';
            pengajuanForm.submit();
        });

        submitModal.addEventListener('click', function (e) {
            if (e.target === submitModal) submitModal.style.display = 'none';
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && submitModal.style.display === 'flex') {
                submitModal.style.display = 'none';
            }
        });
    </script>
@endsection