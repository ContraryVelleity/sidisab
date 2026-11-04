<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otentikasi Staf — SIDISAB Dapil III Kota Tangerang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-canvas: #FAF9F5;
            --bg-surface: #FFFFFF;
            --bg-subtle: #F3F1EC;
            --ink-deep: #0B192C;
            --ink-navy: #0D274D;
            --text-primary: #141619;
            --text-secondary: #525866;
            --accent-primary: #0F52BA;
            --accent-hover: #0A3B8C;
            --accent-soft: #EBF1FA;
            --border-hairline: #E4E2DD;
        }

        html[data-contrast="high"] {
            --bg-canvas: #000000;
            --bg-surface: #0A0A0A;
            --bg-subtle: #1A1A1A;
            --ink-deep: #000000;
            --ink-navy: #0D0D0D;
            --text-primary: #FFFFFF;
            --text-secondary: #E0E0E0;
            --accent-primary: #FFD700;
            --accent-hover: #FFEA70;
            --accent-soft: #262105;
            --border-hairline: #555555;
        }

        ::selection {
            background-color: var(--accent-primary);
            color: #FFFFFF;
        }

        body {
            font-family: 'Atkinson Hyperlegible', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, .display-voice {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.025em;
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        :focus-visible {
            outline: 3px solid var(--accent-primary) !important;
            outline-offset: 2px !important;
        }

        /* Bingkai Utama Split-Screen Padat */
        .auth-shell {
            width: 100%;
            max-width: 1120px;
            background-color: var(--bg-surface);
            border: 1px solid var(--border-hairline);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(11, 25, 44, 0.08);
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
        }

        @media (max-width: 991.98px) {
            .auth-shell {
                grid-template-columns: 1fr;
                max-width: 520px;
            }
        }

        /* Panel Kiri: Kanvas Kartografi GIS Dapil III */
        .gis-showcase {
            background: linear-gradient(155deg, var(--ink-deep) 0%, var(--ink-navy) 60%, #0F52BA 100%);
            color: #F8FAFC;
            padding: 2.25rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.5rem;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gis-grid-bg {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.045) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .gis-map-frame {
            position: relative;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
        }

        .pulse-node {
            transform-origin: center;
            animation: mapPulse 2.4s cubic-bezier(0.16, 1, 0.3, 1) infinite;
        }

        @keyframes mapPulse {
            0% { r: 5px; opacity: 0.9; }
            70% { r: 16px; opacity: 0; }
            100% { r: 16px; opacity: 0; }
        }

        .kelurahan-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            font-size: 0.76rem;
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #E2E8F0;
        }

        .workflow-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
        }

        .workflow-item {
            background: rgba(11, 25, 44, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 11px;
            padding: 0.7rem 0.85rem;
        }

        /* Panel Kanan: Form Otentikasi */
        .auth-form-panel {
            padding: 2.25rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: var(--bg-surface);
        }

        @media (max-width: 575.98px) {
            .gis-showcase, .auth-form-panel {
                padding: 1.5rem;
            }
            .workflow-strip {
                grid-template-columns: 1fr;
            }
        }

        .form-label-dense {
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
            color: var(--text-primary);
        }

        .input-group-minimal {
            position: relative;
        }

        .input-group-minimal .input-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        .form-control-auth {
            width: 100%;
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            border: 1.5px solid var(--border-hairline);
            border-radius: 10px;
            min-height: 44px;
            padding: 0.55rem 2.6rem 0.55rem 2.5rem;
            font-size: 0.95rem;
            transition: border-color 0.15s ease, background-color 0.15s ease;
        }

        .form-control-auth:focus {
            background-color: var(--bg-surface);
            border-color: var(--accent-primary);
            outline: none;
        }

        .btn-toggle-pass {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--text-secondary);
            padding: 0.35rem 0.5rem;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-toggle-pass:hover {
            color: var(--text-primary);
            background-color: var(--bg-subtle);
        }

        .btn-submit-auth {
            background-color: var(--accent-primary);
            color: var(--bg-surface);
            border: 1px solid var(--accent-primary);
            border-radius: 10px;
            min-height: 46px;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background-color 0.15s ease, transform 0.15s ease;
        }
        .btn-submit-auth:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
        }

        .demo-preset-box {
            background-color: var(--bg-subtle);
            border: 1px solid var(--border-hairline);
            border-radius: 12px;
            padding: 0.75rem 0.95rem;
        }

        .a11y-pill-btn {
            background: var(--bg-subtle);
            color: var(--text-primary);
            border: 1px solid var(--border-hairline);
            border-radius: 8px;
            padding: 0.25rem 0.65rem;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
        }
        .a11y-pill-btn:hover {
            border-color: var(--accent-primary);
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <!-- SISI KIRI: Visualisasi Peta Geografis Dapil III -->
        <section class="gis-showcase" aria-label="Informasi Geografis Wilayah Dapil III">
            <div class="gis-grid-bg" aria-hidden="true"></div>

            <!-- Bar Atas Identitas Sistem -->
            <div class="position-relative d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3" style="width: 38px; height: 38px; background: rgba(255,255,255,0.14); color: #60A5FA;">
                        <i class="bi bi-geo-alt-fill fs-5" aria-hidden="true"></i>
                    </span>
                    <div>
                        <span class="fw-bold tracking-tight d-block" style="font-size: 1.05rem; line-height: 1.1;">SIDISAB DAPIL III</span>
                        <span style="font-size: 0.75rem; color: #CBD5E1;">Sistem Informasi Disabilitas &amp; Alat Bantu</span>
                    </div>
                </div>
                <span class="kelurahan-chip tabular-nums">
                    <i class="bi bi-broadcast text-info" aria-hidden="true"></i> -6.1980° S, 106.6750° E
                </span>
            </div>

            <!-- Judul Utama & Kanvas Peta Vektor Interaktif -->
            <div class="position-relative my-1">
                <h1 class="h3 fw-bold mb-2 text-white" style="line-height: 1.22;">
                    Kendali Pemetaan Spasial &amp; Verifikasi Bantuan Disabilitas Kota Tangerang
                </h1>
                <p class="small mb-3" style="color: #CBD5E1; max-width: 58ch;">
                    Pusat pengelolaan titik koordinat tempat tinggal warga, pemantauan realisasi kursi roda, kruk, alat bantu dengar, serta pencetakan dokumen resmi Rapat Dengar Pendapat (RDP).
                </p>

                <!-- Ilustrasi Peta Spasial Kecamatan Cipondoh & Pinang -->
                <div class="gis-map-frame">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="fw-bold text-white"><i class="bi bi-layers-half text-info me-1" aria-hidden="true"></i> Peta Cakupan Operasional Dapil III</span>
                        <span class="tabular-nums" style="color: #93C5FD; font-size: 0.75rem;">2 Kecamatan &bull; 21 Kelurahan</span>
                    </div>

                    <svg viewBox="0 0 520 155" width="100%" height="145" role="img" aria-label="Peta skematik titik sebaran Kecamatan Cipondoh dan Kecamatan Pinang">
                        <!-- Garis Kontur Wilayah -->
                        <path d="M18,35 Q130,10 255,42 T495,28" fill="none" stroke="rgba(147,197,253,0.18)" stroke-width="1.5" stroke-dasharray="4 4"/>
                        <path d="M10,115 Q160,75 290,110 T505,95" fill="none" stroke="rgba(147,197,253,0.18)" stroke-width="1.5" stroke-dasharray="4 4"/>

                        <!-- Poligon Kecamatan Cipondoh -->
                        <polygon points="35,28 245,18 265,88 195,135 45,122" fill="rgba(59,130,246,0.16)" stroke="#60A5FA" stroke-width="1.5"/>
                        <text x="65" y="48" fill="#BFDBFE" font-size="11" font-weight="700" font-family="Plus Jakarta Sans, sans-serif">KEC. CIPONDOH (10 Kel.)</text>

                        <!-- Poligon Kecamatan Pinang -->
                        <polygon points="245,18 475,30 488,125 285,142 265,88" fill="rgba(99,102,241,0.16)" stroke="#818CF8" stroke-width="1.5"/>
                        <text x="288" y="48" fill="#C7D2FE" font-size="11" font-weight="700" font-family="Plus Jakarta Sans, sans-serif">KEC. PINANG (11 Kel.)</text>

                        <!-- Garis Penghubung Jaringan Spasial -->
                        <polyline points="95,82 175,98 225,68 335,92 425,74" fill="none" stroke="rgba(255,255,255,0.28)" stroke-width="1.2"/>

                        <!-- Titik Koordinat Cipondoh Makmur (Menunggu - Amber) -->
                        <circle cx="95" cy="82" r="5" fill="#F59E0B" class="pulse-node"/>
                        <circle cx="95" cy="82" r="5" fill="#FBBF24" stroke="#FFFFFF" stroke-width="1.5"/>
                        <text x="72" y="102" fill="#FDE68A" font-size="9.5" font-family="Atkinson Hyperlegible, sans-serif">Cipondoh Makmur</text>

                        <!-- Titik Koordinat Gondrong (Diproses - Biru) -->
                        <circle cx="175" cy="98" r="5" fill="#38BDF8" stroke="#FFFFFF" stroke-width="1.5"/>
                        <text x="155" y="116" fill="#BAE6FD" font-size="9.5" font-family="Atkinson Hyperlegible, sans-serif">Gondrong</text>

                        <!-- Titik Koordinat Poris Plawad -->
                        <circle cx="225" cy="68" r="4.5" fill="#34D399" stroke="#FFFFFF" stroke-width="1.5"/>
                        <text x="198" y="57" fill="#A7F3D0" font-size="9.5" font-family="Atkinson Hyperlegible, sans-serif">Poris Plawad</text>

                        <!-- Titik Koordinat Panunggangan Utara (Terealisasi - Hijau) -->
                        <circle cx="335" cy="92" r="5" fill="#10B981" class="pulse-node"/>
                        <circle cx="335" cy="92" r="5" fill="#34D399" stroke="#FFFFFF" stroke-width="1.5"/>
                        <text x="295" y="112" fill="#A7F3D0" font-size="9.5" font-family="Atkinson Hyperlegible, sans-serif">Panunggangan Utara</text>

                        <!-- Titik Koordinat Kunciran -->
                        <circle cx="425" cy="74" r="5" fill="#38BDF8" stroke="#FFFFFF" stroke-width="1.5"/>
                        <text x="405" y="93" fill="#BAE6FD" font-size="9.5" font-family="Atkinson Hyperlegible, sans-serif">Kunciran</text>
                    </svg>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-2 mt-1" style="border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.76rem; color: #CBD5E1;">
                        <div class="d-flex align-items-center gap-3">
                            <span><i class="bi bi-circle-fill text-warning me-1" style="font-size:0.5rem;"></i>Menunggu Verifikasi</span>
                            <span><i class="bi bi-circle-fill text-info me-1" style="font-size:0.5rem;"></i>Sedang Diproses</span>
                            <span><i class="bi bi-circle-fill text-success me-1" style="font-size:0.5rem;"></i>Alat Terealisasi</span>
                        </div>
                        <span class="tabular-nums">6 Ragam Disabilitas Terdata</span>
                    </div>
                </div>
            </div>

            <!-- 3 Pilar Alur Kerja Staf Admin -->
            <div class="position-relative workflow-strip">
                <div class="workflow-item">
                    <div class="fw-bold small text-white mb-1"><i class="bi bi-pin-map-fill text-info me-1" aria-hidden="true"></i> 1. Pemetaan GIS</div>
                    <div style="font-size: 0.75rem; color: #CBD5E1; line-height: 1.35;">Validasi titik koordinat rumah warga Cipondoh &amp; Pinang.</div>
                </div>
                <div class="workflow-item">
                    <div class="fw-bold small text-white mb-1"><i class="bi bi-person-wheelchair text-warning me-1" aria-hidden="true"></i> 2. Verifikasi Alat</div>
                    <div style="font-size: 0.75rem; color: #CBD5E1; line-height: 1.35;">Tindak lanjuti ajuan kursi roda, kruk, &amp; alat bantu.</div>
                </div>
                <div class="workflow-item">
                    <div class="fw-bold small text-white mb-1"><i class="bi bi-file-earmark-pdf-fill text-success me-1" aria-hidden="true"></i> 3. Laporan RDP</div>
                    <div style="font-size: 0.75rem; color: #CBD5E1; line-height: 1.35;">Ekspor bukti data resmi PDF &amp; Excel untuk Dinsos.</div>
                </div>
            </div>
        </section>

        <!-- SISI KANAN: Form Login Padat & Interaktif -->
        <main class="auth-form-panel">
            <!-- Bar Aksesibilitas & Navigasi Balik -->
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3" style="border-bottom: 1px solid var(--border-hairline);">
                <a href="{{ route('public.landing') }}" class="text-decoration-none small fw-bold d-inline-flex align-items-center gap-1" style="color: var(--text-secondary);">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> Peta Publik
                </a>
                <button type="button" class="a11y-pill-btn" onclick="toggleLoginContrast()" id="btnContrastLogin" aria-pressed="false">
                    <i class="bi bi-circle-half me-1" aria-hidden="true"></i> Kontras Tinggi
                </button>
            </div>

            <!-- Bagian Inti Form -->
            <div class="my-auto py-2">
                <div class="mb-3">
                    <h2 class="h4 fw-bold mb-1">Masuk ke Panel Staf</h2>
                    <p class="small mb-0" style="color: var(--text-secondary);">
                        Gunakan akun petugas pendata Dapil III untuk mengelola data warga dan status bantuan.
                    </p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small rounded-3 border-0 mb-3 d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label-dense">Alamat Email Staf</label>
                        <div class="input-group-minimal">
                            <i class="bi bi-envelope input-icon" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" class="form-control-auth"
                                   value="{{ old('email') }}" placeholder="admin@sidisab.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label-dense">Kata Sandi</label>
                        <div class="input-group-minimal">
                            <i class="bi bi-key input-icon" aria-hidden="true"></i>
                            <input type="password" id="password" name="password" class="form-control-auth"
                                   placeholder="Masukkan kata sandi" required>
                            <button type="button" class="btn-toggle-pass" onclick="togglePasswordVisibility()"
                                    id="btnTogglePass" aria-label="Tampilkan kata sandi" title="Tampilkan/Sembunyikan Kata Sandi">
                                <i class="bi bi-eye" id="eyeIcon" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 small">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember" style="color: var(--text-secondary);">
                                Ingat sesi perangkat ini
                            </label>
                        </div>
                        <span class="tabular-nums" style="color: var(--text-secondary); font-size: 0.78rem;">
                            <i class="bi bi-shield-check text-success" aria-hidden="true"></i> Sesi Terenkripsi
                        </span>
                    </div>

                    <button type="submit" class="btn-submit-auth mb-3">
                        <span>Masuk ke Dashboard Admin</span>
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>

                <!-- Panel Kredensial Demo Interaktif (Mengisi ruang secara fungsional) -->
                <div class="demo-preset-box">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold" style="font-size: 0.8rem;">
                            <i class="bi bi-terminal-fill me-1 text-primary" aria-hidden="true"></i> Akses Cepat Pengujian Lokal
                        </span>
                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill" style="font-size: 0.7rem;">Seeder Aktif</span>
                    </div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="small tabular-nums" style="font-size: 0.78rem; color: var(--text-secondary);">
                            <div>Email: <strong style="color: var(--text-primary);">admin@sidisab.com</strong></div>
                            <div>Sandi: <strong style="color: var(--text-primary);">password123</strong></div>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 fw-bold" style="font-size: 0.76rem;" onclick="fillDemoCredentials(false)">
                                Isi Form
                            </button>
                            <button type="button" class="btn btn-sm btn-dark rounded-2 fw-bold" style="font-size: 0.76rem;" onclick="fillDemoCredentials(true)">
                                Isi &amp; Masuk
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Ringkas Panel Kanan -->
            <div class="pt-3 mt-2 d-flex justify-content-between align-items-center small" style="border-top: 1px solid var(--border-hairline); font-size: 0.76rem; color: var(--text-secondary);">
                <span>Wilayah Kerja: Kec. Cipondoh &amp; Kec. Pinang</span>
                <a href="{{ route('public.ajukan') }}" class="fw-bold text-decoration-none" style="color: var(--accent-primary);">
                    Form Warga &rarr;
                </a>
            </div>
        </main>
    </div>

    <script>
        function fillDemoCredentials(autoSubmit = false) {
            const emailEl = document.getElementById('email');
            const passEl = document.getElementById('password');
            emailEl.value = 'admin@sidisab.com';
            passEl.value = 'password123';
            if (autoSubmit) {
                document.getElementById('loginForm').submit();
            } else {
                emailEl.focus();
            }
        }

        function togglePasswordVisibility() {
            const passEl = document.getElementById('password');
            const iconEl = document.getElementById('eyeIcon');
            const btnEl = document.getElementById('btnTogglePass');
            if (passEl.type === 'password') {
                passEl.type = 'text';
                iconEl.classList.replace('bi-eye', 'bi-eye-slash');
                btnEl.setAttribute('aria-label', 'Sembunyikan kata sandi');
            } else {
                passEl.type = 'password';
                iconEl.classList.replace('bi-eye-slash', 'bi-eye');
                btnEl.setAttribute('aria-label', 'Tampilkan kata sandi');
            }
        }

        function toggleLoginContrast() {
            const html = document.documentElement;
            const isHigh = html.getAttribute('data-contrast') === 'high';
            if (isHigh) {
                html.removeAttribute('data-contrast');
            } else {
                html.setAttribute('data-contrast', 'high');
            }
            document.getElementById('btnContrastLogin').setAttribute('aria-pressed', !isHigh ? 'true' : 'false');
            localStorage.setItem('sidisab_high_contrast', !isHigh ? '1' : '0');
        }

        if (localStorage.getItem('sidisab_high_contrast') === '1') {
            document.documentElement.setAttribute('data-contrast', 'high');
            document.getElementById('btnContrastLogin').setAttribute('aria-pressed', 'true');
        }
    </script>
</body>
</html>