@extends('layouts.admin')
@section('title', 'Import Data BNBA Tahun 2026')

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <div class="surface-card p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:var(--accent-soft);color:var(--accent-primary);">
                    <i class="bi bi-file-earmark-spreadsheet fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Import BNBA Alat Bantu Disabilitas</h5>
                    <p class="text-secondary small mb-0">Unggah file Excel dengan sheet CIPONDOH dan PINANG.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif

            @if(session('import_stats'))
                @php($stats = session('import_stats'))
                <div class="alert alert-info rounded-3">
                    <strong>Ringkasan import</strong>
                    <ul class="mb-0 mt-2">
                        <li>Baris Excel dibaca: {{ $stats['rows'] }}</li>
                        <li>Data warga baru: {{ $stats['warga_created'] }}</li>
                        <li>NIK warga yang sudah terdaftar/ditemukan: {{ $stats['warga_matched'] }}</li>
                        <li>Pengajuan baru: {{ $stats['pengajuan_created'] }}</li>
                        <li>Pengajuan yang sudah ada dan dilewati: {{ $stats['pengajuan_skipped'] }}</li>
                    </ul>
                </div>
            @endif

            @if(session('import_error'))
                <div class="alert alert-danger rounded-3">
                    <strong>Import dibatalkan.</strong>
                    <div class="small mt-2" style="white-space:pre-line;">{{ session('import_error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <form action="{{ route('admin.import-bnba.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label fw-bold">Pilih file Excel BNBA</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    <div class="form-text">Format .xlsx atau .xls, maksimal 10 MB.</div>
                </div>
                <button type="submit" class="btn-civic-primary" onclick="return confirm('Mulai import data dari file Excel ini?')">
                    <i class="bi bi-cloud-arrow-up me-1"></i> Import Data Excel
                </button>
            </form>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="surface-card p-4 h-100">
            <h6 class="fw-bold mb-3">Ketentuan file</h6>
            <ul class="small text-secondary ps-3 mb-0">
                <li class="mb-2">Nama sheet harus <strong>CIPONDOH</strong> dan <strong>PINANG</strong>.</li>
                <li class="mb-2">Baris pertama harus berisi header sesuai file BNBA asli.</li>
                <li class="mb-2">NIK harus terdiri dari 16 digit.</li>
                <li class="mb-2">Data warga dicocokkan menggunakan NIK agar tidak membuat warga ganda.</li>
                <li class="mb-2">Setiap jenis bantuan disimpan sebagai pengajuan tersendiri.</li>
                <li class="mb-2">Kategori disabilitas dan koordinat tidak diisi karena tidak tersedia di Excel.</li>
                <li class="mb-2">Tanggal pengajuan menggunakan tanggal saat file diimpor.</li>
                <li>Jika ada baris tidak valid, seluruh proses dibatalkan.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
