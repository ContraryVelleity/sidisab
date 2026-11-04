<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDISAB — Pemetaan & Alat Bantu Disabilitas Dapil III Kota Tangerang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --bg-canvas: #FAF9F5;
            --bg-surface: #FFFFFF;
            --bg-subtle: #F3F1EC;
            --text-primary: #141619;
            --text-secondary: #525866;
            --accent-primary: #0F52BA;
            --accent-hover: #0A3B8C;
            --accent-soft: #EBF1FA;
            --border-hairline: #E4E2DD;
            --status-wait-bg: #FEF6E6;
            --status-wait-text: #8A5300;
            --status-proc-bg: #EBF3FE;
            --status-proc-text: #0B4A99;
            --status-done-bg: #E7F6ED;
            --status-done-text: #0D6333;
            --radius-card: 16px;
            --radius-control: 10px;
            --font-scale: 1;
        }

        html[data-contrast="high"] {
            --bg-canvas: #000000;
            --bg-surface: #0D0D0D;
            --bg-subtle: #1A1A1A;
            --text-primary: #FFFFFF;
            --text-secondary: #E0E0E0;
            --accent-primary: #FFD700;
            --accent-hover: #FFEA70;
            --accent-soft: #262105;
            --border-hairline: #555555;
            --status-wait-bg: #332600;
            --status-wait-text: #FFD700;
            --status-proc-bg: #002244;
            --status-proc-text: #70B5FF;
            --status-done-bg: #052E16;
            --status-done-text: #6EE7B7;
        }

        html {
            font-size: calc(16px * var(--font-scale));
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Atkinson Hyperlegible', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            line-height: 1.65;
            letter-spacing: 0.01em;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        /* Skip Link untuk Screen Reader & Navigasi Keyboard */
        .skip-link {
            position: absolute;
            top: -100px;
            left: 1rem;
            background: var(--accent-primary);
            color: #fff;
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius-control);
            z-index: 9999;
            font-weight: 700;
            text-decoration: none;
            transition: top 0.2s ease;
        }
        .skip-link:focus {
            top: 1rem;
            outline: 3px solid #FFD700;
        }

        /* Fokus Keyboard Jelas (WCAG 2.4.7) */
        :focus-visible {
            outline: 3px solid var(--accent-primary) !important;
            outline-offset: 2px !important;
        }

        /* Top Accessibility Utility Bar */
        .a11y-bar {
            background-color: var(--bg-subtle);
            border-bottom: 1px solid var(--border-hairline);
            font-size: 0.875rem;
            padding: 0.5rem 0;
        }

        .a11y-btn {
            background: var(--bg-surface);
            color: var(--text-primary);
            border: 1px solid var(--border-hairline);
            border-radius: 6px;
            padding: 0.2rem 0.6rem;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .a11y-btn:hover, .a11y-btn[aria-pressed="true"] {
            background: var(--accent-primary);
            color: var(--bg-surface);
            border-color: var(--accent-primary);
        }

        /* Minimalist Navbar */
        .navbar-minimal {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-hairline);
            padding: 1.15rem 0;
        }

        .nav-link-minimal {
            color: var(--text-secondary);
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            border-radius: var(--radius-control);
            transition: color 0.15s ease, background-color 0.15s ease;
        }
        .nav-link-minimal:hover, .nav-link-minimal.active {
            color: var(--text-primary);
            background-color: var(--bg-subtle);
        }

        /* Pastikan warna menu menang dari aturan bawaan Bootstrap .navbar-nav .nav-link */
        .navbar-minimal .navbar-nav .nav-link.nav-link-minimal {
            color: var(--text-secondary) !important;
            opacity: 1;
        }
        .navbar-minimal .navbar-nav .nav-link.nav-link-minimal:hover,
        .navbar-minimal .navbar-nav .nav-link.nav-link-minimal:focus,
        .navbar-minimal .navbar-nav .nav-link.nav-link-minimal.active {
            color: var(--text-primary) !important;
            background-color: var(--bg-subtle);
        }
        html[data-contrast="high"] .navbar-minimal .navbar-nav .nav-link.nav-link-minimal {
            color: #FFFFFF !important;
        }
        html[data-contrast="high"] .navbar-minimal .navbar-nav .nav-link.nav-link-minimal:hover,
        html[data-contrast="high"] .navbar-minimal .navbar-nav .nav-link.nav-link-minimal:focus,
        html[data-contrast="high"] .navbar-minimal .navbar-nav .nav-link.nav-link-minimal.active {
            color: #FFD700 !important;
            background-color: #262105;
        }
        html[data-contrast="high"] .navbar-minimal .navbar-brand,
        html[data-contrast="high"] .navbar-minimal .navbar-brand .text-muted-custom,
        html[data-contrast="high"] .navbar-minimal .navbar-toggler {
            color: #FFFFFF !important;
        }
        html[data-contrast="high"] .text-muted-custom {
            color: #FFFFFF !important;
        }

        /* Surface & Cards */
        .surface-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-hairline);
            border-radius: var(--radius-card);
            box-shadow: 0 2px 10px rgba(20, 22, 25, 0.02);
        }

        /* Buttons Minimalist */
        .btn-civic-primary {
            background-color: var(--accent-primary);
            color: var(--bg-surface);
            border: 1px solid var(--accent-primary);
            border-radius: var(--radius-control);
            padding: 0.65rem 1.35rem;
            font-weight: 700;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: transform 0.15s ease, background-color 0.15s ease;
        }
        .btn-civic-primary:hover {
            background-color: var(--accent-hover);
            color: var(--bg-surface);
            transform: translateY(-1px);
        }

        .btn-civic-outline {
            background-color: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border-hairline);
            border-radius: var(--radius-control);
            padding: 0.65rem 1.35rem;
            font-weight: 600;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }
        .btn-civic-outline:hover {
            background-color: var(--bg-subtle);
            color: var(--text-primary);
            border-color: var(--text-secondary);
        }

        /* Form Controls Ramah Disabilitas */
        .form-control, .form-select {
            background-color: var(--bg-surface);
            color: var(--text-primary);
            border: 1.5px solid var(--border-hairline);
            border-radius: var(--radius-control);
            min-height: 46px;
            padding: 0.625rem 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--bg-surface);
            color: var(--text-primary);
            border-color: var(--accent-primary);
            box-shadow: none;
        }

        /* Pill Badges */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-size: 0.825rem;
            font-weight: 700;
        }
        .status-Menunggu { background: var(--status-wait-bg); color: var(--status-wait-text); }
        .status-Diproses { background: var(--status-proc-bg); color: var(--status-proc-text); }
        .status-Terealisasi { background: var(--status-done-bg); color: var(--status-done-text); }

        .text-muted-custom {
            color: var(--text-secondary) !important;
        }

        /* Custom Marker Pin Peta */
        .custom-map-pin {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 3px solid #FFFFFF;
            box-shadow: 0 2px 6px rgba(0,0,0,0.35);
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Lewati ke Konten Utama</a>

    <!-- Bar Aksesibilitas Ramah Disabilitas -->
    <div class="a11y-bar" role="region" aria-label="Pengaturan Aksesibilitas Tampilan">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="text-muted-custom d-flex align-items-center gap-2">
                <i class="bi bi-universal-access-circle" aria-hidden="true"></i>
                <span>Portal Ramah Aksesibilitas — Wilayah Dapil III (Kec. Cipondoh &amp; Kec. Pinang)</span>
            </span>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted-custom me-1">Ukuran Teks:</span>
                <button type="button" class="a11y-btn" onclick="setFontScale(0.95)" title="Perkecil Teks" aria-label="Perkecil Ukuran Teks">A-</button>
                <button type="button" class="a11y-btn" onclick="setFontScale(1)" title="Ukuran Teks Normal" aria-label="Ukuran Teks Normal">A</button>
                <button type="button" class="a11y-btn" onclick="setFontScale(1.15)" title="Perbesar Teks" aria-label="Perbesar Ukuran Teks">A+</button>
                <span class="text-muted-custom mx-1">|</span>
                <button type="button" id="btnContrast" class="a11y-btn d-inline-flex align-items-center gap-1" onclick="toggleHighContrast()" aria-pressed="false">
                    <i class="bi bi-circle-half" aria-hidden="true"></i> Kontras Tinggi
                </button>
            </div>
        </div>
    </div>

    <!-- Navigasi Utama Minimalist -->
    <nav class="navbar navbar-expand-lg navbar-minimal sticky-top" aria-label="Navigasi Utama">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-decoration-none" href="<?php echo e(route('public.landing')); ?>" style="color: var(--text-primary);">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3" style="width: 38px; height: 38px; background: var(--accent-soft); color: var(--accent-primary);">
                    <i class="bi bi-geo-alt-fill fs-5" aria-hidden="true"></i>
                </span>
                <div>
                    <span class="fs-5 tracking-tight">SIDISAB</span>
                    <span class="d-block text-muted-custom fw-normal" style="font-size: 0.72rem; line-height: 1;">Dapil III Kota Tangerang</span>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Buka Menu Navigasi">
                <i class="bi bi-list fs-2" style="color: var(--text-primary);"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-1 my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-minimal <?php echo e(request()->routeIs('public.landing') ? 'active' : ''); ?>" href="<?php echo e(route('public.landing')); ?>">Beranda &amp; Peta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-minimal" href="<?php echo e(route('public.landing')); ?>#cek-status">Cek Status NIK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-minimal <?php echo e(request()->routeIs('public.ajukan') ? 'active' : ''); ?>" href="<?php echo e(route('public.ajukan')); ?>">Ajukan Alat Bantu</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn-civic-primary text-decoration-none">
                            <i class="bi bi-grid-1x2" aria-hidden="true"></i> Panel Staf
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn-civic-outline text-decoration-none">
                            <i class="bi bi-lock" aria-hidden="true"></i> Login Staf
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main id="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="mt-5 py-5" style="border-top: 1px solid var(--border-hairline); background-color: var(--bg-surface);">
        <div class="container">
            <div class="row align-items-center justify-content-between g-3">
                <div class="col-md-7">
                    <p class="fw-bold mb-1">SIDISAB — Sistem Informasi Disabilitas dan Alat Bantu</p>
                    <p class="text-muted-custom small mb-0">Pemetaan Geografis dan Pendataan Alat Bantu Penyandang Disabilitas Wilayah Dapil III Kota Tangerang (Kecamatan Cipondoh &amp; Kecamatan Pinang).</p>
                </div>
                <div class="col-md-5 text-md-end">
                    <small class="text-muted-custom">&copy; <?php echo e(date('Y')); ?> Tim Pendata Dapil III Kota Tangerang.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setFontScale(scale) {
            document.documentElement.style.setProperty('--font-scale', scale);
            localStorage.setItem('sidisab_font_scale', scale);
        }

        function toggleHighContrast() {
            const html = document.documentElement;
            const current = html.getAttribute('data-contrast') === 'high';
            const nextState = !current;
            if (nextState) {
                html.setAttribute('data-contrast', 'high');
            } else {
                html.removeAttribute('data-contrast');
            }
            const btn = document.getElementById('btnContrast');
            if (btn) btn.setAttribute('aria-pressed', nextState ? 'true' : 'false');
            localStorage.setItem('sidisab_high_contrast', nextState ? '1' : '0');
        }

        (function initA11yPreferences() {
            const savedScale = localStorage.getItem('sidisab_font_scale');
            if (savedScale) document.documentElement.style.setProperty('--font-scale', savedScale);

            const savedContrast = localStorage.getItem('sidisab_high_contrast');
            if (savedContrast === '1') {
                document.documentElement.setAttribute('data-contrast', 'high');
                const btn = document.getElementById('btnContrast');
                if (btn) btn.setAttribute('aria-pressed', 'true');
            }
        })();

        // Helper pencegah XSS untuk Popup Peta Leaflet
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\sidisab\resources\views/layouts/public.blade.php ENDPATH**/ ?>