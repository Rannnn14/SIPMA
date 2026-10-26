<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | SIPMA - Sistem Pendataan Pelanggaran Mahasiswa</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 4.6 & FontAwesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #ccfbf1;
            --primary-gradient: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            --accent-cyan: #14b8a6;
            --surface: #ffffff;
            --bg-page: #f8fafc;
            --text-heading: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --shadow-card: 0 20px 40px -15px rgba(13, 148, 136, 0.12), 0 0 0 1px rgba(226, 232, 240, 0.8);
            --shadow-hover: 0 25px 50px -12px rgba(13, 148, 136, 0.2);
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-body);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                radial-gradient(circle at 10% 15%, rgba(20, 184, 166, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(13, 148, 136, 0.08) 0%, transparent 45%);
        }

        /* Top Navbar - Responsive */
        .top-navbar {
            background: #ffffff;
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            position: relative;
            z-index: 10;
        }
        @media (min-width: 768px) {
            .top-navbar {
                padding: 14px 28px;
            }
        }
        .brand-logo-container {
            display: flex;
            align-items: center;
            text-decoration: none !important;
        }
        .brand-logo-icon {
            display: block;
            width: clamp(140px, 24vw, 200px);
            height: auto;
            aspect-ratio: 1737 / 688;
            object-fit: contain;
            background: transparent;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        @media (min-width: 768px) {
            .brand-logo-icon {
                height: auto;
            }
        }
        .brand-logo-container:hover .brand-logo-icon {
            transform: scale(1.05);
        }
        .brand-name {
            color: #ffffff;
            font-weight: 800;
            font-size: clamp(1.05rem, 3.5vw, 1.3rem);
            letter-spacing: 0.5px;
            line-height: 1.1;
        }
        .brand-sub {
            color: #ccfbf1;
            font-size: clamp(0.62rem, 1.8vw, 0.72rem);
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .nav-status-badge {
            background: #f0fdfa;
            color: #0f766e;
            font-size: 0.76rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 30px;
            backdrop-filter: blur(8px);
            border: 1px solid #ccfbf1;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .pulse-dot {
            width: 7px;
            height: 7px;
            background-color: #4ade80;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.4);
        }

        /* Main Container - Responsive Padding */
        .main-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 24px 16px;
        }
        @media (min-width: 768px) {
            .main-wrapper {
                padding: 40px 24px;
            }
        }
        @media (min-width: 1200px) {
            .main-wrapper {
                padding: 48px 30px;
            }
        }
        .main-section {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
        }

        /* Left Side: About SIPMA & Feature Cards */
        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ccfbf1;
            color: #0f766e;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        .section-title {
            color: var(--text-heading);
            font-size: clamp(1.45rem, 3.2vw, 2.25rem);
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }
        .section-title .text-highlight {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .section-desc {
            color: var(--text-body);
            font-size: clamp(0.88rem, 1.5vw, 0.98rem);
            line-height: 1.7;
            margin-bottom: 24px;
        }
        @media (min-width: 768px) {
            .section-desc {
                margin-bottom: 30px;
            }
        }

        /* Features Grid - Fully Responsive */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }
        @media (min-width: 576px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }
        .feature-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            transition: all 0.25s ease;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        @media (min-width: 768px) {
            .feature-box {
                padding: 18px;
            }
        }
        .feature-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(2, 132, 199, 0.1);
            border-color: #cbd5e1;
        }
        .feature-icon-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .badge-cyan { background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); }
        .badge-coral { background: linear-gradient(135deg, #fb7185 0%, #e11d48 100%); }
        .badge-gold { background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); }
        .badge-emerald { background: linear-gradient(135deg, #34d399 0%, #059669 100%); }

        .feature-box-content {
            flex: 1;
        }
        .feature-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .feature-desc {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 0;
        }

        /* Modern Login Card - Responsive */
        .login-card-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(226, 232, 240, 0.9);
            overflow: hidden;
            position: relative;
            transition: box-shadow 0.3s ease;
            width: 100%;
        }
        @media (min-width: 768px) {
            .login-card-container {
                border-radius: 24px;
            }
        }
        .login-card-container:hover {
            box-shadow: var(--shadow-hover);
        }

        /* Top decorative gradient bar */
        .login-card-top-bar {
            height: 5px;
            background: linear-gradient(90deg, #0d9488 0%, #14b8a6 50%, #0f766e 100%);
        }

        .login-card-header {
            padding: 24px 20px 14px 20px;
            text-align: center;
        }
        @media (min-width: 768px) {
            .login-card-header {
                padding: 30px 28px 16px 28px;
            }
        }
        .login-icon-avatar {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);
            color: #0d9488;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 12px;
            box-shadow: 0 8px 18px rgba(2, 132, 199, 0.16);
        }
        @media (min-width: 768px) {
            .login-icon-avatar {
                width: 58px;
                height: 58px;
                font-size: 1.5rem;
                border-radius: 18px;
                margin-bottom: 14px;
            }
        }
        .login-card-title {
            font-size: clamp(1.35rem, 2.5vw, 1.65rem);
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }
        .login-card-subtitle {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0;
        }

        .login-card-body {
            padding: 8px 20px 24px 20px;
        }
        @media (min-width: 768px) {
            .login-card-body {
                padding: 10px 28px 28px 28px;
            }
        }

        /* Field Labels */
        .form-field-group {
            margin-bottom: 16px;
        }
        .field-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }

        /* Modern Input Group */
        .input-group-modern {
            display: flex;
            align-items: center;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            background: #ffffff;
            overflow: hidden;
            transition: all 0.2s ease-in-out;
            min-height: 48px;
        }
        .input-group-modern:focus-within {
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.14);
            background: #ffffff;
        }
        .input-icon {
            color: #94a3b8;
            padding: 0 12px 0 14px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: color 0.2s ease;
        }
        .input-group-modern:focus-within .input-icon {
            color: #0d9488;
        }
        .input-field-modern {
            border: none;
            outline: none;
            padding: 12px 12px 12px 0;
            font-size: 16px; /* 16px prevents iOS Safari auto-zoom on focus */
            width: 100%;
            color: #0f172a;
            font-family: inherit;
            background: transparent;
        }
        @media (min-width: 768px) {
            .input-field-modern {
                font-size: 0.92rem;
            }
        }
        .input-field-modern::placeholder {
            color: #94a3b8;
            font-size: 0.85rem;
        }

        /* Password Eye Toggle */
        .btn-toggle-eye {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding: 0 14px;
            cursor: pointer;
            outline: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            transition: color 0.2s ease;
        }
        .btn-toggle-eye:hover {
            color: #0d9488;
        }

        /* Custom Remember Me Checkbox */
        .remember-checkbox-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            user-select: none;
        }
        .remember-checkbox-wrapper input[type="checkbox"] {
            accent-color: #0d9488;
            width: 17px;
            height: 17px;
            margin-right: 8px;
            cursor: pointer;
        }
        .remember-checkbox-wrapper label {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0;
            cursor: pointer;
            font-weight: 500;
        }
        .remember-checkbox-wrapper label:hover {
            color: #334155;
        }

        /* Modern Submit Button */
        .btn-login-submit {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            padding: 13px 20px;
            min-height: 48px;
            border-radius: 12px;
            border: none;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 20px rgba(2, 132, 199, 0.32);
        }
        .btn-login-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(2, 132, 199, 0.42);
            filter: brightness(1.04);
            color: #ffffff;
        }
        .btn-login-submit:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        }

        /* Secure Badge at Bottom of Card */
        .login-secure-footer {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 0.76rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .login-secure-footer i {
            color: #0d9488;
            font-size: 0.85rem;
        }

        /* Custom Alert Notification */
        .alert-custom-login {
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.82rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            border: 1px solid transparent;
        }
        .alert-custom-login.alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #fee2e2;
        }
        .alert-custom-login.alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #dcfce7;
        }

        /* Bottom Banner - Responsive */
        .bottom-banner {
            background: linear-gradient(135deg, #0d9488 0%, #134e4a 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }
        @media (min-width: 768px) {
            .bottom-banner {
                padding: 40px 25px;
            }
        }
        .bottom-title {
            font-size: clamp(1.2rem, 2.5vw, 1.5rem);
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.2px;
        }
        .bottom-desc {
            max-width: 820px;
            margin: 0 auto;
            font-size: clamp(0.8rem, 1.5vw, 0.88rem);
            line-height: 1.65;
            color: #ccfbf1;
        }

        /* =========================================================
           CAPTCHA WIDGET (reCAPTCHA Style)
           ========================================================= */
        .recaptcha-box {
            background-color: #f9f9f9;
            border: 1px solid #d3d3d3;
            border-radius: 4px;
            width: 100%;
            max-width: 320px;
            height: 74px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            user-select: none;
        }
        .recaptcha-left {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }
        .recaptcha-checkbox {
            width: 26px;
            height: 26px;
            border: 2px solid #c1c1c1;
            border-radius: 3px;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .recaptcha-checkbox:hover {
            border-color: #b2b2b2;
        }
        .recaptcha-checkbox.checked {
            border-color: #0f9d58;
            background-color: #0f9d58;
        }
        .recaptcha-checkbox.checked i {
            color: #ffffff;
            font-size: 14px;
            display: block;
        }
        .recaptcha-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #222;
            margin: 0;
            cursor: pointer;
        }
        .recaptcha-right {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
        }
        .recaptcha-logo-icon {
            width: 32px;
            height: 32px;
        }
        .recaptcha-brand-text {
            font-size: 10px;
            color: #555;
            font-family: Roboto, helvetica, arial, sans-serif;
            font-weight: 500;
            letter-spacing: 0.2px;
            margin-top: 1px;
        }
        .recaptcha-links {
            font-size: 8px;
            color: #555;
            display: flex;
            gap: 4px;
        }
        .recaptcha-links a {
            color: #555;
            text-decoration: none;
        }
        .recaptcha-hint-text {
            font-size: 0.82rem;
            color: #a30000;
            font-style: italic;
            margin-top: 6px;
            font-weight: 600;
            display: block;
        }
    </style>
</head>
<body>

    <!-- TOP NAVBAR -->
    <header class="top-navbar">
        <div class="d-flex align-items-center justify-content-between mx-auto" style="max-width: 1240px;">
            <a href="{{ url('/') }}" class="brand-logo-container">
                <img src="{{ asset('img/sipma-logo-horizontal.png') }}?v=20261024-4" class="brand-logo-icon" alt="SIPMA — Sistem Pendataan Pelanggaran Mahasiswa">
            </a>
            <div class="d-none d-sm-flex align-items-center">
                <span class="nav-status-badge">
                    <span class="pulse-dot"></span>
                    Portal Aktif &amp; Terverifikasi
                </span>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT ROW: ABOUT & CARDS + MODERN LOGIN FORM -->
    <div class="main-wrapper">
        <main class="main-section">
            <div class="row align-items-center">
                
                <!-- SISI KIRI (DESKTOP) / SISI BAWAH (MOBILE): INFORMASI, KELEBIHAN & FITUR UTAMA -->
                <div class="col-lg-7 pr-lg-5 order-2 order-lg-1 mt-4 mt-lg-0">
                    <div class="hero-pill">
                        <i class="fas fa-check-circle"></i>
                        Sistem Kedisiplinan Digital
                    </div>
                    <h1 class="section-title">
                        Platform Terpadu Pengelolaan <span class="text-highlight">Tata Tertib Mahasiswa</span>
                    </h1>
                    <p class="section-desc">
                        <strong>SIPMA (Sistem Pendataan Pelanggaran Mahasiswa)</strong> adalah platform digital terpadu untuk mencatat, mengelola, dan memonitor penegakan kedisiplinan serta tata tertib di lingkungan kampus secara cepat, transparan, dan akurat.
                    </p>

                    <!-- 4 Kartu Kelebihan & Fitur Utama (Responsive) -->
                    <div class="features-grid">
                        
                        <!-- Kelebihan / Fitur 1 -->
                        <div class="feature-box">
                            <div class="feature-icon-badge badge-cyan">
                                <i class="fas fa-laptop-file"></i>
                            </div>
                            <div class="feature-box-content">
                                <h6 class="feature-title">Pelaporan Cepat &amp; Praktis</h6>
                                <p class="feature-desc">Penginputan laporan pelanggaran secara daring yang mudah, dilengkapi fitur unggah bukti dokumen, foto, atau video.</p>
                            </div>
                        </div>

                        <!-- Kelebihan / Fitur 2 -->
                        <div class="feature-box">
                            <div class="feature-icon-badge badge-coral">
                                <i class="fas fa-clock-rotate-left"></i>
                            </div>
                            <div class="feature-box-content">
                                <h6 class="feature-title">Transparansi Real-Time</h6>
                                <p class="feature-desc">Pantau perkembangan status penanganan laporan secara terbuka, akurat, dan dapat dicek langsung kapan saja.</p>
                            </div>
                        </div>

                        <!-- Kelebihan / Fitur 3 -->
                        <div class="feature-box">
                            <div class="feature-icon-badge badge-gold">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <div class="feature-box-content">
                                <h6 class="feature-title">Keamanan &amp; Data Terpusat</h6>
                                <p class="feature-desc">Seluruh rekam data kedisiplinan tersimpan rapi dan terenkripsi dalam database kampus yang aman dan terpadu.</p>
                            </div>
                        </div>

                        <!-- Kelebihan / Fitur 4 -->
                        <div class="feature-box">
                            <div class="feature-icon-badge badge-emerald">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div class="feature-box-content">
                                <h6 class="feature-title">Statistik &amp; Rekapitulasi</h6>
                                <p class="feature-desc">Visualisasi grafik tren pelanggaran serta kemudahan ekspor dokumen rekap data untuk evaluasi berkala kampus.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SISI KANAN (DESKTOP) / SISI ATAS (MOBILE): FORM LOGIN BERSIH & MODERN -->
                <div class="col-lg-5 order-1 order-lg-2">
                    <div class="login-card-container">
                        <div class="login-card-top-bar"></div>
                        
                        <div class="login-card-header">
                            <div class="login-icon-avatar">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h2 class="login-card-title">Masuk ke SIPMA</h2>
                            <p class="login-card-subtitle">Silakan masukkan akun Anda untuk melanjutkan</p>
                        </div>

                        <div class="login-card-body">
                            
                            <!-- Alert Kesalahan / Sukses -->
                            @if ($errors->any())
                                <div class="alert-custom-login alert-danger">
                                    <i class="fas fa-circle-exclamation text-danger flex-shrink-0"></i>
                                    <span>{{ $errors->first() }}</span>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert-custom-login alert-success">
                                    <i class="fas fa-circle-check text-success flex-shrink-0"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert-custom-login alert-danger">
                                    <i class="fas fa-circle-exclamation text-danger flex-shrink-0"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                            @endif

                            <!-- Form Autentikasi -->
                            <form action="{{ route('login') }}" method="POST" id="loginForm">
                                @csrf

                                <!-- Input Identitas (NIM / Email / Username) -->
                                <div class="form-field-group">
                                    <label class="field-label" for="loginInput">
                                        NIM / Email / Username
                                    </label>
                                    <div class="input-group-modern">
                                        <div class="input-icon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <input type="text"
                                               name="login"
                                               id="loginInput"
                                               value="{{ old('login') }}"
                                               required
                                               autofocus
                                               class="input-field-modern"
                                               placeholder="Masukkan NIM atau email">
                                    </div>
                                    <p style="font-size:0.74rem; color:#94a3b8; margin-top:5px; margin-bottom:0;">
                                        <i class="fas fa-circle-info" style="color:#0d9488;"></i>
                                        Mahasiswa: gunakan NIM portal kampus. Staf: gunakan email/username.
                                    </p>
                                </div>

                                <!-- Input Kata Sandi -->
                                <div class="form-field-group">
                                    <label class="field-label" for="passwordInput">
                                        Kata Sandi
                                    </label>
                                    <div class="input-group-modern">
                                        <div class="input-icon">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <input type="password"
                                               name="password"
                                               id="passwordInput"
                                               required
                                               class="input-field-modern"
                                               placeholder="Masukkan kata sandi portal">
                                        <button type="button" class="btn-toggle-eye" id="btnTogglePassword" title="Tampilkan / Sembunyikan Kata Sandi">
                                            <i class="fas fa-eye" id="iconEye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- RECAPTCHA WIDGET ("Saya bukan robot") -->
                                <div style="margin-bottom: 18px;">
                                    <div class="recaptcha-box" id="recaptchaBox" style="{{ $errors->has('captcha') ? 'border-color: #dc2626;' : '' }}">
                                        <div class="recaptcha-left" onclick="toggleCaptcha()">
                                            <div class="recaptcha-checkbox" id="recaptchaCheck">
                                                <i class="fas fa-check" id="recaptchaCheckIcon" style="display: none; color: #fff; font-size: 14px;"></i>
                                            </div>
                                            <label class="recaptcha-label">Saya bukan robot</label>
                                            <input type="hidden" name="captcha_verified" id="captchaVerified" value="0">
                                        </div>
                                        <div class="recaptcha-right">
                                            <svg class="recaptcha-logo-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M42 24C42 14.0589 33.9411 6 24 6C17.0601 6 11.0855 9.94052 8.16335 15.6885L13.5684 18.391C15.6178 14.3638 19.4975 11.6 24 11.6C30.8485 11.6 36.4 17.1515 36.4 24C36.4 30.8485 30.8485 36.4 24 36.4C19.4975 36.4 15.6178 33.6362 13.5684 29.609L8.16335 32.3115C11.0855 38.0595 17.0601 42 24 42C33.9411 42 42 33.9411 42 24Z" fill="#1A73E8"/>
                                                <path d="M6 24C6 26.6853 6.58784 29.2335 7.64385 31.5245L13.0489 28.822C12.3789 27.3512 12 25.7206 12 24C12 22.2794 12.3789 20.6488 13.0489 19.178L7.64385 16.4755C6.58784 18.7665 6 21.3147 6 24Z" fill="#9AA0A6"/>
                                            </svg>
                                            <div class="recaptcha-brand-text">reCAPTCHA</div>
                                            <div class="recaptcha-links">
                                                <span>Privasi</span> - <span>Persyaratan</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="recaptcha-hint-text">
                                        * Centang captcha di atas untuk melengkapi validasi
                                    </div>
                                    @error('captcha')
                                        <div class="text-danger small font-weight-bold mt-1">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Checkbox Ingat Saya -->
                                <div class="remember-checkbox-wrapper" style="margin-top:18px;">
                                    <input type="checkbox" name="remember" id="rememberMe">
                                    <label for="rememberMe">Ingat saya di perangkat ini</label>
                                </div>

                                <!-- Tombol Masuk Utama -->
                                <button type="submit" class="btn-login-submit" id="btnLoginSubmit">
                                    <span>Masuk Sekarang</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </form>

                            <!-- Badge Keamanan Resmi -->
                            <div class="login-secure-footer">
                                <i class="fas fa-shield-halved"></i>
                                <span>Akses Terverifikasi &amp; Terlindungi · Bridge API Portal Kampus</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- BOTTOM BANNER -->
    <footer class="bottom-banner">
        <h3 class="bottom-title">SIPMA Information &amp; Compliance</h3>
        <p class="bottom-desc">
            Sistem Pendataan Pelanggaran Mahasiswa (SIPMA) mendukung terwujudnya ketertiban, keadilan, dan integritas etika di lingkungan perguruan tinggi melalui platform pencatatan digital yang modern, akuntabel, dan terpercaya.
        </p>
    </footer>

    <!-- Scripts -->
    <script>
        // Toggle reCAPTCHA check
        function toggleCaptcha() {
            const check = document.getElementById('recaptchaCheck');
            const icon = document.getElementById('recaptchaCheckIcon');
            const input = document.getElementById('captchaVerified');
            const box = document.getElementById('recaptchaBox');

            if (input.value === '1') {
                input.value = '0';
                check.classList.remove('checked');
                icon.style.display = 'none';
            } else {
                input.value = '1';
                check.classList.add('checked');
                icon.style.display = 'block';
                box.style.borderColor = '#0f9d58';
            }
        }

        // Show/Hide Password toggle
        document.getElementById('btnTogglePassword').addEventListener('click', function () {
            const pwd  = document.getElementById('passwordInput');
            const icon = document.getElementById('iconEye');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        // Loading state saat form submit
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnLoginSubmit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin" style="margin-right:8px;"></i> <span>Memeriksa...</span>';
        });
    </script>
</body>
</html>
