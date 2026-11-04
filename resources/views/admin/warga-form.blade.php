@extends('layouts.admin')
@section('title', $warga ? 'Perbarui Data Warga Disabilitas' : 'Tambah Data Warga Disabilitas')

@section('content')
<div class="surface-card p-4 p-lg-5">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 border-0 mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $warga ? route('admin.warga.update', $warga->id) : route('admin.warga.store') }}" method="POST">
        @csrf
        @if($warga) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-4">
                <label for="nik" class="form-label fw-bold">NIK (16 Digit)</label>
                <input type="text" id="nik" name="nik" inputmode="numeric" class="form-control tabular-nums" maxlength="16"
                       value="{{ old('nik', $warga->nik ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                       value="{{ old('nama_lengkap', $warga->nama_lengkap ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label for="no_hp" class="form-label fw-bold">Nomor HP / WhatsApp</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control tabular-nums"
                       value="{{ old('no_hp', $warga->no_hp ?? '') }}">
            </div>

            <div class="col-md-4">
                <label for="kecamatan" class="form-label fw-bold">Kecamatan (Dapil III)</label>
                <select id="kecamatan" name="kecamatan" class="form-select" required>
                    <option value="Cipondoh" {{ (old('kecamatan', $warga->kecamatan ?? '') == 'Cipondoh') ? 'selected' : '' }}>Cipondoh</option>
                    <option value="Pinang" {{ (old('kecamatan', $warga->kecamatan ?? '') == 'Pinang') ? 'selected' : '' }}>Pinang</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="kelurahan" class="form-label fw-bold">Kelurahan</label>
                <input type="text" id="kelurahan" name="kelurahan" class="form-control"
                       value="{{ old('kelurahan', $warga->kelurahan ?? '') }}" required>
            </div>
            <div class="col-md-4">
                <label for="kategori_disabilitas" class="form-label fw-bold">Kategori Disabilitas</label>
                <select id="kategori_disabilitas" name="kategori_disabilitas" class="form-select">
                    <option value="">-- Belum ditentukan --</option>
                    @foreach(['Tunadaksa', 'Tunanetra', 'Tunarungu', 'Tunawicara', 'Tunagrahita', 'Disabilitas Ganda'] as $kat)
                        <option value="{{ $kat }}" {{ (old('kategori_disabilitas', $warga->kategori_disabilitas ?? '') == $kat) ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label for="alamat_lengkap" class="form-label fw-bold">Alamat Lengkap</label>
                <textarea id="alamat_lengkap" name="alamat_lengkap" class="form-control" rows="2" required>{{ old('alamat_lengkap', $warga->alamat_lengkap ?? '') }}</textarea>
            </div>

            <div class="col-12 mt-4">
                <label class="form-label fw-bold">Penentuan Titik Koordinat Rumah (Klik Peta atau Geser Pin)</label>
                <div id="mapForm" style="height: 320px;" class="border rounded-4 mb-3"></div>
            </div>

            <div class="col-md-6">
                <label for="lat" class="form-label fw-bold">Latitude</label>
                <input type="text" id="lat" name="latitude" class="form-control tabular-nums"
                       value="{{ old('latitude', $warga->latitude ?? '-6.19800000') }}" required>
            </div>
            <div class="col-md-6">
                <label for="lng" class="form-label fw-bold">Longitude</label>
                <input type="text" id="lng" name="longitude" class="form-control tabular-nums"
                       value="{{ old('longitude', $warga->longitude ?? '106.67500000') }}" required>
            </div>

            <div class="col-12 mt-4 d-flex gap-2">
                <button type="submit" class="btn-civic-primary">Simpan Data Warga</button>
                <a href="{{ route('admin.warga.index') }}" class="btn btn-outline-secondary rounded-3 px-4 d-inline-flex align-items-center">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const initLat = parseFloat(latInput.value) || -6.1980;
    const initLng = parseFloat(lngInput.value) || 106.6750;

    const map = L.map('mapForm').setView([initLat, initLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        latInput.value = pos.lat.toFixed(8);
        lngInput.value = pos.lng.toFixed(8);
    });

    map.on('click', function(e) {
        latInput.value = e.latlng.lat.toFixed(8);
        lngInput.value = e.latlng.lng.toFixed(8);
        marker.setLatLng(e.latlng);
    });

    function syncFromInput() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    }
    latInput.addEventListener('change', syncFromInput);
    lngInput.addEventListener('change', syncFromInput);
</script>
@endpush