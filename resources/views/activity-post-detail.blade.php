<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $activityPost->title }} - Simagang</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ url('storage/vendor/icon-komdigi.png') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f8fc;
            color: #0f2d4a;
            margin: 0;
            padding-bottom: 75px;
        }

        .detail-bg {
            background:
                radial-gradient(circle at top left, rgba(34,211,238,.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(37,99,235,.15), transparent 35%),
                linear-gradient(135deg, #f0f6ff 0%, #f8fbff 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 30px rgba(15, 45, 74, 0.04);
        }

        .article-content {
            color: #334155;
            line-height: 1.85;
            font-size: 1.025rem;
        }

        .article-content p {
            margin-bottom: 1.35rem;
        }

        .article-content h2,
        .article-content h3 {
            font-weight: 800;
            color: #0f172a;
            margin-top: 1.75rem;
            margin-bottom: .75rem;
        }

        .share-box-sidebar {
            margin-top: 1.25rem;
            padding: 1rem;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .share-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 40px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.25s ease;
        }

        .share-whatsapp {
            background: #22c55e;
            color: white;
            box-shadow: 0 4px 12px rgba(34,197,94,0.2);
        }

        .share-whatsapp:hover {
            background: #16a34a;
            transform: translateY(-2px);
            color: white;
        }

        .share-copy {
            background: #ffffff;
            color: #2563eb;
            border: 1px solid #cbd5e1;
        }

        .share-copy:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }

        .copy-alert {
            display: none;
            margin-top: 8px;
            padding: 6px 10px;
            border-radius: 8px;
            background: #ecfdf5;
            color: #047857;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
        }

        .copy-alert.show {
            display: block;
        }

        .main-footer {
            background: linear-gradient(135deg, #10499e 100%, #0e60cc 40%, #0891b2 30%);
            color: white;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            border-top: 1px solid rgba(34,211,238,0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 -10px 25px rgba(0,0,0,0.3);
        }

        .footer-simple-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-logos-simple,
        .social-links-simple {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .footer-logos-simple { gap: 1.5rem; }
        .footer-logos-simple img { height: 28px; object-fit: contain; }

        .copyright-simple {
            flex: 1.5;
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.8);
        }

        .social-links-simple { justify-content: flex-end; gap: 12px; }
        .social-links-simple a {
            width: 35px; height: 35px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 768px) {
            body { padding-bottom: 110px; }
            .footer-simple-inner { flex-direction: column; gap: 8px; padding: 4px 1rem; }
            .copyright-simple { order: 3; font-size: 11px; }
            .footer-logos-simple { order: 1; justify-content: center; }
            .social-links-simple { order: 2; justify-content: center; }
            .share-actions { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body class="detail-bg min-h-screen">

<!-- Header Navbar -->
<header class="sticky top-0 z-40 bg-white/90 backdrop-blur-xl border-b border-blue-100/80 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 h-[72px] flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <img src="{{ \App\Models\SystemSetting::get('logo_komdigi', url('storage/vendor/logo_komdigi.png')) }}" alt="Logo Komdigi" class="h-10 w-auto object-contain">
                <img src="{{ \App\Models\SystemSetting::get('logo_simagang', url('storage/vendor/simagang.png')) }}" alt="Logo Simagang" class="h-9 w-auto object-contain">
            </a>

        <div class="flex items-center gap-3">
            <a href="{{ route('landing') }}#news" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-semibold text-sm hover:bg-blue-100 transition">
                <i class="fas fa-arrow-left text-xs"></i> Kembali
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-blue-600 text-white font-semibold text-sm shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition">
                <i class="fas fa-sign-in-alt text-xs"></i> Login
            </a>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 md:px-6 py-8 md:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- ================= SISI KIRI: ARTIKEL UTAMA (8 Kolom) ================= -->
        <div class="lg:col-span-8">
            <div class="glass-card rounded-[28px] p-6 md:p-9">
                
                <!-- 1. Kategori Badge -->
                <div class="mb-3">
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $activityPost->type === 'youtube' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-blue-50 text-blue-600 border border-blue-200' }}">
                        <i class="{{ $activityPost->type_icon }} mr-1.5"></i> {{ $activityPost->type_label }}
                    </span>
                </div>

                <!-- 2. Judul Artikel -->
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-snug tracking-tight mb-4">
                    {{ $activityPost->title }}
                </h1>

                <!-- 3. Meta Info (Tanggal, Penulis, Editor) -->
                <div class="flex flex-wrap items-center gap-3 text-xs md:text-sm text-slate-600 pb-6 mb-6 border-b border-slate-200/80">
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 px-3 py-1 rounded-full text-slate-700 font-medium">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        {{ $activityPost->published_at ? $activityPost->published_at->format('d M Y') : '-' }}
                    </span>

                    @if($activityPost->type !== 'youtube')
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 px-3 py-1 rounded-full text-slate-700 font-medium">
                        <i class="fas fa-user text-blue-600"></i>
                        {{ $activityPost->display_author }}
                    </span>
                    @endif

                    @if($activityPost->display_editor !== '-')
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 px-3 py-1 rounded-full text-slate-700 font-medium">
                        <i class="fas fa-edit text-blue-600"></i>
                        Ed: {{ $activityPost->display_editor }}
                    </span>
                    @endif
                </div>

                <!-- 4. Gambar / Frame Video -->
                <div class="w-full rounded-2xl overflow-hidden shadow-md mb-8 bg-slate-900 aspect-video max-h-[380px] relative">
                    @if($activityPost->type === 'youtube')
                        @if($activityPost->youtube_embed_url)
                            <iframe src="{{ $activityPost->youtube_embed_url }}"
                                    class="w-full h-full border-0"
                                    allowfullscreen>
                            </iframe>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-red-800 via-slate-900 to-black flex flex-col items-center justify-center text-white p-6 text-center">
                                <i class="fab fa-youtube text-5xl mb-2 text-red-500"></i>
                                <p class="text-base font-bold">Link YouTube Belum Valid</p>
                                <p class="text-xs text-gray-300 mt-1">Silakan periksa kembali link video pada menu admin.</p>
                            </div>
                        @endif
                    @else
                        <img src="{{ $activityPost->thumbnail_url }}"
                             alt="{{ $activityPost->title }}"
                             class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- 5. Isi Artikel -->
                <div class="article-body">
                    @if($activityPost->type === 'artikel')
                        @if($activityPost->content)
                            <div class="article-content">
                                {!! nl2br(e($activityPost->content)) !!}
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 p-8 text-center text-slate-400">
                                <i class="fas fa-file-alt text-3xl mb-2"></i>
                                <p class="font-medium text-sm">Isi artikel belum tersedia.</p>
                            </div>
                        @endif
                    @else
                        @if($activityPost->content || $activityPost->excerpt)
                            <div class="text-slate-700 leading-relaxed whitespace-pre-line text-base">
                                {{ $activityPost->content ?: $activityPost->excerpt }}
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 p-8 text-center text-slate-400">
                                <i class="fab fa-youtube text-3xl mb-2"></i>
                                <p class="font-medium text-sm">Deskripsi video belum tersedia.</p>
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>

        <!-- ================= SISI KANAN: SIDEBAR (4 Kolom) ================= -->
        <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24">
            
            <!-- Card Ringkasan & Bagikan -->
            <div class="glass-card rounded-[28px] p-6">
                <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <i class="fas fa-file-text"></i>
                    </div>
                    <h2 class="text-base font-extrabold text-slate-900">Ringkasan</h2>
                </div>

                <!-- Excerpt / Ringkasan Content -->
                @if($activityPost->excerpt)
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-sm">
                        <p class="text-xs font-normal leading-relaxed">
                            {{ $activityPost->excerpt }}
                        </p>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-slate-100 text-slate-500 text-xs italic">
                        Tidak ada ringkasan untuk konten ini.
                    </div>
                @endif

                <!-- Bagikan Berita (Dipindah ke bawah Ringkasan) -->
                @php
                    $shareTitle = $activityPost->title ?? 'Aktivitas Simagang';
                    $shareDate = $activityPost->published_at ? $activityPost->published_at->format('d M Y') : '-';
                    $shareType = $activityPost->type_label ?? 'Aktivitas';
                    $shareUrl = url()->current();

                    $shareText = "Yuk baca berita terbaru Simagang!\n\n"
                        . "Judul: " . $shareTitle . "\n"
                        . "Tipe: " . $shareType . "\n"
                        . "Tanggal: " . $shareDate . "\n\n"
                        . "Buka link:\n" . $shareUrl;

                    $whatsappShareUrl = 'https://wa.me/?text=' . rawurlencode($shareText);
                @endphp

                <div class="share-box-sidebar">
                    <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <i class="fas fa-share-alt text-blue-600"></i> Bagikan Berita Ini
                    </div>

                    <div class="share-actions">
                        <a href="{{ $whatsappShareUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-whatsapp">
                            <i class="fab fa-whatsapp text-base"></i> WhatsApp
                        </a>

                        <button type="button" class="share-btn share-copy" onclick="copyActivityPostLink()">
                            <i class="fas fa-link"></i> Salin Link
                        </button>
                    </div>

                    <div id="copyAlert" class="copy-alert">
                        <i class="fas fa-check-circle"></i> Link disalin.
                    </div>
                </div>

            </div>

            <!-- Card Konten Terkait -->
            <div class="glass-card rounded-[28px] p-6">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h2 class="text-base font-extrabold text-slate-900">
                        {{ $activityPost->type_label }} Lainnya
                    </h2>
                    <span class="text-xs font-bold text-blue-600">Terkait</span>
                </div>

                <div class="space-y-3">
                    @forelse($relatedPosts as $post)
                        <a href="{{ route('public.activity.show', $post->slug) }}" class="flex gap-3 p-2.5 rounded-2xl hover:bg-blue-50/60 transition border border-transparent hover:border-blue-100 group">
                            <div class="w-20 h-16 rounded-xl overflow-hidden bg-slate-200 shrink-0 relative">
                                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @if($post->type === 'youtube')
                                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center text-white text-xs">
                                        <i class="fas fa-play"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex flex-col justify-center">
                                <h4 class="font-bold text-slate-800 text-xs leading-snug line-clamp-2 group-hover:text-blue-600 transition">
                                    {{ $post->title }}
                                </h4>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    <i class="fas fa-calendar-alt text-blue-500 mr-1"></i>
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 p-6 text-center text-slate-400 text-xs">
                            Belum ada konten terkait.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</main>

<!-- Footer -->
<footer class="main-footer">
    <div class="footer-simple-inner">
        <div class="footer-logos-simple">
            <img src="{{ url('storage/vendor/logo_berakhlak.png') }}" alt="BerAkhlak">
            <img src="{{ url('storage/vendor/logo_banggamelayani.png') }}" alt="Bangga Melayani">
            <img src="{{ url('storage/vendor/logo_antikorupsi.png') }}" alt="Anti Korupsi">
        </div>

        <div class="copyright-simple">
            &copy; 2026 <strong>Simagang</strong> — BBLSDM Komdigi Makassar. All rights reserved.
        </div>

        <div class="social-links-simple">
            <a href="https://www.instagram.com/bblsdm.komdigi.makassar/" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://www.komdigi.go.id/" target="_blank" title="Website"><i class="fas fa-globe"></i></a>
            <a href="https://www.tiktok.com/@balaikomdigimakassar" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
            <a href="https://www.youtube.com/@bblsdm.komdigi.makassar" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</footer>

<script>
    function copyActivityPostLink() {
        const url = window.location.href;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(function () {
                showCopyAlert();
            }).catch(function () {
                fallbackCopyText(url);
            });
        } else {
            fallbackCopyText(url);
        }
    }

    function fallbackCopyText(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        textarea.style.top = '-9999px';

        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();

        try {
            document.execCommand('copy');
            showCopyAlert();
        } catch (err) {
            alert('Link belum bisa disalin otomatis.');
        }

        document.body.removeChild(textarea);
    }

    function showCopyAlert() {
        const alertBox = document.getElementById('copyAlert');
        if (!alertBox) return;

        alertBox.classList.add('show');
        setTimeout(function () {
            alertBox.classList.remove('show');
        }, 2200);
    }
</script>

</body>
</html>