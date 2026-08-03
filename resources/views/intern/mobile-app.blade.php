@extends('layouts.app')

@section('title', 'Aplikasi Mobile - Sistem Magang')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    body { background: #f0f4ff; }

    .page-bg {
        background: linear-gradient(135deg, #e8eeff 0%, #f0f4ff 40%, #e4ecff 100%);
        min-height: calc(100vh - 80px);
    }

    .hero-strip {
        background: linear-gradient(110deg, #1e3a8a 0%, #312e81 50%, #0f172a 100%);
        position: relative;
        overflow: hidden;
        border-radius: 2rem;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
    }

    .hero-strip::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(59,130,246,0.25) 0%, rgba(0,0,0,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-strip::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: 20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, rgba(0,0,0,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .feature-card {
        background: #ffffff;
        border-radius: 1.5rem;
        padding: 1.5rem;
        border: 1px solid #e0e7ff;
        box-shadow: 0 4px 15px rgba(30,58,138,0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(59,79,216,0.12);
        border-color: #c7d2fe;
    }

    .step-card {
        background: #ffffff;
        border-radius: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15,23,42,0.05);
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .anim-1 { animation: fadeSlideUp 0.5s ease both; }
    .anim-2 { animation: fadeSlideUp 0.5s ease 0.1s both; }
    .anim-3 { animation: fadeSlideUp 0.5s ease 0.2s both; }
</style>
@endpush

@section('content')
<div class="page-bg py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- ALERT ERROR JIKA FILE BELUM TERSEDIA --}}
        @if(session('error'))
            <div class="p-5 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-sm anim-1">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Pengunduhan Tidak Dapat Dilakukan</p>
                        <p class="text-xs text-red-600 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-700 font-bold px-2">&times;</button>
            </div>
        @endif

        {{-- HERO SECTION --}}
        <div class="hero-strip p-8 md:p-12 anim-1">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-2xl text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                        Simagang Mobile
                    </h1>
                    <p class="text-blue-200/90 text-base md:text-lg mt-3 leading-relaxed">
                        Kemudahan mengelola absensi harian, foto kehadiran, logbook magang, serta pengumuman langsung dalam genggaman HP Android Anda.
                    </p>
                </div>

                {{-- KARTU TOMBOL DOWNLOAD HERO --}}
                <div class="w-full md:w-auto flex flex-col items-center md:items-end flex-shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/15 p-6 rounded-3xl shadow-2xl max-w-sm w-full text-center">
                        <div id="cta-download-android" class="space-y-4">
                            <a href="{{ route('intern.mobile-app.download') }}" 
                               class="w-full inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 active:scale-95 text-white font-bold text-base rounded-2xl shadow-xl shadow-emerald-500/30 transition-all duration-200">
                                <i class="fab fa-android text-2xl"></i>
                                <span>Download APK Android</span>
                            </a>

                            <div class="flex items-center justify-center gap-4 text-xs text-blue-200/80 pt-1">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-tag text-emerald-400"></i>
                                    v1.0.0
                                </span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-file-shield text-emerald-400"></i>
                                    Aman & Resmi
                                </span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1.5" title="Ukuran File APK">
                                    <i class="fas fa-database text-emerald-400"></i>
                                    {{ $apkSize ?? '~25 MB' }}
                                </span>
                            </div>
                        </div>

                        {{-- Tampilan Khusus iOS --}}
                        <div id="cta-download-ios" class="hidden p-4 rounded-2xl bg-amber-500/20 border border-amber-400/30 text-amber-200 text-sm text-left">
                            <div class="flex items-start gap-3">
                                <i class="fab fa-apple text-2xl text-amber-400 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-bold text-amber-300">Versi iOS Belum Tersedia</p>
                                    <p class="text-xs text-amber-200/90 mt-1">
                                        Untuk pengguna iPhone, silakan gunakan Safari dan pilih opsi <strong>"Add to Home Screen"</strong> atau unduh melalui perangkat Android.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT GRID (2 KOLOM: FITUR & PANDUAN INSTALASI) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 anim-2">

            {{-- KOLOM KIRI: FITUR UNGGULAN --}}
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-800">Fitur Utama Aplikasi</h2>
                    <p class="text-sm text-gray-500 mt-1">Dirancang khusus untuk mempermudah aktivitas magang Anda setiap hari.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="feature-card">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold mb-4">
                            <i class="fas fa-camera text-xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base">Absensi</h3>
                        <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                            Lakukan absen masuk dan keluar lengkap dengan foto selfie serta verifikasi lokasi secara real-time.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mb-4">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base">Logbook</h3>
                        <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                            Catat aktivitas harian magang dan pantau persetujuan logbook langsung dari genggaman Anda.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold mb-4">
                            <i class="fas fa-comments text-xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base">Sharing Session</h3>
                        <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                            Cek jadwal kegiatan, lengkapi materi narasumber, dan isi form evaluasi dengan lebih praktis.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold mb-4">
                            <i class="fas fa-award text-xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base">Mikro Skill</h3>
                        <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                            Pantau kemajuan pembelajaran mikro skill dan selesaikan berbagai materi pelatihan langsung dari genggaman Anda.
                        </p>
                    </div>
                </div>

                {{-- INFO SISTEM REQUIREMENT --}}
                <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">
                        <i class="fas fa-circle-info text-blue-600 mr-2"></i>
                        Spesifikasi Minimum Perangkat
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <p class="text-gray-400 font-semibold">Sistem Operasi</p>
                            <p class="text-gray-800 font-bold mt-0.5">Android 8.0 (Oreo) +</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <p class="text-gray-400 font-semibold">Ukuran File APK</p>
                            <p class="text-gray-800 font-bold mt-0.5">{{ $apkSize ?? '~25 MB' }}</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <p class="text-gray-400 font-semibold">Ruang Penyimpanan</p>
                            <p class="text-gray-800 font-bold mt-0.5">Minimal 50 MB Free</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <p class="text-gray-400 font-semibold">Koneksi Internet</p>
                            <p class="text-gray-800 font-bold mt-0.5">4G / Wi-Fi Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PANDUAN INSTALASI & FAQ --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="step-card p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                            <i class="fas fa-sliders text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Panduan Install APK</h2>
                            <p class="text-xs text-gray-500">4 Langkah instalasi di HP Android Anda</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex gap-4 items-start p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="w-7 h-7 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">1</span>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Unduh File APK</p>
                                <p class="text-xs text-gray-500 mt-1">Klik tombol <strong>Download APK Android</strong> di atas dan tunggu proses unduh sampai selesai.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="w-7 h-7 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">2</span>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Izinkan Unknown Sources</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Jika muncul peringatan <em>"File mungkin berbahaya"</em>, pilih <strong>Tetap Download (Download anyway)</strong>. Saat membuka APK, aktifkan opsi <strong>"Izinkan dari sumber ini"</strong> di Pengaturan Android.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="w-7 h-7 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">3</span>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Install Aplikasi</p>
                                <p class="text-xs text-gray-500 mt-1">Klik tombol <strong>Install</strong> dan tunggu beberapa detik hingga ikon aplikasi <strong>Simagang Mobile</strong> terpasang.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="w-7 h-7 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0 mt-0.5">4</span>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Login Akun Magang</p>
                                <p class="text-xs text-gray-500 mt-1">Buka aplikasi dan masuk menggunakan email dan kata sandi akun portal magang Anda ini.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FAQ MINIMALIS --}}
                <div class="p-6 rounded-3xl bg-white border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                        Pertanyaan Umum
                    </h3>

                    <div class="space-y-3">
                        <details class="group border-b border-gray-100 pb-3">
                            <summary class="text-xs font-bold text-gray-700 cursor-pointer flex justify-between items-center">
                                <span>Mengapa muncul peringatan keamanan saat download?</span>
                                <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition"></i>
                            </summary>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                Karena aplikasi diunduh langsung (sideloading) dari portal intern resmi ini dan bukan dari Play Store, sistem Android secara standar meminta konfirmasi dari Anda.
                            </p>
                        </details>

                        <details class="group border-b border-gray-100 pb-3">
                            <summary class="text-xs font-bold text-gray-700 cursor-pointer flex justify-between items-center">
                                <span>Apakah bisa digunakan di iPhone (iOS)?</span>
                                <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition"></i>
                            </summary>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                Saat ini versi native iOS masih dalam tahap persiapan lisensi. Pengguna iPhone dapat menggunakan Safari dan memilih <strong>"Add to Home Screen"</strong> untuk pengalaman serupa aplikasi.
                            </p>
                        </details>

                        <details class="group">
                            <summary class="text-xs font-bold text-gray-700 cursor-pointer flex justify-between items-center">
                                <span>Apakah absensi di aplikasi tersinkron dengan web ini?</span>
                                <i class="fas fa-chevron-down text-gray-400 group-open:rotate-180 transition"></i>
                            </summary>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                Ya, 100% tersinkron secara real-time. Semua data absensi dan logbook yang diisi di aplikasi akan otomatis tampil di dasbor portal web.
                            </p>
                        </details>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    const isIOS = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream;

    const androidCta = document.getElementById('cta-download-android');
    const iosCta = document.getElementById('cta-download-ios');

    if (isIOS) {
        if (androidCta) androidCta.classList.add('hidden');
        if (iosCta) iosCta.classList.remove('hidden');
    } else {
        if (androidCta) androidCta.classList.remove('hidden');
        if (iosCta) iosCta.classList.add('hidden');
    }
});
</script>
@endsection
