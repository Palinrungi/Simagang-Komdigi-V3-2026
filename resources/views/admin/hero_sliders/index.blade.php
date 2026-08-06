@extends('layouts.app')

@section('title', 'Kelola Hero Slider - Admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Strip -->
        <div class="bg-gradient-to-r from-blue-900 via-indigo-800 to-blue-700 rounded-2xl p-6 mb-6 text-white shadow-lg flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold">Kelola Banner / Hero Carousel</h1>
                <p class="text-blue-200 text-sm">Atur slide, gambar background, video YouTube, dan urutan carousel di Landing Page</p>
            </div>
            <a href="{{ route('admin.hero-sliders.create') }}" class="px-5 py-2.5 bg-cyan-400 hover:bg-cyan-300 text-slate-900 font-extrabold rounded-xl shadow transition duration-200 flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Slide Baru
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-emerald-800 bg-emerald-100 rounded-xl border border-emerald-200 font-semibold flex items-center gap-2">
                <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Panel Tabel Slider -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-images text-blue-600"></i> Daftar Slide Active Carousel
                </h2>
                <span class="text-xs bg-slate-100 text-slate-600 font-bold px-3 py-1 rounded-full">Total: {{ $sliders->count() }} Slide</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3 px-4 text-center">Urutan</th>
                            <th class="py-3 px-4">Preview Background</th>
                            <th class="py-3 px-4">Judul & Tipe</th>
                            <th class="py-3 px-4">Tombol / Link</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($sliders as $slider)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 px-4 text-center font-bold text-slate-700">
                                    <span class="inline-block w-8 h-8 rounded-lg bg-blue-50 text-blue-700 leading-8 text-center border border-blue-200">
                                        {{ $slider->order }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($slider->image_path)
                                        <img src="{{ $slider->image_url }}" class="w-24 h-14 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Preview">
                                    @else
                                        <div class="w-24 h-14 bg-slate-100 text-slate-400 rounded-lg border border-slate-200 flex items-center justify-center text-xs">Default BG</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-slate-900 leading-tight mb-1">{!! Str::limit(strip_tags($slider->title), 50) !!}</p>
                                    <p class="text-xs text-slate-500 line-clamp-1 mb-1.5">{{ $slider->subtitle ?? '-' }}</p>
                                    
                                    @if($slider->type === 'youtube')
                                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border border-red-200">
                                            <i class="fab fa-youtube"></i> Video YouTube
                                        </span>
                                    @elseif($slider->type === 'sharing_session')
                                        <span class="inline-flex items-center gap-1 bg-cyan-100 text-cyan-800 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border border-cyan-200">
                                            <i class="fas fa-comments"></i> Sharing Session (Otomatis)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border border-blue-200">
                                            <i class="fas fa-layer-group"></i> General Slide
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-xs">
                                    @if($slider->button_url)
                                        <a href="{{ $slider->button_url }}" target="_blank" class="text-blue-600 hover:underline font-semibold flex items-center gap-1">
                                            <i class="fas fa-link text-[10px]"></i> {{ $slider->button_text ?? 'Link' }}
                                        </a>
                                    @elseif($slider->youtube_url)
                                        <a href="{{ $slider->youtube_url }}" target="_blank" class="text-red-600 hover:underline font-semibold flex items-center gap-1">
                                            <i class="fab fa-youtube text-[10px]"></i> Buka Video
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($slider->is_active)
                                        <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">
                                            <i class="fas fa-check-circle text-[10px]"></i> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.hero-sliders.edit', $slider) }}" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 flex items-center justify-center transition" title="Edit Slide">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.hero-sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 flex items-center justify-center transition" title="Hapus Slide">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada slide hero kustom. Slide default bawaan akan ditampilkan di Landing Page.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection