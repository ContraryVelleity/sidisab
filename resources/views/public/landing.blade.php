@extends('layouts.public')

@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 p-4 mb-5 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-3 text-success" aria-hidden="true"></i>
                <div>
                    <h6 class="fw-bold mb-1">Permohonan Berhasil Diterima</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-primary border-0 rounded-4 p-4 mb-5 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-info-circle-fill fs-3" aria-hidden="true"></i>
                <div>
                    <h6 class="fw-bold mb-1">Informasi Permohonan</h6>
                    <p class="mb-0">{{ session('info') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Section dengan Whitespace Lega -->
    <section class="py-4 py-lg-5 mb-5">
        <div class="row align-items-end g-4">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3" style="line-height: 1.18;">
                    Pemetaan Spasial &amp; Pendataan Alat Bantu Disabilitas Dapil III Kota Tangerang
                </h1>
                <p class="fs-5 text-muted-custom mb-4" style="max-width: 65ch;">
                    Layanan keterbukaan data geografis dan pengajuan mandiri bantuan alat bantu bagi warga penyandang disabilitas di wilayah <strong>Kecamatan Cipondoh</strong> dan <strong>Kecamatan Pinang</strong>.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('public.ajukan') }}" class="btn-civic-primary text-decoration-none">
                        <i class="bi bi-plus-circle" aria-hidden="true"></i> Ajukan Alat Bantu Mandiri
                    </a>
                    <a href="#cek-status" class="btn-civic-outline text-decoration-none">
                        <i class="bi bi-search" aria-hidden="true"></i> Lacak Status NIK
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="surface-card p-4">
                    <div class="row text-center g-3 tabular-nums">
                        <div class="col-4">
                            <div class="fs-2 fw-bold" style="color: var(--accent-primary);">{{ $totalWarga }}</div>
                            <div class="small text-muted-custom">Warga Terdata</div>
                        </div>
                        <div class="col-4" style="border-left: 1px solid var(--border-hairline); border-right: 1px solid var(--border-hairline);">
                            <div class="fs-2 fw-bold">{{ $totalAjuan }}</div>
                            <div class="small text-muted-custom">Total Ajuan</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-2 fw-bold text-success">{{ $totalSelesai }}</div>
                            <div class="small text-muted-custom">Terealisasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Peta Interaktif Publik & Filter Langsung -->
    <section class="mb-5 pb-3" aria-labelledby="heading-map">
        <div class="surface-card overflow-hidden">
            <div class="p-4 p-lg-5" style="border-bottom: 1px solid var(--border-hairline);">
                <div class="row align-items-center justify-content-between g-3">
                    <div class="col-lg-6">
                        <h2 id="heading-map" class="h4 fw-bold mb-1">Peta Sebaran Geografis Warga</h2>
                        <p class="text-muted-custom small mb-0">
                            Identitas warga disensor dengan inisial untuk menjaga privasi data kependudukan.
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label for="filterKecamatan" class="visually-hidden">Filter Kecamatan</label>
                                <select id="filterKecamatan" class="form-select" aria-label="Filter berdasarkan Kecamatan">
                                    <option value="ALL">Semua Kecamatan (Dapil III)</option>
                                    <option value="Cipondoh">Kecamatan Cipondoh</option>
                                    <option value="Pinang">Kecamatan Pinang</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="filterKategori" class="visually-hidden">Filter Kategori Disabilitas</label>
                                <select id="filterKategori" class="form-select" aria-label="Filter berdasarkan Kategori Disabilitas">
                                    <option value="ALL">Semua Kategori Disabilitas</option>
                                    <option value="Tunadaksa">Tunadaksa</option>
                                    <option value="Tunanetra">Tunanetra</option>
                                    <option value="Tunarungu">Tunarungu</option>
                                    <option value="Tunawicara">Tunawicara</option>
                                    <option value="Tunagrahita">Tunagrahita</option>
                                    <option value="Disabilitas Ganda">Disabilitas Ganda</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="mapPublic" style="height: 480px; width: 100%;" role="application" aria-label="Peta sebaran warga disabilitas Cipondoh dan Pinang"></div>

            <!-- Legenda & Ringkasan Kategori -->
            <div class="p-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: var(--bg-subtle); border-top: 1px solid var(--border-hairline);">
                <div class="d-flex flex-wrap align-items-center gap-3 small">
                    <span class="fw-bold">Legenda Status:</span>
                    <span class="d-inline-flex align-items-center gap-1"><span style="width:10px;height:10px;border-radius:50%;background:#D97706;display:inline-block;"></span> Menunggu</span>
                    <span class="d-inline-flex align-items-center gap-1"><span style="width:10px;height:10px;border-radius:50%;background:#0F52BA;display:inline-block;"></span> Diproses</span>
                    <span class="d-inline-flex align-items-center gap-1"><span style="width:10px;height:10px;border-radius:50%;background:#15803D;display:inline-block;"></span> Terealisasi</span>
                </div>
                <div class="small text-muted-custom tabular-nums">
                    Menampilkan <strong id="visibleMarkerCount">0</strong> titik lokasi terpetakan
                </div>
            </div>
        </div>
    </section>

    <!-- Seksi Cek Status NIK & Ringkasan Kategori Disabilitas -->
    <section id="cek-status" class="py-3">
        <div class="row g-4">
            <!-- Pencarian NIK -->
            <div class="col-lg-7">
                <div class="surface-card p-4 p-lg-5 h-100">
                    <h2 class="h4 fw-bold mb-2">Lacak Status Permohonan via NIK</h2>
                    <p class="text-muted-custom mb-4">
                        Masukkan 16 digit Nomor Induk Kependudukan (NIK) untuk melihat tahapan verifikasi dan penyaluran alat bantu.
                    </p>

                    <form action="{{ route('public.landing') }}#cek-status" method="GET" class="row g-2 mb-3">
                        <div class="col-sm-8">
                            <label for="inputCekNik" class="visually-hidden">Nomor Induk Kependudukan 16 Digit</label>
                            <input type="text" id="inputCekNik" name="nik" inputmode="numeric" pattern="[0-9]*"
                                   class="form-control tabular-nums" placeholder="Contoh: 3671051205700001"
                                   value="{{ request('nik') }}" required maxlength="16">
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn-civic-primary w-100">
                                <i class="bi bi-search" aria-hidden="true"></i> Periksa NIK
                            </button>
                        </div>
                    </form>

                    <div class="small text-muted-custom mb-4">
                        Contoh uji cepat:
                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold" onclick="document.getElementById('inputCekNik').value='3671051205700001'">3671051205700001</button>
                        atau
                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold" onclick="document.getElementById('inputCekNik').value='3671111902650003'">3671111902650003</button>
                    </div>

                    @if(request()->filled('nik'))
                        <div class="pt-4" style="border-top: 1px solid var(--border-hairline);" aria-live="polite">
                            @if($hasilCekNik)
                                @php $ajuan = $hasilCekNik->pengajuanAlat->first(); @endphp
                                <div class="p-4 rounded-4" style="background-color: var(--bg-subtle);">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                        <div>
                                            <span class="text-muted-custom small d-block">Nama Terdata (Disensor)</span>
                                            <h5 class="fw-bold mb-0">{{ Str::mask($hasilCekNik->nama_lengkap, '*', 3) }}</h5>
                                        </div>
                                        @if($ajuan)
                                            <span class="status-pill status-{{ $ajuan->status }}">
                                                <i class="bi bi-circle-fill" style="font-size: 0.45rem;" aria-hidden="true"></i>
                                                {{ strtoupper($ajuan->status) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="row g-3 small mb-3">
                                        <div class="col-sm-6">
                                            <span class="text-muted-custom d-block">Wilayah Domisili</span>
                                            <strong>Kel. {{ $hasilCekNik->kelurahan }}, Kec. {{ $hasilCekNik->kecamatan }}</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="text-muted-custom d-block">Kategori &amp; Alat Bantu</span>
                                            <strong>{{ $hasilCekNik->kategori_disabilitas }} — {{ $ajuan ? $ajuan->jenis_alat : 'Belum Mengajukan' }}</strong>
                                        </div>
                                    </div>

                                    @if($ajuan)
                                        <!-- Stepper Visual Tahapan Bantuan -->
                                        @php
                                            $step = $ajuan->status === 'Terealisasi' ? 3 : ($ajuan->status === 'Diproses' ? 2 : 1);
                                        @endphp
                                        <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-hairline);">
                                            <div class="d-flex justify-content-between small fw-bold mb-2">
                                                <span class="{{ $step >= 1 ? 'text-primary' : 'text-muted-custom' }}">1. Menunggu Verifikasi</span>
                                                <span class="{{ $step >= 2 ? 'text-primary' : 'text-muted-custom' }}">2. Diproses Dinas</span>
                                                <span class="{{ $step >= 3 ? 'text-success' : 'text-muted-custom' }}">3. Terealisasi</span>
                                            </div>
                                            <div class="progress" style="height: 8px; border-radius: 99px;" role="progressbar" aria-valuenow="{{ $step * 33 }}" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar {{ $step == 3 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $step == 1 ? '33%' : ($step == 2 ? '66%' : '100%') }}"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="p-4 rounded-4 border border-danger-subtle bg-danger-subtle text-danger-emphasis">
                                    <p class="fw-bold mb-1"><i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i> NIK Belum Terdaftar</p>
                                    <p class="small mb-2">Data warga dengan NIK <strong class="tabular-nums">{{ request('nik') }}</strong> belum ditemukan.</p>
                                    <a href="{{ route('public.ajukan') }}" class="btn btn-sm btn-danger rounded-3 fw-bold">Daftar &amp; Ajukan Sekarang</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Komposisi Kategori Disabilitas -->
            <div class="col-lg-5">
                <div class="surface-card p-4 p-lg-5 h-100">
                    <h2 class="h4 fw-bold mb-2">Proporsi Kategori Disabilitas</h2>
                    <p class="text-muted-custom small mb-4">Distribusi warga terdata berdasarkan ragam disabilitas di Dapil III.</p>

                    <div class="d-flex flex-column gap-3 tabular-nums">
                        @php
                            $daftarKategori = ['Tunadaksa', 'Tunanetra', 'Tunarungu', 'Tunawicara', 'Tunagrahita', 'Disabilitas Ganda'];
                        @endphp
                        @foreach($daftarKategori as $kat)
                            @php
                                $jml = $kategoriStats[$kat] ?? 0;
                                $persen = $totalWarga > 0 ? round(($jml / $totalWarga) * 100) : 0;
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-bold">{{ $kat }}</span>
                                    <span class="text-muted-custom">{{ $jml }} jiwa ({{ $persen }}%)</span>
                                </div>
                                <div class="progress" style="height: 7px; background-color: var(--bg-subtle);">
                                    <div class="progress-bar" style="width: {{ $persen }}%; background-color: var(--accent-primary);"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('mapPublic').setView([-6.1980, 106.6750], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap | SIDISAB Dapil III Tangerang'
    }).addTo(map);

    const markerLayer = L.layerGroup().addTo(map);
    let rawMapData = [];

    function getPinColor(status) {
        if (status === 'Terealisasi') return '#15803D';
        if (status === 'Diproses') return '#0F52BA';
        return '#D97706';
    }

    function renderPublicMarkers() {
        markerLayer.clearLayers();
        const kecFilter = document.getElementById('filterKecamatan').value;
        const katFilter = document.getElementById('filterKategori').value;
        let count = 0;

        rawMapData.forEach(item => {
            if (kecFilter !== 'ALL' && item.kecamatan !== kecFilter) return;
            if (katFilter !== 'ALL' && item.kategori !== katFilter) return;

            count++;
            const pinColor = getPinColor(item.status);
            const icon = L.divIcon({
                className: '',
                html: `<div class="custom-map-pin" style="background-color:${pinColor};"></div>`,
                iconSize: [18, 18],
                iconAnchor: [9, 9]
            });

            L.marker([item.lat, item.lng], { icon })
                .addTo(markerLayer)
                .bindPopup(`
                    <div style="min-width: 200px; font-family: 'Atkinson Hyperlegible', sans-serif;">
                        <strong style="font-size: 0.95rem;">${escapeHtml(item.nama_sensor)}</strong>
                        <div style="color: #525866; font-size: 0.8rem; margin-bottom: 6px;">
                            Kel. ${escapeHtml(item.kelurahan)}, Kec. ${escapeHtml(item.kecamatan)}
                        </div>
                        <div style="font-size: 0.82rem; border-top: 1px solid #eee; padding-top: 6px;">
                            <div><strong>Kategori:</strong> ${escapeHtml(item.kategori)}</div>
                            <div><strong>Alat Bantu:</strong> ${escapeHtml(item.alat)}</div>
                            <div><strong>Status:</strong> ${escapeHtml(item.status)}</div>
                        </div>
                    </div>
                `);
        });

        document.getElementById('visibleMarkerCount').textContent = count;
    }

    fetch("{{ route('api.map.public') }}")
        .then(res => res.json())
        .then(data => {
            rawMapData = data;
            renderPublicMarkers();
        });

    document.getElementById('filterKecamatan').addEventListener('change', renderPublicMarkers);
    document.getElementById('filterKategori').addEventListener('change', renderPublicMarkers);
</script>
@endpush