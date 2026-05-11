<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simagang - Sistem Manajemen Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ url('storage/vendor/icon-komdigi.png') }}">
    <link rel="shortcut icon" href="{{ url('storage/vendor/icon-komdigi.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        @font-face {
            font-family: 'Etna';
            src: url('/fonts/Etna-Free-Font.otf') format('opentype');
        }
        .font-etna { font-family: 'Etna', sans-serif; }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f7ff;
            color: #0f2d4a;
            margin: 0;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid #bfdbfe;
            box-shadow: 0 1px 20px rgba(14,99,201,0.07);
        }
        .navbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .logo-wrap {
            width: 36px;
            height: 36px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .logo-wrap img { width: 24px; height: 24px; object-fit: contain; }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-links a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: #4b6580;
            padding: 8px 16px;
            border-radius: 50px;
            transition: all 0.2s;
        }
        .nav-links a:hover { background: #eff6ff; color: #1d6fca; }
        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: linear-gradient(135deg, #1d6fca, #0ea5e9);
            color: white;
            font-size: 14px;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(14,99,201,0.3);
            transition: all 0.25s;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(14,99,201,0.4); }
        .nav-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: 1.5px solid rgba(14,99,201,0.18);
            background: white;
            color: #1d6fca;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .nav-toggle:hover { background: #eff6ff; }
        .mobile-menu {
            position: absolute;
            top: 70px;
            right: 1rem;
            width: min(280px, calc(100% - 2rem));
            background: white;
            border: 1px solid #dbeafe;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(14,99,201,0.12);
            display: none;
            flex-direction: column;
            gap: 0.65rem;
            padding: 0.85rem 0.9rem 1rem;
            z-index: 55;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            display: block;
            text-decoration: none;
            color: #0f2d4a;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 14px;
            transition: background 0.2s, color 0.2s;
        }
        .mobile-menu a:hover { background: #eff6ff; }
        .mobile-menu .login-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #1d6fca;
            color: white;
            border-radius: 14px;
            padding: 12px 16px;
        }
        .mobile-menu .login-link:hover { background: #0ea5e9; }

        /* ── HERO ── */
        .hero {
            position: relative;
            background: linear-gradient(135deg, #0c2d5e 0%, #1251a3 40%, #0891b2 100%);
            overflow: hidden;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }
        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            pointer-events: none;
        }
        .hero-blob-1 { width: 500px; height: 500px; background: #38bdf8; top: -150px; left: -100px; }
        .hero-blob-2 { width: 400px; height: 400px; background: #818cf8; bottom: -100px; right: 0; }
        .hero-blob-3 { width: 300px; height: 300px; background: #22d3ee; top: 40%; left: 40%; }

        .hero-inner {
            position: relative;
            max-width: 1280px;
            margin: 0 auto;
            padding: 5rem 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            width: 100%;
        }

        /* Kolom kiri: satu flex column — badge → judul → desc → stats → btns */
        .hero-text-col {
            display: flex;
            flex-direction: column;
        }

        /* .hero-badge {
            display: inline-flex;
            align-self: flex-start;
            align-items: center;
            gap: 8px;
            background: rgba(56,189,248,0.18);
            border: 1px solid rgba(56,189,248,0.35);
            color: #7dd3fc;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 1.25rem;
        } */
        .hero-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.15;
            color: white;
            margin: 0 0 1.25rem;
        }
        .hero-title span { color: #7dd3fc; }

        .hero-desc {
            font-size: 1rem;
            line-height: 1.75;
            color: rgba(255,255,255,0.75);
            margin: 0;          /* stats langsung menyusul */
            max-width: 480px;
        }

        /* Stats — tepat di bawah deskripsi */
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
            padding-bottom: 1.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.14);
        }
        .hero-stat-num { font-size: 1.75rem; font-weight: 800; color: white; line-height: 1; }
        .hero-stat-label { font-size: 11px; color: rgba(255,255,255,0.55); margin-top: 4px; }
        .hero-stat-divider { width: 1px; background: rgba(255,255,255,0.14); flex-shrink: 0; }

        /* Tombol — di bawah stats */
        .hero-btns {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 1.75rem;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: #22d3ee;
            color: #0c2d5e;
            font-weight: 700;
            font-size: 15px;
            border-radius: 9999px;
            text-decoration: none;
            box-shadow: 0 6px 28px rgba(34,211,238,0.28);
            transition: all 0.25s;
        }
        .btn-primary:hover { background: #38bdf8; transform: translateY(-2px); }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: rgba(255,255,255,0.12);
            border: 1.5px solid rgba(255,255,255,0.28);
            color: white;
            font-weight: 600;
            font-size: 15px;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.25s;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.22); }

        /* Kolom kanan: gambar */
        .hero-image-wrap {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .hero-image-card { width: 100%; }
        .hero-image-card img {
            width: 100%;
            max-width: 100%;
            height: auto;
            object-fit: contain;
            display: block;
        }

        /* ── SECTION WRAPPER ── */
        .section-header { text-align: center; max-width: 640px; margin: 0 auto 3.5rem; }
        .section-eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #1d6fca;
            background: #eff6ff;
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 1rem;
        }
        .section-eyebrow-alt {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #1d6fca;
            background: #c7dfff;
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 1rem;
        }
        .section-title { font-size: clamp(1.75rem, 3vw, 2.5rem); font-weight: 800; color: #0f2d4a; margin: 0 0 1rem; line-height: 1.2; }
        .section-desc { font-size: 1.05rem; color: #4b6580; line-height: 1.7; margin: 0; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }

        /* ── PROCESS ── */
        .section-process { background: white; padding: 6rem 0; }
        .process-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .process-card {
            background: #f0f7ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            position: relative;
            transition: all 0.3s;
            overflow: hidden;
        }
        .process-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #eff6ff, transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .process-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(14,99,201,0.12); border-color: #93c5fd; }
        .process-card:hover::before { opacity: 1; }
        .process-num {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 3.5rem;
            font-weight: 900;
            color: #bfdbfe;
            line-height: 1;
        }
        .process-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #1d6fca, #0ea5e9);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 22px;
            margin-bottom: 1.5rem;
            position: relative;
            box-shadow: 0 8px 24px rgba(29,111,202,0.3);
        }
        .process-title { font-size: 1.2rem; font-weight: 700; color: #0f2d4a; margin: 0 0 0.6rem; }
        .process-desc { font-size: 0.95rem; color: #4b6580; line-height: 1.6; margin: 0; }

        /* Carousel shared styles */
        .process-carousel { display: none; overflow: hidden; }
        .testi-carousel  { display: none; overflow: hidden; }
        .carousel-wrapper { display: flex; transition: transform 0.4s ease-out; }
        .carousel-item { flex: 0 0 100%; min-width: 100%; }
        .carousel-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .carousel-btn {
            width: 40px; height: 40px; border-radius: 50%;
            background: #eff6ff; border: 1.5px solid #bfdbfe; color: #1d6fca;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all 0.3s; font-size: 13px;
        }
        .carousel-btn:hover { background: #1d6fca; color: white; border-color: #1d6fca; }
        .carousel-btn:disabled { opacity: 0.4; cursor: not-allowed; }
        .dot-wrap { display: flex; gap: 0.5rem; }
        .dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #dbeafe; cursor: pointer; transition: all 0.3s;
        }
        .dot.active { background: #1d6fca; width: 24px; border-radius: 4px; }

        /* ── USAGE / TUTORIAL ── */
        .section-usage { background: #f0f7ff; padding: 6rem 0; }
        .step-block {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            padding: 3.5rem 0;
        }
        .step-block + .step-block { border-top: 1px solid #bfdbfe; }
        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #1d6fca;
            font-size: 11px; font-weight: 700; letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 5px 12px; border-radius: 50px; margin-bottom: 1rem;
        }
        .step-icon-wrap {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #1d6fca, #0ea5e9);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 20px;
            box-shadow: 0 6px 20px rgba(29,111,202,0.28);
            margin-bottom: 1.25rem;
        }
        .step-title { font-size: 1.75rem; font-weight: 800; color: #0f2d4a; margin: 0 0 1rem; }
        .step-desc { font-size: 1.05rem; color: #4b6580; line-height: 1.75; margin: 0; }
        .step-list { list-style: none; padding: 0; margin: 1.25rem 0 0; display: flex; flex-direction: column; gap: 10px; }
        .step-list li {
            display: flex; align-items: center; gap: 10px;
            font-size: 0.95rem; color: #4b6580;
        }
        .step-list li::before {
            content: '';
            width: 22px; height: 22px; min-width: 22px;
            background: #eff6ff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%231d6fca'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414L8.414 15l-4.121-4.121a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z' clip-rule='evenodd'/%3E%3C/svg%3E") center/13px no-repeat;
            border: 1.5px solid #93c5fd;
            border-radius: 50%;
        }
        .step-image {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 16px 50px rgba(14,99,201,0.12);
            border: 2px solid #bfdbfe;
            transition: transform 0.35s;
        }
        .step-image:hover { transform: scale(1.025); }

        /* ── TESTIMONIALS ── */
        .section-testimonials { background: white; padding: 6rem 0; }
        .testi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .testi-card {
            background: #f0f7ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            transition: all 0.3s;
            display: flex; flex-direction: column;
            min-height: 320px;
        }
        .testi-card:hover { transform: translateY(-4px); box-shadow: 0 16px 44px rgba(14,99,201,0.1); }
        .testi-quote-icon { font-size: 2.5rem; color: #bfdbfe; line-height: 1; margin-bottom: 1rem; }
        .testi-stars { color: #f59e0b; font-size: 13px; margin-bottom: 0.75rem; letter-spacing: 2px; }
        .testi-text { font-size: 1rem; color: #4b6580; line-height: 1.7; margin: 0 0 1.5rem; font-style: italic; flex-grow: 1; }
        .testi-author { display: flex; align-items: center; gap: 12px; padding-top: 1.25rem; border-top: 1px solid #bfdbfe; }
        .testi-avatar {
            width: 48px; height: 48px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #1d6fca, #0ea5e9); color: white; font-weight: 700; font-size: 15px;
        }
        .testi-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .testi-name { font-weight: 700; font-size: 14px; color: #0f2d4a; }
        .testi-inst { font-size: 12px; color: #64748b; margin-top: 2px; }

        /* ── PARTNERS ── */
        .section-partners { background: #f0f7ff; padding: 5rem 0; }
        .partners-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
        .partner-card {
            background: white;
            border: 1.5px solid #bfdbfe;
            border-radius: 20px;
            padding: 1.75rem;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s;
        }
        .partner-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(14,99,201,0.1); border-color: #93c5fd; }
        .partner-card img { max-height: 64px; object-fit: contain; filter: none ; transition: filter 0.3s; }
        .partner-card:hover img {transform: scale(1.05); }
        .partner-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            color: #64748b;
            background: white;
            border-radius: 20px;
            border: 1.5px dashed #bfdbfe;
        }

        /* ── CTA ── */
        .section-cta { background: linear-gradient(135deg, #0c2d5e 0%, #1251a3 50%, #0891b2 100%); padding: 6rem 0; }
        .cta-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        .cta-tag {
            display: inline-block;
            background: rgba(34,211,238,0.18);
            border: 1px solid rgba(34,211,238,0.3);
            color: #67e8f9;
            font-size: 11px; font-weight: 700; letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 5px 14px; border-radius: 50px; margin-bottom: 1rem;
        }
        .cta-title { font-size: 2.25rem; font-weight: 800; color: white; margin: 0 0 1rem; line-height: 1.2; }
        .cta-desc { font-size: 1.05rem; color: rgba(255,255,255,0.65); line-height: 1.7; margin: 0; }
        .cta-cards { display: flex; flex-direction: column; gap: 1rem; }
        .cta-card {
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,0.07);
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: 20px; padding: 1.25rem 1.5rem;
            text-decoration: none; color: white;
            transition: all 0.25s; cursor: pointer;
        }
        .cta-card:hover { background: rgba(255,255,255,0.14); border-color: rgba(255,255,255,0.25); transform: translateX(4px); }
        .cta-card-left { display: flex; align-items: center; gap: 14px; }
        .cta-card-icon {
            width: 46px; height: 46px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white; flex-shrink: 0;
        }
        .cta-card-name { font-weight: 700; font-size: 15px; }
        .cta-card-sub { font-size: 12px; color: rgba(255,255,255,0.55); margin-top: 2px; }
        .cta-arrow { font-size: 20px; opacity: 0.6; }

        /* ── FOOTER ── */
        .main-footer {
            background:  linear-gradient(135deg, #10499e 100%, #0e60cc 40%, #0891b2 30%);
            color: white;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            border-top: 1px solid rgba(34, 211, 238, 0.3);
            backdrop-filter: blur(10px); 
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.3);
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

        .footer-logos-simple {
            gap: 1.5rem;
        }

        .footer-logos-simple img {
            height: 28px;
            object-fit: contain;
            /* Membuat logo sedikit lebih terang agar kontras */
            filter: drop-shadow(0 0 5px rgba(255,255,255,0.1));
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .footer-logos-simple img:hover {
            transform: translateY(-5px) scale(1.1);
            filter: drop-shadow(0 5px 15px rgba(34, 211, 238, 0.4));
        }

        .copyright-simple {
            flex: 1.5;
            text-align: center;
            font-size: 13px;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.8);
        }

        .copyright-simple strong {
            color: #22d3ee;
            text-shadow: 0 0 10px rgba(34, 211, 238, 0.3);
        }

        .social-links-simple {
            justify-content: flex-end;
            gap: 12px;
        }

        .social-links-simple a {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .social-links-simple a:hover {
            background: rgba(34, 211, 238, 0.15);
            color: #22d3ee;
            border-color: #22d3ee;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(34, 211, 238, 0.2);
        }

        /* Responsif untuk Mobile */
        @media (max-width: 768px) {
            .main-footer { padding: 10px 0; }
            .footer-simple-inner { flex-direction: column; gap: 8px; }
            .copyright-simple { order: 3; font-size: 11px; }
            .footer-logos-simple { order: 1; justify-content: center; }
            .social-links-simple { order: 2; justify-content: center; }
        }


        /* Newsletter Input matching your theme */
        .newsletter-form { display: flex; gap: 8px; margin-top: 1.25rem; }
        .newsletter-form input {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 12px 16px;
            border-radius: 12px;
            color: white;
            flex-grow: 1;
            font-size: 14px;
            outline: none;
        }
        .newsletter-form input:focus { border-color: #22d3ee; background: rgba(255,255,255,0.1); }
        
        .newsletter-btn {
            background: #22d3ee; /* Sesuai warna btn-primary Anda */
            color: #0c2d5e;
            padding: 0 20px;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .newsletter-btn:hover { background: #38bdf8; transform: translateY(-2px); }

        .social-links { display: flex; gap: 12px; margin-top: 1.5rem; }
        .social-links a {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px; color: white; transition: 0.3s;
        }
        .social-links a:hover { 
            background: #22d3ee; 
            color: #0c2d5e;
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(34,211,238,0.2);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        .partner-logos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 3rem;
            align-items: center;
        }
        .partner-logos img { 
            height: 30px; 
            opacity: 1; 
            transition: transform 0.3s;
            object-fit: contain;
        }
        .partner-logos img:hover { transform: scale(1.1); }

        .copyright { font-size: 13px; color: rgba(255,255,255,0.4); text-align: center; }
        .copyright span { color: #22d3ee; font-weight: 600; }


        /* ── MODERN NEWS SECTION ── */
        .news-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
        }

        /* Berita Utama (Kiri) */
        .news-main-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            height: 500px;
            cursor: pointer;
        }

        .news-main-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .news-main-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(2, 11, 26, 0.95), transparent);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
        }

        /* Daftar Berita Samping (Kanan) */
        .news-side-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .news-side-item {
            display: flex;
            gap: 20px;
            background: white;
            padding: 15px;
            border-radius: 18px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 242, 255, 0.05);
            text-decoration: none;
        }

        .news-side-item:hover {
            border-color: #00f2ff;
            transform: translateX(10px);
            box-shadow: 0 10px 20px rgba(0, 242, 255, 0.05);
        }

        .news-side-img {
            width: 120px;
            height: 90px;
            border-radius: 12px;
            object-fit: cover;
        }

        .news-side-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .news-side-title {
            font-size: 15px;
            font-weight: 700;
            color: #020b1a;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .news-container { grid-template-columns: 1fr; }
            .news-main-card { height: 350px; }
        }


        /* ── SCROLL ANIMATION ── */
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .hero { min-height: auto; }
            .hero-inner {
                grid-template-columns: 1fr;
                padding: 3.5rem 1.5rem;
                gap: 2.5rem;
                text-align: center;
            }
            /* .hero-badge { align-self: center; } */
            .hero-desc { margin-left: auto; margin-right: auto; }
            .hero-stats { justify-content: center; }
            .hero-btns { justify-content: center; }
            .hero-image-wrap { justify-content: center; max-width: 520px; margin: 0 auto; order: -1; }
            .process-grid { grid-template-columns: 1fr; }
            .step-block { grid-template-columns: 1fr; gap: 2rem; padding: 2rem 0; }
            .step-block .order-swap { order: 0; }
            .step-block .step-image-wrap { order: -1; }
            .testi-grid { grid-template-columns: 1fr; }
            .partners-grid { grid-template-columns: repeat(2, 1fr); }
            .cta-inner { grid-template-columns: 1fr; }
            .nav-links { display: none; }
            .nav-toggle { display: inline-flex; }
            .btn-login { display: none; }
        }
        @media (max-width: 640px) {
            .hero-inner { padding: 2.5rem 1rem; gap: 1.5rem; }
            .hero-title { font-size: 1.8rem; }
            .hero-image-wrap { max-width: 100%; }
            .partners-grid { grid-template-columns: 1fr 1fr; }
            .process-grid { display: none; }
            .process-carousel { display: block; }
            .testi-grid { display: none; }
            .testi-carousel { display: block; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->


<main>
    <section id="artikel1" class="section-usage">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Sharing Session</span>
                <h2 class="section-title">
                    Visualisasi Data Sederhana Menggunakan Looker Studio
                </h2>
                <p class="section-desc">
                    Kegiatan sharing session ini membahas cara membuat visualisasi data secara sederhana menggunakan Google Looker Studio untuk membantu penyajian informasi menjadi lebih menarik, interaktif, dan mudah dipahami.
                </p>
            </div>

            <div class="step-block reveal">
                <div>
                    <div class="step-badge">
                        <i class="fas fa-chart-line"></i>
                        Sharing Session
                    </div>

                    <div class="step-icon-wrap">
                        <i class="fas fa-desktop"></i>
                    </div>

                    <h3 class="step-title">
                        Memahami Dasar Visualisasi Data
                    </h3>

                    <p class="step-desc">
                        Pada kegiatan ini, peserta diperkenalkan dengan konsep dasar visualisasi data serta pentingnya penyajian data yang efektif dalam mendukung analisis dan pengambilan keputusan. Materi difokuskan pada penggunaan Looker Studio sebagai salah satu tools visualisasi data yang mudah digunakan dan dapat diakses secara online.
                    </p>

                    <ul class="step-list">
                        <li>Mengenal fitur dasar Looker Studio</li>
                        <li>Membuat dashboard sederhana</li>
                        <li>Menghubungkan data dari spreadsheet</li>
                        <li>Menampilkan grafik dan tabel interaktif</li>
                    </ul>
                </div>

                <div class="step-image-wrap">
                    <img src="{{ asset('storage/artikel/artikel_1.jpeg') }} "
                        alt="Sharing Session Looker Studio" 
                        class="step-image">
                </div>
            </div>

            <div class="step-block reveal">
                <div class="step-image-wrap order-swap">
                    <img src="{{ asset('storage/artikel/artikel_1_1.png') }} " 
                        alt="Praktik Looker Studio" 
                        class="step-image">
                </div>

                <div class="order-swap">
                    <div class="step-badge">
                        <i class="fas fa-users"></i>
                        Praktik Langsung
                    </div>

                    <div class="step-icon-wrap">
                        <i class="fas fa-laptop-code"></i>
                    </div>

                    <h3 class="step-title">
                        Praktik Membuat Dashboard Interaktif
                    </h3>

                    <p class="step-desc">
                        Selain penyampaian materi, peserta juga melakukan praktik langsung membuat dashboard visualisasi data menggunakan dataset sederhana. Melalui sesi ini, peserta belajar bagaimana mengubah data mentah menjadi tampilan informasi yang lebih komunikatif dan mudah dianalisis.
                    </p>

                    <ul class="step-list">
                        <li>Mendesain tampilan dashboard</li>
                        <li>Mengatur filter dan interaksi data</li>
                        <li>Menggunakan chart untuk analisis sederhana</li>
                        <li>Meningkatkan kemampuan pengolahan data digital</li>
                    </ul>
                </div>
            </div>

            <div class="step-block reveal">
                <div>
                    <div class="step-badge">
                        <i class="fas fa-lightbulb"></i>
                        Insight
                    </div>

                    <div class="step-icon-wrap">
                        <i class="fas fa-chart-pie"></i>
                    </div>

                    <h3 class="step-title">
                        Meningkatkan Literasi Data Digital
                    </h3>

                    <p class="step-desc">
                        Sharing session ini diharapkan dapat membantu peserta memahami pentingnya visualisasi data di era digital. Dengan memanfaatkan Looker Studio, peserta dapat menyajikan data secara lebih profesional, informatif, dan menarik untuk berbagai kebutuhan laporan maupun presentasi.
                    </p>

                    <ul class="step-list">
                        <li>Belajar menyampaikan data secara visual</li>
                        <li>Meningkatkan keterampilan digital</li>
                        <li>Membantu analisis data lebih efektif</li>
                        <li>Mendorong kreativitas dalam penyajian informasi</li>
                    </ul>
                </div>

                <div class="step-image-wrap">
                    <img src="{{ asset('storage/artikel/artikel_1_2.jpeg') }} " 
                        alt="Peserta Sharing Session" 
                        class="step-image">
                </div>
            </div>
        </div>
    </section>

</main>

<!-- ===== FOOTER ===== -->
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
/* ── Scroll Reveal ── */
const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            setTimeout(() => entry.target.classList.add('visible'), entry.target.dataset.delay || 0);
            revealObs.unobserve(entry.target);
        }
    });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach((el, i) => {
    el.dataset.delay = (i % 3) * 100;
    revealObs.observe(el);
});

/* ── Active Nav ── */
const navLinks = document.querySelectorAll('.nav-links a');
const navObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            navLinks.forEach(link => {
                link.style.background = '';
                link.style.color = '';
                if (link.getAttribute('href') === '#' + entry.target.id) {
                    link.style.background = '#eff6ff';
                    link.style.color = '#1d6fca';
                }
            });
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('section[id]').forEach(s => navObs.observe(s));

/* ── Mobile Menu ── */
const toggle = document.querySelector('.nav-toggle');
const menu   = document.querySelector('.mobile-menu');
if (toggle && menu) {
    toggle.addEventListener('click', () => {
        const open = menu.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open);
        menu.setAttribute('aria-hidden', !open);
    });
    menu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            menu.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
            menu.setAttribute('aria-hidden', 'true');
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('newsSlider');
        const slides = slider.children;
        let index = 0;

        function nextSlide() {
            index++;
            if (index >= slides.length) {
                index = 0;
            }
            slider.style.transform = `translateX(-${index * 100}%)`;
        }

        setInterval(nextSlide, 5000);
    });

/* ── Carousel Factory ── */
function initCarousel({ wrapperId, dotsId, prevId, nextId, interval = 5000 }) {
    const wrapper = document.getElementById(wrapperId);
    if (!wrapper) return;
    const dotsWrap = document.getElementById(dotsId);
    const dots     = dotsWrap ? Array.from(dotsWrap.querySelectorAll('.dot')) : [];
    const total    = wrapper.children.length;
    let cur = 0, timer;

    const go = (n) => {
        cur = ((n % total) + total) % total;
        wrapper.style.transform = `translateX(-${cur * 100}%)`;
        dots.forEach((d, i) => d.classList.toggle('active', i === cur));
    };

    const reset = () => { clearInterval(timer); timer = setInterval(() => go(cur + 1), interval); };

    document.getElementById(prevId)?.addEventListener('click', () => { go(cur - 1); reset(); });
    document.getElementById(nextId)?.addEventListener('click', () => { go(cur + 1); reset(); });
    dots.forEach((d, i) => d.addEventListener('click', () => { go(i); reset(); }));

    /* Touch swipe */
    let sx = 0;
    const root = wrapper.closest('.process-carousel, .testi-carousel') || wrapper.parentElement;
    root.addEventListener('touchstart', e => { sx = e.changedTouches[0].screenX; }, { passive: true });
    root.addEventListener('touchend',   e => {
        const dx = sx - e.changedTouches[0].screenX;
        if (Math.abs(dx) > 50) { go(cur + (dx > 0 ? 1 : -1)); reset(); }
    });

    go(0);
    reset();
}

initCarousel({ wrapperId:'process-wrapper', dotsId:'process-dots', prevId:'process-prev', nextId:'process-next' });
initCarousel({ wrapperId:'testi-wrapper',   dotsId:'testi-dots',   prevId:'testi-prev',   nextId:'testi-next' });
</script>

</body>
</html>