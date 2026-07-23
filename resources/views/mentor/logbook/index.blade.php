@extends('layouts.app')

@section('title', 'Logbook Anak Bimbingan - Sistem Magang')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

        *, body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .mono { font-family: 'DM Mono', monospace; }
        .dash-bg { min-height: 100vh; background: #f4f7fe; }

        /* ── Header strip modern ── */
        .hero-strip {
            background: linear-gradient(135deg, #0f2878 0%, #2d3ecb 50%, #4f46e5 100%);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            margin-bottom: 28px;
            box-shadow: 0 10px 25px -5px rgba(45, 62, 203, 0.25);
        }

        .hero-strip::before {
            content: ''; position: absolute; top: -80px; right: -60px;
            width: 260px; height: 260px; background: rgba(255, 255, 255, 0.06);
            border-radius: 50%; pointer-events: none;
        }

        .hero-strip::after {
            content: ''; position: absolute; bottom: -100px; left: 20%;
            width: 320px; height: 320px; background: rgba(255, 255, 255, 0.04);
            border-radius: 50%; pointer-events: none;
        }

        /* ── Panel Styling ── */
        .panel {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .panel-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b4fd8 100%);
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .panel-header h2 {
            color: #fff; font-size: 15px; font-weight: 700;
            letter-spacing: 0.01em; margin: 0;
            display: flex; align-items: center; gap: 10px;
        }

        .panel-body { padding: 24px; }

        /* ── Filter Form Styling ── */
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            align-items: flex-end;
        }

        .filter-group { display: flex; flex-direction: column; gap: 8px; }

        .filter-label {
            font-size: 11px; font-weight: 700; color: #475569;
            text-transform: uppercase; letter-spacing: 0.06em;
            display: flex; align-items: center; gap: 6px;
        }

        .filter-input, .filter-select {
            padding: 11px 14px; border: 1.5px solid #e2e8f0;
            border-radius: 12px; font-size: 13px; font-family: inherit;
            background: #f8fafc; transition: all .2s ease; color: #1e293b;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none; border-color: #3b4fd8; background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 79, 216, 0.1);
        }

        .filter-actions { display: flex; gap: 8px; }

        .btn-filter {
            padding: 11px 18px; background: linear-gradient(135deg, #3b4fd8, #3b82f6);
            color: #fff; border: none; border-radius: 12px; font-size: 13px;
            font-weight: 700; cursor: pointer; transition: all .2s ease;
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            box-shadow: 0 4px 12px rgba(59, 79, 216, 0.2);
            flex: 1;
        }
        .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(59, 79, 216, 0.3); }

        .btn-clear {
            padding: 11px 16px; background: #f1f5f9; color: #64748b;
            border: none; border-radius: 12px; font-weight: 700; cursor: pointer;
            transition: all .2s ease; display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-clear:hover { background: #e2e8f0; color: #1e293b; }

        /* ── Tombol Bulk Approve Premium ── */
        .btn-bulk-approve {
            padding: 10px 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff; border: none; border-radius: 12px;
            font-size: 13px; font-weight: 700; cursor: pointer;
            transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }
        .btn-bulk-approve:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
        }
        .btn-bulk-approve:disabled {
            background: rgba(255, 255, 255, 0.15); color: rgba(255, 255, 255, 0.4);
            cursor: not-allowed; box-shadow: none; border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ── Custom Checkbox ── */
        .custom-checkbox {
            width: 18px; height: 18px; accent-color: #10b981;
            cursor: pointer; vertical-align: middle; border-radius: 4px;
        }
        .custom-checkbox:disabled { cursor: not-allowed; opacity: 0.3; }

        /* ── Table Styling ── */
        .table-wrapper {
            overflow-x: auto; border-radius: 14px; border: 1px solid #edf2f7; background: #fff;
        }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafc; }

        thead th {
            padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700;
            color: #475569; text-transform: uppercase; letter-spacing: 0.08em;
            border-bottom: 2px solid #e2e8f0; white-space: nowrap;
        }

        tbody td {
            padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
            font-size: 13px; color: #334155; vertical-align: middle;
        }
        tbody tr:hover { background: #f8fafc; }

        /* ── Komponen Tabel ── */
        .avatar-cell { display: flex; align-items: center; gap: 10px; }
        .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            object-fit: cover; border: 2px solid #e2e8f0; flex-shrink: 0;
        }
        .activity-text {
            color: #64748b; font-size: 13px; line-height: 1.55; word-break: break-word;
        }
        .photo-thumbnail {
            width: 40px; height: 40px; border-radius: 10px; object-fit: cover;
            border: 2px solid #e0e7ff; cursor: pointer; transition: all .2s ease; flex-shrink: 0;
        }
        .photo-thumbnail:hover { border-color: #3b82f6; transform: scale(1.08); }

        .btn-action {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; background: #e0e7ff; color: #3b82f6;
            border: none; border-radius: 9px; font-size: 12px; font-weight: 700;
            cursor: pointer; text-decoration: none; transition: all .2s ease; white-space: nowrap;
        }
        .btn-action:hover { background: #3b82f6; color: #fff; }

        .btn-approved {
            padding: 7px 14px; border-radius: 9px; background: #dcfce7;
            color: #15803d; font-weight: 700; font-size: 12px;
            display: inline-flex; align-items: center; gap: 6px;
        }

        .empty-state { text-align: center; padding: 56px 20px; color: #94a3b8; }
        .empty-state i { font-size: 48px; margin-bottom: 12px; opacity: 0.5; color: #cbd5e1; }

        /* ── Mobile Cards View (Dipercantik) ── */
        @media (max-width: 768px) {
            .table-wrapper { display: none; }
            .logbook-cards { display: grid; grid-template-columns: 1fr; gap: 16px; }
            .logbook-card {
                background: #fff; border: 1px solid #e2e8f0;
                border-radius: 16px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            }
            .card-header {
                display: flex; align-items: center; justify-content: space-between;
                margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;
            }
            .card-date { font-size: 12px; font-weight: 700; color: #3b82f6; }
            .card-intern { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
            .card-intern-avatar { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
            .card-intern-name { font-weight: 700; font-size: 14px; color: #1e293b; }
            .card-activity { margin-bottom: 14px; background: #f8fafc; padding: 12px; border-radius: 10px; }
            .card-activity-label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px; display: block; }
            .card-activity-text { font-size: 13px; color: #334151; line-height: 1.5; }
            .card-actions { display: flex; gap: 8px; padding-top: 10px; border-top: 1px solid #f1f5f9; align-items: center; justify-content: space-between; }
        }

        @media (min-width: 769px) {
            .logbook-cards { display: none; }
        }
    </style>
@endpush

@section('content')
    <div class="dash-bg py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── HERO HEADER ──────────────────────────────── --}}
            <div class="hero-strip shadow-md">
                <div class="relative z-10 px-8 py-8">
                    <h1 class="text-2xl font-extrabold text-white mb-1">Logbook Anak Bimbingan</h1>
                    <p class="text-blue-100 text-sm">Pantau dan kelola catatan harian aktivitas anak magang Anda secara efisien.</p>
                </div>
            </div>

            {{-- ── FILTER PANEL ────────────────────────────── --}}
            <div class="panel">
                <div class="panel-header">
                    <h2><i class="fas fa-filter text-blue-200"></i> Filter Data</h2>
                </div>
                <div class="panel-body">
                    <form method="GET" action="{{ route('mentor.logbook.index') }}">
                        <div class="filter-grid">
                            <div class="filter-group">
                                <label class="filter-label"><i class="fas fa-user text-blue-600"></i> Anak Magang</label>
                                <select name="intern_id" class="filter-select">
                                    <option value="">Semua Anak Magang</option>
                                    @foreach ($interns as $intern)
                                        <option value="{{ $intern->id }}" @selected(request('intern_id') == $intern->id)>
                                            {{ $intern->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label"><i class="fas fa-calendar-alt text-blue-600"></i> Dari Tanggal</label>
                                <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
                            </div>

                            <div class="filter-group">
                                <label class="filter-label"><i class="fas fa-calendar-check text-blue-600"></i> Hingga Tanggal</label>
                                <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Terapkan</button>
                                @if (request()->anyFilled(['intern_id', 'date_from', 'date_to']))
                                    <a href="{{ route('mentor.logbook.index') }}" class="btn-clear" title="Reset Filter"><i class="fas fa-times"></i></a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── LOGBOOK TABLE FORM (BULK APPROVE) ───────────── --}}
            <form method="POST" action="{{ route('mentor.logbook.bulk-approve') }}">
                @csrf

                <div class="panel">
                    <div class="panel-header">
                        <h2><i class="fas fa-book text-blue-200"></i> Daftar Logbook Harian</h2>
                        
                        {{-- Tombol Setujui Terpilih --}}
                        <button type="submit" id="btnBulkApprove" class="btn-bulk-approve" disabled onclick="return confirm('Apakah Anda yakin ingin menyetujui logbook yang dipilih?')">
                            <i class="fas fa-check-double"></i> Setujui Terpilih (<span id="selectedCount">0</span>)
                        </button>
                    </div>

                    <div class="panel-body">
                        {{-- Desktop Table View --}}
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 50px; text-align: center;">
                                            <input type="checkbox" id="selectAll" class="custom-checkbox" title="Pilih Semua yang Belum Disetujui">
                                        </th>
                                        <th style="width: 130px;">Tanggal</th>
                                        <th style="width: 190px;">Nama</th>
                                        <th>Aktivitas</th>
                                        <th style="width: 90px; text-align: center;">Foto</th>
                                        <th style="width: 150px; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logbooks as $l)
                                        <tr>
                                            <td style="text-align: center;">
                                                <input type="checkbox" name="logbooks[]" value="{{ $l->id }}" 
                                                    class="custom-checkbox logbook-checkbox desktop-cb"
                                                    {{ $l->approval_status === 'approved' ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <span style="font-size: 13px; font-weight: 600; color: #475569;">
                                                    {{ \Carbon\Carbon::parse($l->date)->translatedFormat('d M Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="avatar-cell">
                                                    @if ($l->intern->photo_path)
                                                        <img src="{{ url('storage/' . $l->intern->photo_path) }}" alt="{{ $l->intern->name }}" class="avatar">
                                                    @else
                                                        <div class="avatar" style="background: linear-gradient(135deg,#3b82f6,#6366f1); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:12px;">
                                                            {{ strtoupper(substr($l->intern->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <span style="font-weight: 700; font-size: 13px; color: #1e293b;">{{ $l->intern->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="activity-text" title="{{ $l->activity }}">
                                                    {{ $l->activity }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($l->photo_path)
                                                    @php
                                                        $photoUrl = URL::temporarySignedRoute(
                                                            'mentor.logbook.photo',
                                                            now()->addMinutes(5),
                                                            ['filename' => basename($l->photo_path)]
                                                        );
                                                    @endphp
                                                    @can('view', $l)
                                                        <img src="{{ $photoUrl }}" alt="Logbook Photo" class="photo-thumbnail" onclick="window.open('{{ $photoUrl }}', '_blank')" title="Klik untuk melihat full size">
                                                    @else
                                                        <span style="color:#cbd5e1; font-size:12px;">Tanpa Akses</span>
                                                    @endcan
                                                @else
                                                    <span style="color:#cbd5e1; font-size:12px;">—</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="action-inline" style="justify-content: center; display:flex; gap: 8px; align-items:center;">
                                                    <a href="{{ route('mentor.logbook.show', $l) }}" class="btn-action" title="Detail">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>

                                                    @if ($l->approval_status === 'approved')
                                                        <span class="btn-approved" title="Sudah disetujui">
                                                            <i class="fas fa-check"></i> Disetujui
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <i class="fas fa-folder-open"></i>
                                                    <p class="font-bold text-slate-700 text-sm">Tidak ada data logbook tersedia</p>
                                                    <p style="font-size:12px; color: #94a3b8; margin-top: 4px;">Coba ubah filter untuk melihat data lainnya</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards View --}}
                        <div class="logbook-cards">
                            @forelse($logbooks as $l)
                                <div class="logbook-card">
                                    <div class="card-header">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <input type="checkbox" name="logbooks[]" value="{{ $l->id }}" 
                                                class="custom-checkbox logbook-checkbox mobile-cb"
                                                {{ $l->approval_status === 'approved' ? 'disabled' : '' }}>
                                            <span class="card-date">{{ \Carbon\Carbon::parse($l->date)->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="card-intern">
                                        @if ($l->intern->photo_path)
                                            <img src="{{ url('storage/' . $l->intern->photo_path) }}" alt="{{ $l->intern->name }}" class="card-intern-avatar">
                                        @else
                                            <div class="card-intern-avatar" style="background: linear-gradient(135deg,#3b82f6,#6366f1); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:12px;">
                                                {{ strtoupper(substr($l->intern->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="card-intern-name">{{ $l->intern->name }}</span>
                                    </div>

                                    <div class="card-activity">
                                        <span class="card-activity-label">Aktivitas Harian</span>
                                        <div class="card-activity-text">{{ $l->activity }}</div>
                                    </div>

                                    <div class="card-actions">
                                        <a href="{{ route('mentor.logbook.show', $l) }}" class="btn-action">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>

                                        @if ($l->approval_status === 'approved')
                                            <span class="btn-approved">
                                                <i class="fas fa-check"></i> Disetujui
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state" style="padding: 30px 0;">
                                    <i class="fas fa-folder-open"></i>
                                    <p style="font-weight: 700; font-size: 14px; color: #475569; margin-top: 6px;">Tidak ada data logbook tersedia.</p>
                                </div>
                            @endforelse
                        </div>

                        @if (method_exists($logbooks, 'links'))
                            <div style="margin-top: 20px;">
                                {{ $logbooks->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAll');
        const btnBulkApprove = document.getElementById('btnBulkApprove');
        const selectedCount = document.getElementById('selectedCount');

        function updateBulkButton() {
            const activeCheckboxes = window.innerWidth > 768 
                ? document.querySelectorAll('.desktop-cb:checked') 
                : document.querySelectorAll('.mobile-cb:checked');

            const checkedIds = new Set();
            activeCheckboxes.forEach(cb => checkedIds.add(cb.value));

            const count = checkedIds.size;
            selectedCount.textContent = count;
            btnBulkApprove.disabled = count === 0;
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const isChecked = this.checked;
                const activeClass = window.innerWidth > 768 ? '.desktop-cb' : '.mobile-cb';
                
                document.querySelectorAll(`${activeClass}:not(:disabled)`).forEach(cb => {
                    cb.checked = isChecked;
                });
                updateBulkButton();
            });
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('logbook-checkbox')) {
                const val = e.target.value;
                const isChecked = e.target.checked;

                document.querySelectorAll(`.logbook-checkbox[value="${val}"]`).forEach(cb => {
                    cb.checked = isChecked;
                });

                if (!isChecked && selectAll) {
                    selectAll.checked = false;
                }

                updateBulkButton();
            }
        });
    });
</script>
@endpush