@extends('layouts.app')

@section('title', 'Edit Profile - Sistem Manajemen Magang')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    *, body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }

    .edit-bg { min-height: 100vh; background: #f1f5ff; }
    .hero-header { background: linear-gradient(110deg, #0f2878 0%, #2d3ecb 55%, #4f46e5 100%); border-radius: 20px; position: relative; overflow: hidden; margin-bottom: 24px; }
    .hero-header::before { content: ''; position: absolute; top: -80px; right: -60px; width: 260px; height: 260px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none; }
    .hero-header::after { content: ''; position: absolute; bottom: -100px; left: 25%; width: 320px; height: 320px; background: rgba(255,255,255,0.04); border-radius: 50%; pointer-events: none; }

    .panel { background: #fff; border-radius: 20px; box-shadow: 0 1px 3px rgba(20,40,120,0.06), 0 4px 18px rgba(20,40,120,0.06); overflow: hidden; margin-bottom: 24px; transition: transform .2s ease, box-shadow .2s ease; }
    .panel:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(20,40,120,0.12); }
    .panel-header { background: linear-gradient(100deg, #1e3a8a 0%, #3b4fd8 100%); padding: 16px 22px; display: flex; align-items: center; gap: 10px; }
    .panel-header h2 { color: #fff; font-size: 15px; font-weight: 700; letter-spacing: 0.01em; margin: 0; }
    .panel-body { padding: 20px 22px; }

    .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3); border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all .2s ease; cursor: pointer; }
    .btn-back:hover { background: rgba(255,255,255,0.3); border-color: rgba(255,255,255,0.5); transform: translateX(-3px); }

    .photo-section { display: flex; gap: 16px; align-items: flex-start; padding: 16px; background: #f9fafb; border-radius: 14px; margin-bottom: 20px; }
    .photo-preview { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 2px solid #e5e7eb; flex-shrink: 0; background: #f3f4f6; display: flex; align-items: center; justify-content: center; }
    .file-input-label { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: linear-gradient(135deg, #3b82f6, #6366f1); color: #fff; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; }
    #photo { display: none; }

    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
    .form-control { width: 100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 13px; }
    .form-control:focus { outline: none; border-color: #3b4fd8; box-shadow: 0 0 0 3px rgba(59,79,216,0.1); }

    .btn-group { display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border: none; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; }
    .btn-primary { background: linear-gradient(135deg, #3b4fd8, #3b82f6); color: #fff; }
    .btn-secondary { background: #e5e7eb; color: #374151; }

    .modal-overlay { display: none; position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #fff; border-radius: 16px; padding: 24px; max-width: 400px; width: 90%; }
</style>
@endpush

@section('content')
<div class="edit-bg py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ── HERO HEADER ── --}}
        <div class="hero-header shadow-xl">
            <div class="relative z-10 px-6 py-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">Edit Profile</h1>
                    <p class="text-blue-200 text-sm">Perbarui informasi profilmu</p>
                </div>
                <a href="{{ route('intern.profile.show') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <form id="profile-form" method="POST" action="{{ route('intern.profile.update') }}" enctype="multipart/form-data">
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
                        @if($intern->photo_path)
                            <img src="{{ url('storage/' . $intern->photo_path) }}" alt="Current Photo" class="photo-preview" id="photo-preview">
                        @else
                            <div class="photo-preview" id="photo-preview-placeholder">
                                <i class="fas fa-user text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                        <div class="photo-upload">
                            <label class="form-label">Unggah Foto Baru</label>
                            <div class="file-input-wrapper">
                                <label for="photo" class="file-input-label"><i class="fas fa-cloud-upload-alt"></i> Pilih Foto</label>
                                <input type="file" name="photo" id="photo" accept="image/*">
                            </div>
                            <p class="form-help">Hanya JPG/PNG. Maksimal 2MB.</p>
                            @error('photo')
                                <div class="form-error mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-times-circle mr-2"></i>{{ $message }}</div>
                            @enderror
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
                        <div>
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') error @enderror" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap">
                            @error('name')<div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') error @enderror" value="{{ old('email', $user->email) }}" placeholder="Masukkan email">
                            @error('email')<div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label for="phone">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') error @enderror" value="{{ old('phone', $intern->phone) }}" placeholder="Masukkan nomor telepon">
                            @error('phone')<div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
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
                    <div class="note-box">Kosongkan jika tidak ingin mengubah password</div>
                    <div class="form-grid">
                        <div>
                            <label for="current_password">Password Lama</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') error @enderror">
                            @error('current_password')<div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') error @enderror">
                            @error('password')<div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="btn-group">
                <a href="{{ route('intern.profile.show') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="button" id="btn-submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

{{-- CONFIRMATION MODAL --}}
<div id="confirm-modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-question-circle"></i></div>
        <h3 class="modal-title">Simpan Perubahan?</h3>
        <p class="modal-text">Apakah Anda yakin ingin menyimpan semua perubahan pada profil Anda?</p>
        <div class="modal-actions">
            <button id="btn-cancel" type="button" class="btn btn-secondary" style="flex:1"><i class="fas fa-times"></i> Tidak</button>
            <button id="btn-confirm" type="button" class="btn btn-primary" style="flex:1"><i class="fas fa-check"></i> Ya, Simpan</button>
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
                    photoPlaceholder.replaceWith(img);
                } else {
                    photoPreview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('btn-submit').addEventListener('click', function() {
        modal.classList.add('active');
    });
    document.getElementById('btn-cancel').addEventListener('click', function() { modal.classList.remove('active'); });
    document.getElementById('btn-confirm').addEventListener('click', function() { modal.classList.remove('active'); form.submit(); });
    modal.addEventListener('click', function(e) { if (e.target === modal) modal.classList.remove('active'); });
});
</script>
@endpush

@endsection