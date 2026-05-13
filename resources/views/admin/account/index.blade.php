@extends('layouts.app')

@section('title', 'Kelola Akun Admin - Sistem Manajemen Magang')

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
        }

        .input-main:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .icon-btn-edit {
            background: #eff6ff;
            color: #3b82f6;
        }

        .icon-btn-edit:hover {
            background: #dbeafe;
            color: #1d4ed8;
            transform: translateY(-1px);
        }

        .icon-btn-delete {
            background: #fff1f2;
            color: #ef4444;
            border: none;
            cursor: pointer;
        }

        .icon-btn-delete:hover {
            background: #fee2e2;
            color: #b91c1c;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.6rem;
            }

            .action-mobile {
                width: 100%;
                justify-content: center;
            }

            .table-responsive {
                overflow: auto;
            }
        }

        .table-min-w {
            min-width: 600px;
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- ── HERO STRIP ── --}}
            <div class="hero-strip mb-6">
                <div class="relative z-10 px-6 py-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="hero-title text-4xl font-bold mb-1">Kelola Akun Admin</h1>
                        <p class="text-blue-100">Atur super admin dan admin lain sesuai akses yang dibutuhkan.</p>
                    </div>
                    <div class="flex-shrink-0 flex flex-col items-start sm:items-end gap-3">
                        <div>
                            <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Total Admin</p>
                            <p class="font-extrabold text-white text-center text-4xl leading-none">{{ $accounts->total() }}</p>
                        </div>
                        <a href="{{ route('admin.accounts.create') }}"
                            class="inline-flex items-center gap-2 bg-white text-blue-700 font-bold text-sm px-5 py-2.5 rounded-xl shadow hover:shadow-md hover:bg-blue-50 transition-all duration-200">
                            <i class="fas fa-plus text-xs"></i>
                            Tambah Admin
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── SEARCH FILTER PANEL ── --}}
            <div class="panel mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-search mr-3"></i>Cari Akun Admin
                    </h2>
                </div>
                <form method="GET" action="{{ route('admin.accounts.index') }}"
                    class="p-6 flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 sm:max-w-md relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="input-main pl-9">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="action-mobile bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        @if (request()->filled('search'))
                            <a href="{{ route('admin.accounts.index') }}"
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ── ACCOUNTS TABLE PANEL ── --}}
            <div class="panel overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-user-shield mr-3"></i>Daftar Akun Admin
                    </h2>
                </div>
                <div class="p-6">
                    <div class="table-responsive max-h-[560px]">
                        <table class="min-w-full table-min-w divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-blue-50">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg">
                                        Nama</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">
                                        Akses</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($accounts as $account)
                                    @php
                                        $roleName = $account->getRoleNames()->first() ?? ($account->role ?? '-');
                                        $roleBadgeMap = [
                                            'super_admin' => [
                                                'label'   => 'Super Admin',
                                                'classes' => 'background:#fef2f2;color:#b91c1c;',
                                                'dot'     => '#ef4444',
                                            ],
                                            'admin_full' => [
                                                'label'   => 'Admin Akses Penuh',
                                                'classes' => 'background:#eff6ff;color:#1d4ed8;',
                                                'dot'     => '#3b82f6',
                                            ],
                                            'admin_user_manager' => [
                                                'label'   => 'Admin Pengelola Pengguna',
                                                'classes' => 'background:#f0fdf4;color:#15803d;',
                                                'dot'     => '#22c55e',
                                            ],
                                            'admin_data_manager' => [
                                                'label'   => 'Admin Pengelola Data',
                                                'classes' => 'background:#fefce8;color:#a16207;',
                                                'dot'     => '#f59e0b',
                                            ],
                                        ];
                                        $roleBadge = $roleBadgeMap[$roleName] ?? [
                                            'label'   => str_replace('_', ' ', ucwords($roleName, '_')),
                                            'classes' => 'background:#f1f5f9;color:#475569;',
                                            'dot'     => '#94a3b8',
                                        ];
                                    @endphp
                                    <tr class="hover:bg-blue-50 transition-colors duration-150">

                                        {{-- Nama --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#818cf8);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                    <i class="fas fa-user text-white" style="font-size:13px;"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $account->name }}</span>
                                            </div>
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ $account->email }}</div>
                                        </td>

                                        {{-- Role badge --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="role-badge" style="{{ $roleBadge['classes'] }}">
                                                <span style="width:6px;height:6px;border-radius:50%;background:{{ $roleBadge['dot'] }};display:inline-block;flex-shrink:0;"></span>
                                                {{ $roleBadge['label'] }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.accounts.edit', $account) }}"
                                                    class="icon-btn icon-btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if (!$account->isSuperAdmin())
                                                    <form action="{{ route('admin.accounts.destroy', $account) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Hapus akun admin ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="icon-btn icon-btn-delete" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-300 font-medium px-2">Terlindungi</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                                <p class="text-sm">Belum ada akun admin.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $accounts->links() }}</div>
                </div>
            </div>

        </div>
    </div>
@endsection