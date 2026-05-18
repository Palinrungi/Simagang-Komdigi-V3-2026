@extends('layouts.app')

@section('title', 'Mikro Skill - Sistem Magang')

@section('content')
@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    body.page-microskill { background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%); }

    /* Hero (logbook-like) */
    .hero-strip { background: linear-gradient(110deg, #0f2878 0%, #2d3ecb 55%, #4f46e5 100%); border-radius: 20px; position: relative; overflow: hidden; margin-bottom: 28px; }
    .hero-strip::before { content: ''; position: absolute; top: -80px; right: -60px; width: 260px; height: 260px; background: rgba(255,255,255,0.05); border-radius: 50%; pointer-events: none; }
    .hero-strip::after { content: ''; position: absolute; bottom: -100px; left: 25%; width: 320px; height: 320px; background: rgba(255,255,255,0.04); border-radius: 50%; pointer-events: none; }
    .hero-strip .hero-inner { position: relative; z-index: 10; padding: 2rem 1.5rem; }
    .hero-strip h1 { color: #fff; font-size: 1.75rem; font-weight: 700; margin-bottom: 4px; }
    .hero-strip p  { color: #c7d2fe; font-size: 14px; }
    .cta-btn { display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:linear-gradient(110deg,#1e3a8a,#3b4fd8);color:#fff;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s ease }
    .cta-btn:hover { box-shadow:0 6px 16px rgba(59,79,216,0.3); transform:translateY(-1px); color:#fff }
    .ms-search-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid rgba(15,23,42,0.06);
        border-radius: 999px;
        padding: 6px 8px 6px 12px;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
    }
    .ms-search-wrap input {
        background: transparent;
        border: none;
        outline: none;
        color: #0f172a;
        font-size: 14px;
        width: 240px;
    }
    .ms-search-wrap input::placeholder { color: #94a3b8; }
    .ms-search-btn {
        width: 36px; height: 36px;
        border-radius: 999px;
        background: linear-gradient(110deg,#1e3a8a,#3b4fd8);
        border: none;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        transition: transform .12s ease, box-shadow .12s ease;
    }
    .ms-search-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(59,79,216,0.18); }
    .ms-search-clear {
        width: 32px; height: 32px;
        border-radius: 999px;
        background: transparent;
        border: none;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
    }

    /* Inline search for table header (dark on light background) */
    .ms-search-inline { display: flex; align-items: center; gap: 8px; background: #f3f4f6; border: 1px solid #e6eef6; border-radius: 999px; padding: 6px 8px 6px 12px; }
    .ms-search-inline input { background: transparent; border: none; outline: none; color: #111827; font-size: 14px; width: 200px; }
    .ms-search-inline input::placeholder { color: #9ca3af; }
    .ms-search-inline .ms-search-btn { width:34px; height:34px; border-radius:50%; background:#fff; border:none; color:#1e3a8a }
    .ms-search-inline .ms-search-clear { width:34px; height:34px; border-radius:50%; background:transparent; border:none; color:#6b7280 }

    /* Section label */
    .ms-section-label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .06em;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: .75rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ms-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }

    /* Recommendation cards (horizontal carousel) */
    .ms-rec-carousel-wrap {
        margin-bottom: 1.75rem;
        padding: 0 8px;
    }
    .ms-rec-carousel {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x mandatory;
        padding: 12px 4px;
    }
    .ms-rec-carousel::-webkit-scrollbar { height: 8px; }
    .ms-rec-carousel::-webkit-scrollbar-thumb { background: rgba(15,23,42,0.06); border-radius: 999px; }
    .ms-rec-carousel .ms-rec-card { width: 320px; max-width: 320px; flex: 0 0 320px; scroll-snap-align: start; }
    .ms-rec-card-more { display:flex; align-items:center; justify-content:center; text-align:center; }
    .ms-rec-card-more .ms-more-cta { background: transparent; color: #1d4ed8; border: 1px dashed rgba(29,78,216,0.12); padding: 8px 12px; border-radius: 10px; font-weight: 700; text-decoration: none; }
    .ms-rec-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: border-color .15s, box-shadow .15s;
    }
    .ms-rec-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 4px 14px rgba(59,130,246,0.08);
    }
    .ms-rec-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 3px 10px;
        border-radius: 999px;
        width: fit-content;
    }
    .ms-rec-title {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        line-height: 1.45;
        flex: 1;
    }
    .ms-rec-title-link { color: inherit; text-decoration: none; display: block; }
    .ms-rec-title-link:hover { color: #2563eb; text-decoration: underline; }
    .ms-rec-link {
        font-size: 12px;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .ms-rec-link:hover { color: #2563eb; }
    .ms-rec-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding-top: 10px;
        border-top: 1px solid #f3f4f6;
    }
    .ms-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #1d4ed8;
        color: #fff;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        transition: background .15s;
        white-space: nowrap;
    }
    .ms-upload-btn:hover { background: #1e40af; color: #fff; }
    .ms-ext-link {
        font-size: 12px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .ms-ext-link:hover { color: #374151; }
    .ms-rec-empty {
        background: #fff;
        border: 1px dashed #d1d5db;
        border-radius: 14px;
        padding: 2rem;
        text-align: center;
        color: #9ca3af;
        font-size: 14px;
        grid-column: 1 / -1;
    }

    /* Data table card */
    .ms-table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
    }
    .ms-table-header {
        padding: .875rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ms-table-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }
    .ms-table-scroll {
        overflow-x: auto;
        max-height: 500px;
        overflow-y: auto;
    }
    .ms-table-scroll table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .ms-table-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: 10px 16px;
        border-bottom: 1px solid #f3f4f6;
        white-space: nowrap;
    }
    .ms-table-scroll tbody tr {
        border-bottom: 1px solid #f9fafb;
        transition: background .1s;
    }
    .ms-table-scroll tbody tr:last-child { border-bottom: none; }
    .ms-table-scroll tbody tr:hover { background: #f0f4ff; }
    .ms-table-scroll tbody td { padding: 11px 16px; color: #111827; vertical-align: middle; }

    /* Status pills */
    .ms-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
    }
    .ms-pill-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
    .ms-pill-approved  { background: #dcfce7; color: #15803d; }
    .ms-pill-pending   { background: #fef9c3; color: #a16207; }
    .ms-pill-rejected  { background: #fee2e2; color: #b91c1c; }
    .ms-pill-default   { background: #f3f4f6; color: #6b7280; }

    /* Thumbnail */
    .ms-thumb {
        width: 36px; height: 36px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        display: block;
        transition: border-color .15s;
    }
    .ms-thumb:hover { border-color: #93c5fd; }

    /* Action buttons */
    .ms-actions { display: flex; gap: 6px; }
    .ms-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px; height: 30px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: #fff;
        cursor: pointer;
        font-size: 13px;
        color: #6b7280;
        text-decoration: none;
        transition: background .1s, border-color .1s, color .1s;
    }
    .ms-action-btn:hover { background: #f3f4f6; border-color: #d1d5db; color: #374151; }
    .ms-action-btn.del:hover { color: #dc2626; border-color: #fca5a5; background: #fff5f5; }

    /* Empty state */
    .ms-empty-state {
        padding: 3rem 2rem;
        text-align: center;
        color: #9ca3af;
    }
    .ms-empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #d1d5db; }
    .ms-empty-state p { font-size: 15px; font-weight: 500; margin-bottom: 16px; }

    /* Add button */
    .ms-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        background: #1d4ed8;
        color: #fff;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        transition: background .15s;
    }
    .ms-add-btn:hover { background: #1e40af; color: #fff; }

    /* Mobile responsive tweaks */
    @media (max-width: 640px) {
        .hero-strip .hero-inner { padding: 1rem; }
        .hero-strip h1 { font-size: 1.25rem; }
        .hero-strip p { font-size: 12px; }
        .ms-rec-carousel { padding: 8px 0; gap: 10px; }
        .ms-rec-carousel .ms-rec-card { width: 220px; flex: 0 0 220px; }
        .ms-rec-carousel .ms-rec-card .ms-rec-title { font-size: 13px; }
        .ms-search-wrap { width: 100%; max-width: 320px; }
        .ms-search-wrap input { width: 120px; }
        .bg-blue-600 .flex.items-center.justify-between { flex-direction: column; align-items: flex-start; gap: 8px; }
        .bg-blue-600 .ms-search-wrap { margin-top: 6px; }
        .cta-btn { padding: 8px 12px; font-size: 13px; }
        .ms-rec-card { padding: 0.85rem 1rem; }
        .ms-table-scroll { overflow-x: auto; }
        .ms-table-scroll table { min-width: 700px; }
        .ms-thumb { width: 44px; height: 44px; }
    }
</style>
@endpush

<div class="py-8" style="min-height:100vh">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Flash errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border-t-4 border-red-500 text-red-700 px-6 py-4 mb-6 rounded-xl" role="alert">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-0.5"></i>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ── Hero ── --}}
        <div class="hero-strip shadow-xl a1 mb-6">
            <div class="hero-inner">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold leading-tight">Mikro Skill Saya</h1>
                        <p class="text-blue-200">Kelola aktifitas mikro skillmu</p>
                    </div>

                    <div></div>
                </div>
            </div>
        </div>

        {{-- ── Rekomendasi ── --}}
        <p class="ms-section-label">
            <i class="fas fa-lightbulb" style="color:#16a34a;font-size:12px"></i>
            Rekomendasi Mikro Skill
        </p>

        <div class="ms-rec-carousel-wrap">
            @if(!empty($recommendations) && count($recommendations) > 0)
                <div class="ms-rec-carousel">
                    @foreach($recommendations as $r)
                        <div class="ms-rec-card">
                            <div>
                                <span class="ms-rec-badge">
                                    <i class="fas fa-star" style="font-size:10px"></i> Tersedia
                                </span>
                            </div>
                            <p class="ms-rec-title">
                                <a href="{{ $r->link_micro }}" target="_blank" rel="noopener" class="ms-rec-title-link">{{ $r->judul_micro }}</a>
                            </p>
                            <a href="{{ $r->link_micro }}" target="_blank" rel="noopener" class="ms-rec-link">
                                <i class="fas fa-globe" style="font-size:13px"></i>
                                {{ parse_url($r->link_micro, PHP_URL_HOST) ?? $r->link_micro }}
                            </a>
                            {{-- <div class="ms-rec-footer">
                                <a href="{{ route('intern.microskill.create', ['title' => $r->judul_micro]) }}" class="ms-upload-btn">
                                    <i class="fas fa-upload" style="font-size:11px"></i> Upload bukti
                                </a>
                                <a href="{{ $r->link_micro }}" target="_blank" rel="noopener" class="ms-ext-link">
                                    <i class="fas fa-external-link-alt" style="font-size:11px"></i> Buka
                                </a>
                            </div> --}}
                        </div>
                    @endforeach

                    {{-- Kartu CTA: Lihat lebih banyak --}}
                    <div class="ms-rec-card ms-rec-card-more" aria-hidden="false">
                        <div>
                            <span class="ms-rec-badge" style="background:#eef2ff;color:#3730a3">
                                <i class="fas fa-ellipsis-h" style="font-size:11px"></i> Lebih banyak
                            </span>
                        </div>
                        {{-- <p class="ms-rec-title">Lihat lebih banyak rekomendasi</p> --}}
                        <a href="https://digitalent.komdigi.go.id/micro-skill" target="_blank" rel="noopener" class="ms-more-cta">
                            Buka Digitalent Micro-skill
                        </a>
                    </div>
                </div>
            @else
                <div class="ms-rec-empty">
                    <i class="fas fa-check-circle" style="font-size:2rem;color:#86efac;margin-bottom:8px;display:block"></i>
                    Semua mikro skill sudah dikerjakan. Kerja bagus! 🎉
                </div>
            @endif
        </div>

        {{-- ── Data Mikro Skill ── --}}

        <div class="bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-star mr-3"></i>
                        Mikro Skill Selesai
                    </h2>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('intern.microskill.index') }}" class="ms-search-wrap">
                            <i class="fas fa-search" style="color:rgba(13, 20, 152, 0.966);font-size:13px"></i>
                            <input id="q" name="q" value="{{ request('q') }}" type="text" placeholder="Cari judul mikro skill..." aria-label="Cari mikro skill" />
                            @if (request('q'))
                                <a href="{{ route('intern.microskill.index') }}" class="ms-search-clear" aria-label="Bersihkan pencarian">
                                    <i class="fas fa-times"></i>
                                </a>
                            @else
                                <button type="submit" class="ms-search-btn" aria-label="Cari">
                                    <i class="fas fa-arrow-right" style="font-size:13px"></i>
                                </button>
                            @endif
                        </form>

                        @if($cekaktif)
                            <a href="{{ route('intern.microskill.create') }}" class="cta-btn">
                                <i class="fas fa-upload"></i>
                                Upload Bukti
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    {{-- search & upload moved to hero header to avoid duplication --}}
                </div>
                <div class="overflow-x-auto overflow-y-auto max-h-[500px] scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-blue-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-blue-50">
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tl-lg">Judul</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Dikirim</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider">Bukti</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-blue-900 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($submissions as $s)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-left">
                                        <div class="flex items-center justify-start min-w-0">
                                            <span class="text-sm font-medium text-gray-900 truncate">{{ $s->title }}</span>
                                        </div>
                                    </td>
                                    {{-- status column removed --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                        @if ($s->submitted_at)
                                            <div class="flex items-center justify-center">
                                                {{ \Carbon\Carbon::parse($s->submitted_at)->setTimezone('Asia/Makassar')->format('d/m/Y') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($s->photo_path)
                                            <img src="{{ $s->photo_url }}" alt="Bukti" class="w-12 h-12 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:border-blue-400 transition-all shadow-sm mx-auto" onclick="window.open('{{ $s->photo_url }}', '_blank')" title="Klik untuk melihat full size">
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="flex space-x-2 justify-center">
                                            <a href="{{ route('intern.microskill.edit', $s->id) }}" class="inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-all duration-200" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { url: '{{ route('intern.microskill.destroy', $s->id) }}' } }))" class="inline-flex items-center justify-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-all duration-200" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                            <p class="text-lg font-medium">Belum ada pengumpulan.</p>
                                            <p class="text-sm mt-2">Mulai dengan membuat pengumpulan pertama Anda.</p>
                                            @if ($cekaktif)
                                                <a href="{{ route('intern.microskill.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-300">
                                                    <i class="fas fa-upload mr-2"></i>Upload Bukti
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($submissions->count() > 0)
                    <div class="mt-6">
                        {{ $submissions->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{ showDeleteModal: false, deleteUrl: '' }" @open-delete-modal.window="showDeleteModal = true; deleteUrl = $event.detail.url">
    <!-- Modal Backdrop -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900 bg-opacity-50 backdrop-blur-sm" x-transition.opacity>
        <!-- Modal Content -->
        <div @click.away="showDeleteModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all" x-show="showDeleteModal" x-transition.scale.origin.bottom>
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-center text-gray-600 mb-6">Apakah Anda yakin ingin menghapus mikro skill ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-3">
                <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Batal
                </button>
                <form :action="deleteUrl" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors flex items-center gap-2">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>document.body.classList.add('page-microskill');</script>
@endpush

@endsection