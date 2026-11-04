<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Panel Admin SIDISAB Dapil III</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
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
            --accent-soft: #EBF1FA;
            --border-hairline: #E4E2DD;
            --radius-card: 14px;
            --radius-control: 10px;
            --font-scale: 1;
        }

        html[data-contrast="high"] {
            --bg-canvas: #000000;
            --bg-surface: #0D0D0D;
            --bg-subtle: #1C1C1C;
            --text-primary: #FFFFFF;
            --text-secondary: #D9D9D9;
            --accent-primary: #FFD700;
            --accent-soft: #262105;
            --border-hairline: #555555;
        }

        html {
            font-size: calc(15.5px * var(--font-scale));
        }

        body {
            font-family: 'Atkinson Hyperlegible', sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.02em;
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        :focus-visible {
            outline: 3px solid var(--accent-primary) !important;
            outline-offset: 2px !important;
        }

        .admin-sidebar {
            min-height: 100vh;
            background-color: var(--bg-surface);
            border-right: 1px solid var(--border-hairline);
            padding: 1.75rem 1.25rem;
        }

        .nav-admin-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-control);
            font-weight: 600;
            margin-bottom: 0.35rem;
            transition: all 0.15s ease;
        }
        .nav-admin-link:hover {
            background-color: var(--bg-subtle);
            color: var(--text-primary);
        }
        .nav-admin-link.active {
            background-color: var(--accent-soft);
            color: var(--accent-primary);
            font-weight: 700;
        }

        .surface-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-hairline);
            border-radius: var(--radius-card);
        }

        .table-minimal th {
            background-color: var(--bg-subtle);
            color: var(--text-secondary);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            border-bottom: 1px solid var(--border-hairline);
            padding: 0.9rem 1rem;
        }
        .table-minimal td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-hairline);
            color: var(--text-primary);
            vertical-align: middle;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .status-Menunggu { background: #FEF6E6; color: #8A5300; }
        .status-Diproses { background: #EBF3FE; color: #0B4A99; }
        .status-Terealisasi { background: #E7F6ED; color: #0D6333; }

        .btn-civic-primary {
            background-color: var(--accent-primary);
            color: var(--bg-surface);
            border: 1px solid var(--accent-primary);
            border-radius: var(--radius-control);
            padding: 0.55rem 1.15rem;
            font-weight: 700;
            min-height: 42px;
        }
        .btn-civic-primary:hover {
            opacity: 0.92;
            color: var(--bg-surface);
        }

        .form-control, .form-select {
            background-color: var(--bg-surface);
            color: var(--text-primary);
            border: 1.5px solid var(--border-hairline);
            border-radius: var(--radius-control);
            min-height: 42px;
        }

        /* Perbaikan keterbacaan teks tabel dan komponen Bootstrap */
        .table-minimal,
        .table-minimal > :not(caption) > * > * {
            color: var(--text-primary);
            background-color: var(--bg-surface);
            border-color: var(--border-hairline);
        }
        .table-minimal thead th {
            color: var(--text-primary);
            background-color: var(--bg-subtle);
        }
        .table-minimal .text-secondary,
        .surface-card .text-secondary,
        .admin-sidebar .text-secondary {
            color: var(--text-secondary) !important;
        }
        .form-control::placeholder,
        .form-select,
        .form-select option {
            color: var(--text-primary);
        }

        /* Kontras tinggi: cegah warna bawaan Bootstrap menimpa warna tema */
        html[data-contrast="high"] .text-secondary,
        html[data-contrast="high"] .small.text-secondary,
        html[data-contrast="high"] .table-minimal td,
        html[data-contrast="high"] .table-minimal td * {
            color: #FFFFFF !important;
        }
        html[data-contrast="high"] .table-minimal th {
            color: #FFFFFF !important;
            background-color: #242424 !important;
        }
        html[data-contrast="high"] .table-minimal td,
        html[data-contrast="high"] .table-minimal tr {
            background-color: #0D0D0D !important;
            border-color: #777777 !important;
        }
        html[data-contrast="high"] .status-pill {
            border: 1px solid currentColor;
        }
        html[data-contrast="high"] .status-Menunggu {
            background: #332600 !important;
            color: #FFD700 !important;
        }
        html[data-contrast="high"] .status-Diproses {
            background: #002244 !important;
            color: #8CC8FF !important;
        }
        html[data-contrast="high"] .status-Terealisasi {
            background: #052E16 !important;
            color: #6EE7B7 !important;
        }
        html[data-contrast="high"] .btn-civic-primary {
            background-color: #FFD700;
            border-color: #FFD700;
            color: #000000;
        }
        html[data-contrast="high"] .btn-civic-primary:hover {
            color: #000000;
        }
        html[data-contrast="high"] .btn-outline-secondary {
            color: #FFFFFF;
            border-color: #FFFFFF;
        }
        html[data-contrast="high"] .form-control,
        html[data-contrast="high"] .form-select {
            color: #FFFFFF;
            background-color: #111111;
            border-color: #AAAAAA;
        }
        html[data-contrast="high"] .form-select option {
            color: #FFFFFF;
            background-color: #111111;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Minimalist -->
        <aside class="col-md-3 col-lg-2 admin-sidebar d-flex flex-column">
            <div class="d-flex align-items-center gap-2 mb-4 px-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3" style="width: 36px; height: 36px; background: var(--accent-soft); color: var(--accent-primary);">
                    <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0">SIDISAB</h6>
                    <small class="text-secondary" style="font-size: 0.75rem;">Dapil III Tangerang</small>
                </div>
            </div>

            <nav aria-label="Menu Panel Admin">
                <a href="{{ route('admin.dashboard') }}" class="nav-admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2" aria-hidden="true"></i> Ringkasan Data
                </a>
                <a href="{{ route('admin.gis') }}" class="nav-admin-link {{ request()->routeIs('admin.gis') ? 'active' : '' }}">
                    <i class="bi bi-map" aria-hidden="true"></i> Peta Spasial (GIS)
                </a>
                <a href="{{ route('admin.warga.index') }}" class="nav-admin-link {{ request()->routeIs('admin.warga.*') ? 'active' : '' }}">
                    <i class="bi bi-people" aria-hidden="true"></i> Data Warga
                </a>
                <a href="{{ route('admin.pengajuan.index') }}" class="nav-admin-link {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                    <i class="bi bi-heart-pulse" aria-hidden="true"></i> Pengajuan Alat
                </a>
                <a href="{{ route('admin.import-bnba.index') }}" class="nav-admin-link {{ request()->routeIs('admin.import-bnba.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-arrow-up" aria-hidden="true"></i> Import Data BNBA
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="nav-admin-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i> Laporan RDP
                </a>
                <a href="{{ route('admin.pengaturan.index') }}" class="nav-admin-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                    <i class="bi bi-sliders" aria-hidden="true"></i> Panduan &amp; Akun
                </a>
            </nav>

            <div class="mt-auto pt-4" style="border-top: 1px solid var(--border-hairline);">
                <a href="{{ route('public.landing') }}" target="_blank" class="nav-admin-link mb-2">
                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Buka Halaman Publik
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 rounded-3 fw-bold">
                        <i class="bi bi-box-arrow-left me-1" aria-hidden="true"></i> Keluar Sesi
                    </button>
                </form>
            </div>
        </aside>

        <!-- Konten Utama -->
        <main class="col-md-9 col-lg-10 p-4 p-lg-5">
            <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5 pb-3" style="border-bottom: 1px solid var(--border-hairline);">
                <div>
                    <h3 class="fw-bold mb-1">@yield('title')</h3>
                    <p class="text-secondary small mb-0">Sistem Informasi Disabilitas &amp; Alat Bantu — Kec. Cipondoh &amp; Kec. Pinang</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" onclick="toggleAdminContrast()" title="Ubah Kontras Layar">
                        <i class="bi bi-circle-half" aria-hidden="true"></i> Kontras
                    </button>
                    <span class="px-3 py-2 rounded-3 small fw-bold" style="background: var(--bg-subtle); border: 1px solid var(--border-hairline);">
                        <i class="bi bi-person-check me-1" aria-hidden="true"></i> {{ auth()->user()->name }}
                    </span>
                </div>
            </header>

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-4 shadow-sm d-flex align-items-center justify-content-between mb-4" role="alert">
                    <div><i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i> {{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup Notifikasi"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleAdminContrast() {
        const html = document.documentElement;
        const isHigh = html.getAttribute('data-contrast') === 'high';
        if (isHigh) html.removeAttribute('data-contrast');
        else html.setAttribute('data-contrast', 'high');
        localStorage.setItem('sidisab_high_contrast', !isHigh ? '1' : '0');
    }
    if (localStorage.getItem('sidisab_high_contrast') === '1') {
        document.documentElement.setAttribute('data-contrast', 'high');
    }
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }
</script>
@stack('scripts')
</body>
</html>