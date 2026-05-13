@extends('layouts.app')

@section('title', 'Edit Akun Admin - Sistem Manajemen Magang')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .dash-bg {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%);
        }

        .hero-strip {
            background: linear-gradient(100deg, #1e3a8a 0%, #3b4fd8 50%, #4f46e5 100%);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(20, 40, 120, 0.16);
            color: #fff;
        }

        .hero-strip::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .hero-strip::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: 30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .panel {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(30, 58, 138, 0.06), 0 4px 20px rgba(30, 58, 138, 0.06);
            overflow: hidden;
        }

        .input-main {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border: 1px solid #d1d5db;
            border-radius: 0.6rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: all .15s ease;
            font-size: 14px;
            color: #111827;
            background: #fff;
        }

        .input-main::placeholder { color: #9ca3af; }

        .input-main:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .input-main:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }

        select.input-main { cursor: pointer; }

        .field-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .field-label i { color: #93c5fd; font-size: 12px; }

        .field-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .form-divider {
            height: 1px;
            background: linear-gradient(to right, #e0e7ff, transparent);
            margin: 8px 0;
        }

        .super-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #fef2f2;
            color: #b91c1c;
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 10px;
            border: 1.5px solid #e0e7ff;
            background: #f8faff;
            color: #6b7280;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            border-color: #c7d2fe;
            background: #eff2ff;
            color: #3730a3;
            text-decoration: none;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(to right, #2563eb, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-save:hover {
            box-shadow: 0 6px 16px rgba(59, 79, 216, 0.3);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 1.6rem; }
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">

            {{-- ── HERO STRIP ── --}}
            <div class="hero-strip mb-6">
                <div class="relative z-10 px-6 py-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="hero-title text-4xl font-bold mb-1">Edit Akun Admin</h1>
                        <p class="text-blue-100 text-sm">{{ $user->email }}</p>
                        <div class="mt-2">
                            @if ($user->isSuperAdmin())
                                <span class="super-badge">
                                    <i class="fas fa-shield-alt text-xs"></i> Super Admin
                                </span>
                            @else
                                <span class="text-blue-200 text-sm">
                                    <i class="fas fa-user-cog mr-1"></i>
                                    {{ $user->getRoleNames()->first() ? str_replace('_', ' ', ucwords($user->getRoleNames()->first(), '_')) : 'Admin' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── FORM PANEL ── --}}
            <div class="panel">

                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-pen mr-3"></i>Informasi Akun
                    </h2>
                </div>

                <form action="{{ route('admin.accounts.update', $user) }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Data Utama --}}
                    <p class="section-label">Data Utama</p>

                    <div>
                        <label class="field-label">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="input-main" required placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">
                            <i class="fas fa-envelope"></i> Alamat Email
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="input-main" required placeholder="contoh@email.com">
                        @error('email')
                            <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label">
                            <i class="fas fa-id-badge"></i> Akses Admin
                        </label>
                        @if ($user->isSuperAdmin())
                            <input type="text" value="Super Admin" class="input-main" disabled>
                            <input type="hidden" name="role" value="super_admin">
                            <p class="field-hint"><i class="fas fa-lock mr-1"></i>Akses Super Admin tidak dapat diubah.</p>
                        @else
                            <select name="role" class="input-main" required>
                                <option value="">Pilih Akses...</option>
                                @foreach ($roleOptions as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('role', $user->role ?? $user->getRoleNames()->first()) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        @error('role')
                            <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="form-divider"></div>

                    {{-- Password --}}
                    <p class="section-label">Ubah Password</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="field-label">
                                <i class="fas fa-lock"></i> Password Baru
                            </label>
                            <input type="password" name="password" class="input-main"
                                placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                                <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="field-label">
                                <i class="fas fa-lock"></i> Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" class="input-main"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <p class="field-hint">
                        <i class="fas fa-info-circle mr-1"></i>
                        Biarkan kosong jika tidak ingin mengubah password.
                    </p>

                    <div class="form-divider"></div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.accounts.index') }}" class="btn-cancel">
                            <i class="fas fa-arrow-left text-xs"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save text-xs"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
@endsection