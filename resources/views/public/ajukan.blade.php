@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('public.landing') }}" class="text-decoration-none small fw-bold" style="color: var(--accent-primary);">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali ke Beranda
                </a>
                <h1 class="h2 fw-bold mt-2 mb-1">Formulir Pengajuan Alat Bantu Mandiri</h1>
                <p class="text-muted-custom mb-0">
                    Khusus warga penyandang disabilitas yang berdomisili di Kecamatan Cipondoh dan Kecamatan Pinang.
                </p>
            </div>

            <div class="surface-card p-4 p-lg-5">
                @if($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 mb-4" role="alert">
                        <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1" aria-hidden="true"></i> Mohon periksa kembali isian Anda:</h6>
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.simpan') }}" method="POST" novalidate>
                    @csrf

                    <!-- Bagian 1: Identitas Kependudukan -->
                    <h2 class="h6 fw-bold text-uppercase text-muted-custom mb-3 pb-2" style="border-bottom: 1px solid var(--border-hairline);">
                        1. Identitas Kependudukan
                    </h2>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <label for="nik" class="form-label fw-bold">NIK (16 Digit) <span class="text-danger">*</span></label>
                                <small id="nikCounter" class="text-muted-custom tabular-nums">0 / 16 digit</small>
                            </div>
                            <input type="text" id="nik" name="nik" inputmode="numeric" pattern="[0-9]*"
                                   class="form-control tabular-nums" maxlength="16" value="{{ old('nik') }}"
                                   placeholder="3671xxxxxxxxxxxx" required oninput="updateNikCount(this)">
                        </div>
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap Sesuai KTP <span class="text-danger">*</span></label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                   value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap warga" required>
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-bold">Nomor WhatsApp Aktif (Warga/Pendamping) <span class="text-danger">*</span></label>
                            <input type="tel" id="no_hp" name="no_hp" class="form-control tabular-nums"
                                   placeholder="0812xxxxxxxx" value="{{ old('no_hp') }}" required>
                        </div>
                    </div>

                    <!-- Bagian 2: Domisili & Titik Lokasi -->
                    <h2 class="h6 fw-bold text-uppercase text-muted-custom mb-3 pb-2" style="border-bottom: 1px solid var(--border-hairline);">
                        2. Domisili &amp; Titik Koordinat Rumah
                    </h2>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="kecamatan" class="form-label fw-bold">Kecamatan (Dapil III) <span class="text-danger">*</span></label>
                            <select id="kecamatan" name="kecamatan" class="form-select" required>
                                <option value="Cipondoh" {{ old('kecamatan') == 'Cipondoh' ? 'selected' : '' }}>Kecamatan Cipondoh</option>
                                <option value="Pinang" {{ old('kecamatan') == 'Pinang' ? 'selected' : '' }}>Kecamatan Pinang</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="kelurahan" class="form-label fw-bold">Kelurahan <span class="text-danger">*</span></label>
                            <input type="text" id="kelurahan" name="kelurahan" list="listKelurahan" class="form-control"
                                   value="{{ old('kelurahan') }}" placeholder="Ketik atau pilih kelurahan..." required>
                            <datalist id="listKelurahan">
                                <option value="Cipondoh"></option>
                                <option value="Cipondoh Indah"></option>
                                <option value="Cipondoh Makmur"></option>
                                <option value="Gondrong"></option>
                                <option value="Kenanga"></option>
                                <option value="Ketapang"></option>
                                <option value="Petir"></option>
                                <option value="Poris Plawad"></option>
                                <option value="Poris Plawad Indah"></option>
                                <option value="Poris Plawad Utara"></option>
                                <option value="Pinang"></option>
                                <option value="Kunciran"></option>
                                <option value="Kunciran Indah"></option>
                                <option value="Kunciran Jaya"></option>
                                <option value="Nerogtog"></option>
                                <option value="Pakojan"></option>
                                <option value="Panunggangan"></option>
                                <option value="Panunggangan Timur"></option>
                                <option value="Panunggangan Utara"></option>
                                <option value="Sudimara Pinang"></option>
                                <option value="Cipete"></option>
                            </datalist>
                        </div>
                        <div class="col-12">
                            <label for="alamat_lengkap" class="form-label fw-bold">Alamat Lengkap (Nama Jalan, RT/RW, Nomor Rumah/Patokan) <span class="text-danger">*</span></label>
                            <textarea id="alamat_lengkap" name="alamat_lengkap" class="form-control" rows="2" required>{{ old('alamat_lengkap') }}</textarea>
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <label class="form-label fw-bold mb-0">Titik Koordinat Tempat Tinggal (Opsional)</label>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-3 fw-bold" onclick="useCurrentGps()">
                                    <i class="bi bi-crosshair me-1" aria-hidden="true"></i> Gunakan Lokasi Saya Saat Ini (GPS)
                                </button>
                            </div>
                            <p class="small text-muted-custom mb-2">Klik pada peta atau geser pin untuk menandai lokasi rumah agar memudahkan survei petugas.</p>
                            <div id="mapPicker" style="height: 280px;" class="rounded-4 border mb-2"></div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label for="lat" class="small text-muted-custom">Latitude</label>
                                    <input type="text" id="lat" name="latitude" class="form-control form-control-sm tabular-nums"
                                           value="{{ old('latitude') }}" placeholder="-6.19800000">
                                </div>
                                <div class="col-6">
                                    <label for="lng" class="small text-muted-custom">Longitude</label>
                                    <input type="text" id="lng" name="longitude" class="form-control form-control-sm tabular-nums"
                                           value="{{ old('longitude') }}" placeholder="106.67500000">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 3: Kebutuhan Alat Bantu -->
                    <h2 class="h6 fw-bold text-uppercase text-muted-custom mb-3 pb-2" style="border-bottom: 1px solid var(--border-hairline);">
                        3. Rincian Disabilitas &amp; Alat Bantu
                    </h2>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="kategori_disabilitas" class="form-label fw-bold">Kategori Disabilitas <span class="text-danger">*</span></label>
                            <select id="kategori_disabilitas" name="kategori_disabilitas" class="form-select" required>
                                <option value="Tunadaksa" {{ old('kategori_disabilitas') == 'Tunadaksa' ? 'selected' : '' }}>Tunadaksa (Fisik / Gerak)</option>
                                <option value="Tunanetra" {{ old('kategori_disabilitas') == 'Tunanetra' ? 'selected' : '' }}>Tunanetra (Penglihatan)</option>
                                <option value="Tunarungu" {{ old('kategori_disabilitas') == 'Tunarungu' ? 'selected' : '' }}>Tunarungu (Pendengaran)</option>
                                <option value="Tunawicara" {{ old('kategori_disabilitas') == 'Tunawicara' ? 'selected' : '' }}>Tunawicara (Wicara)</option>
                                <option value="Tunagrahita" {{ old('kategori_disabilitas') == 'Tunagrahita' ? 'selected' : '' }}>Tunagrahita (Intelektual)</option>
                                <option value="Disabilitas Ganda" {{ old('kategori_disabilitas') == 'Disabilitas Ganda' ? 'selected' : '' }}>Disabilitas Ganda</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="jenis_alat" class="form-label fw-bold">Alat Bantu yang Diajukan <span class="text-danger">*</span></label>
                            <select id="jenis_alat" name="jenis_alat" class="form-select" required>
                                <option value="Kursi Roda" {{ old('jenis_alat') == 'Kursi Roda' ? 'selected' : '' }}>Kursi Roda</option>
                                <option value="Kruk" {{ old('jenis_alat') == 'Kruk' ? 'selected' : '' }}>Kruk / Penyangga Kaki</option>
                                <option value="Tongkat Penuntun" {{ old('jenis_alat') == 'Tongkat Penuntun' ? 'selected' : '' }}>Tongkat Penuntun Netra</option>
                                <option value="Alat Bantu Dengar" {{ old('jenis_alat') == 'Alat Bantu Dengar' ? 'selected' : '' }}>Alat Bantu Dengar</option>
                                <option value="Kaki/Tangan Palsu" {{ old('jenis_alat') == 'Kaki/Tangan Palsu' ? 'selected' : '' }}>Kaki / Tangan Palsu</option>
                                <option value="Lainnya" {{ old('jenis_alat') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="keterangan_kondisi" class="form-label fw-bold">Keterangan Kondisi Saat Ini</label>
                            <textarea id="keterangan_kondisi" name="keterangan_kondisi" class="form-control" rows="3"
                                      placeholder="Jelaskan kondisi warga dan alasan membutuhkan alat bantu tersebut...">{{ old('keterangan_kondisi') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-civic-primary w-100 py-3">
                        <i class="bi bi-send-check" aria-hidden="true"></i> Kirim Permohonan Bantuan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    function updateNikCount(el) {
        el.value = el.value.replace(/\D/g, '');
        document.getElementById('nikCounter').textContent = `${el.value.length} / 16 digit`;
    }

    const map = L.map('mapPicker').setView([-6.1980, 106.6750], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = null;

    function setCoordinate(lat, lng, pan = false) {
        document.getElementById('lat').value = Number(lat).toFixed(8);
        document.getElementById('lng').value = Number(lng).toFixed(8);
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                setCoordinate(pos.lat, pos.lng, false);
            });
        }
        if (pan) map.setView([lat, lng], 16);
    }

    map.on('click', function(e) {
        setCoordinate(e.latlng.lat, e.latlng.lng, false);
    });

    function useCurrentGps() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung fitur geolokasi.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            pos => setCoordinate(pos.coords.latitude, pos.coords.longitude, true),
            () => alert('Gagal mengambil titik GPS. Pastikan izin lokasi diaktifkan atau klik langsung pada peta.')
        );
    }

    updateNikCount(document.getElementById('nik'));
</script>
@endpush