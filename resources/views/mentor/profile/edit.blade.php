@extends('layouts.app')

@section('title', 'Edit Profile - Sistem Manajemen Magang')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    *, body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }

    /* ── Page background ── */
    .edit-bg {
        min-height: 100vh;
        background: #f1f5ff;
    }

    /* ── Header strip ── */
    .hero-header {
        background: linear-gradient(110deg, #0f2878 0%, #2d3ecb 55%, #4f46e5 100%);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .hero-header::before {
        content: '';
        position: absolute;
        top: -80px; right: -60px;
        width: 260px; height: 260px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-header::after {
        content: '';
        position: absolute;
        bottom: -100px; left: 25%;
        width: 320px; height: 320px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
        pointer-events: none;
    }

    /* ── Panel ── */
    .panel {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(20,40,120,0.06), 0 4px 18px rgba(20,40,120,0.06);
        overflow: hidden;
        margin-bottom: 24px;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .panel:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(20,40,120,0.12); }

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
    .panel-body { padding: 20px 22px; }

    /* ── Back button ── */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s ease;
        cursor: pointer;
    }
    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
        transform: translateX(-3px);
    }

    /* ── Alert messages ── */
    .alert {
        padding: 14px 18px;
        border-radius: 14px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        animation: slideDown .3s ease;
    }
    .alert-success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #15803d;
    }
    .alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }
    .alert i { margin-top: 2px; flex-shrink: 0; }
    .alert ul { margin: 0; padding-left: 20px; }
    .alert li { font-size: 13px; margin: 4px 0; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Form elements ── */
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        letter-spacing: 0.01em;
    }
    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        font-size: 13px;
        font-family: inherit;
        transition: all .2s ease;
        background: #fff;
    }
    .form-control:focus {
        outline: none;
        border-color: #3b4fd8;
        box-shadow: 0 0 0 3px rgba(59,79,216,0.1);
    }
    .form-control.error {
        border-color: #ef4444;
    }
    .form-control.error:focus {
        box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
    }
    .form-error {
        color: #b91c1c;
        font-size: 12px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-help {
        color: #6b7280;
        font-size: 12px;
        margin-top: 6px;
    }

    /* ── Photo upload section ── */
    .photo-section {
        display: flex;
        gap: 16px;
        align-items: flex-start;
        padding: 16px;
        background: #f9fafb;
        border-radius: 14px;
        margin-bottom: 20px;
    }
    .photo-preview {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .photo-upload {
        flex: 1;
    }
    .file-input-wrapper {
        position: relative;
        display: inline-block;
    }
    .file-input-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #fff;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s ease;
    }
    .file-input-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59,79,216,0.3);
    }
    #photo {
        display: none;
    }

    /* ── Grid layout ── */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
    }

    /* ── Buttons ── */
    .btn-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 28px;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
        text-decoration: none;
    }
    .btn-primary {
        background: linear-gradient(135deg, #3b4fd8, #3b82f6);
        color: #fff;
        box-shadow: 0 3px 12px rgba(59,79,216,0.3);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59,79,216,0.4);
    }
    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }
    .btn-secondary:hover {
        background: #d1d5db;
    }
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    /* ── Modal ── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 50;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        animation: fadeIn .2s ease;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-box {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp .3s ease;
    }
    .modal-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #dbeafe;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 28px;
        color: #3b82f6;
    }
    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        text-align: center;
        margin-bottom: 8px;
    }
    .modal-text {
        font-size: 14px;
        color: #6b7280;
        text-align: center;
        margin-bottom: 20px;
    }
    .modal-actions {
        display: flex;
        gap: 12px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Password section note ── */
    .note-box {
        padding: 12px 14px;
        background: #f0f9ff;
        border-left: 3px solid #3b82f6;
        border-radius: 8px;
        font-size: 12px;
        color: #1e40af;
        margin-bottom: 16px;
    }

    /* ── Loading state ── */
    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="edit-bg py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ── HERO HEADER ──────────────────────────────── --}}
        <div class="hero-header shadow-xl">
            <div class="relative z-10 px-6 py-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">Edit Profile</h1>
                    <p class="text-blue-200 text-sm">Perbarui informasi profil Anda dengan data yang terbaru</p>
                </div>
                <a href="{{ route('mentor.profile.show') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- ── SUCCESS MESSAGE ──────────────────────────── --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle text-lg"></i>
                <div>
                    <p class="font-semibold text-sm mb-1">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- ── ERROR MESSAGES ──────────────────────────── --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle text-lg"></i>
                <div>
                    <p class="font-semibold text-sm mb-2">Terjadi kesalahan:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ── FORM ──────────────────────────────────── --}}
        <form id="profile-form" method="POST" action="{{ route('mentor.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- PHOTO SECTION --}}
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-camera text-blue-200"></i>
                    <h2>Foto Profil</h2>
                </div>
                <div class="panel-body">
                    <div class="photo-section">
                        @if ($mentor->photo_path)
                            <img src="{{ asset('storage/' . $mentor->photo_path) }}" alt="Current Photo"
                                class="photo-preview" id="photo-preview" style="width: 80px; height: 80px; border-radius: 12px;">
                        @else
                            <div class="photo-preview" id="photo-preview-placeholder">
                                <i class="fas fa-user text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                        <div class="photo-upload">
                            <label for="photo" class="form-label">Unggah Foto Baru</label>
                            <div class="file-input-wrapper">
                                <label for="photo" class="file-input-label">
                                    <i class="fas fa-cloud-upload-alt"></i> Pilih Foto
                                </label>
                                <input type="file" name="photo" id="photo" accept="image/*">
                            </div>
                            <p class="form-help">JPG, PNG, atau GIF. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PERSONAL INFO SECTION --}}
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-user text-blue-200"></i>
                    <h2>Informasi Pribadi</h2>
                </div>
                <div class="panel-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') error @enderror"
                                value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') error @enderror"
                                value="{{ old('email', $user->email) }}" placeholder="Masukkan email">
                            @error('email')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') error @enderror"
                                value="{{ old('phone', $mentor->phone) }}" placeholder="Masukkan nomor telepon">
                            @error('phone')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- PASSWORD SECTION --}}
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-lock text-blue-200"></i>
                    <h2>Ubah Password</h2>
                </div>
                <div class="panel-body">
                    <div class="note-box">
                        <i class="fas fa-info-circle mr-2"></i>
                        Kosongkan semua field password jika tidak ingin mengubahnya.
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="current_password">Password Lama</label>
                            <input type="password" name="current_password" id="current_password"
                                class="form-control @error('current_password') error @enderror">
                            @error('current_password')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') error @enderror">
                            @error('password')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="btn-group">
                <a href="{{ route('mentor.profile.show') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="button" id="btn-submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

{{-- ── CONFIRMATION MODAL ────────────────────────── --}}
<div id="confirm-modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        <h3 class="modal-title">Simpan Perubahan?</h3>
        <p class="modal-text">Apakah Anda yakin ingin menyimpan semua perubahan pada profil Anda?</p>
        <div class="modal-actions">
            <button type="button" id="btn-cancel" class="btn btn-secondary" style="flex: 1;">
                <i class="fas fa-times"></i> Tidak
            </button>
            <button type="button" id="btn-confirm" class="btn btn-primary" style="flex: 1;">
                <i class="fas fa-check"></i> Ya, Simpan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('confirm-modal');
    const form = document.getElementById('profile-form');
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const photoPlaceholder = document.getElementById('photo-preview-placeholder');

    // ── Photo preview ──
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (!photoPreview) {
                    const img = document.createElement('img');
                    img.id = 'photo-preview';
                    img.src = e.target.result;
                    img.className = 'photo-preview';
                    img.style.cssText = 'width: 80px; height: 80px; border-radius: 12px; border: 2px solid #e5e7eb;';
                    photoPlaceholder.replaceWith(img);
                } else {
                    photoPreview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // ── Confirmation modal ──
    document.getElementById('btn-submit').addEventListener('click', function() {
        modal.classList.add('active');
    });

    document.getElementById('btn-cancel').addEventListener('click', function() {
        modal.classList.remove('active');
    });

    document.getElementById('btn-confirm').addEventListener('click', function() {
        modal.classList.remove('active');
        form.submit();
    });

    // Close modal if clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
});
</script>
@endpush

@endsection
