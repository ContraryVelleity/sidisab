@extends('layouts.admin')
@section('title', 'Rekapitulasi & Cetak Laporan RDP Dinas Sosial')

@section('content')
<div class="surface-card p-4 mb-4">
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="kecamatan" class="form-label fw-bold small">Filter Kecamatan</label>
            <select id="kecamatan" name="kecamatan" class="form-select">
                <option value="">Semua Wilayah (Dapil III)</option>
                <option value="Cipondoh" {{ request('kecamatan') == 'Cipondoh' ? 'selected' : '' }}>Kec. Cipondoh</option>
                <option value="Pinang" {{ request('kecamatan') == 'Pinang' ? 'selected' : '' }}>Kec. Pinang</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label fw-bold small">Filter Status Bantuan</label>
            <select id="status" name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Terealisasi" {{ request('status') == 'Terealisasi' ? 'selected' : '' }}>Terealisasi</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-civic-primary w-100">Terapkan</button>
        </div>
        <div class="col-md-4 text-md-end d-flex flex-wrap justify-content-md-end gap-2">
            <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank" class="btn btn-danger rounded-3 fw-bold px-3 py-2">
                <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Cetak PDF (RDP)
            </a>
            <a href="{{ route('admin.laporan.excel', request()->query()) }}" class="btn btn-success rounded-3 fw-bold px-3 py-2">
                <i class="bi bi-file-earmark-spreadsheet me-1" aria-hidden="true"></i> Ekspor CSV/Excel
            </a>
        </div>
    </form>
</div>

<div class="surface-card overflow-hidden">
    <div class="p-4 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid var(--border-hairline);">
        <h5 class="fw-bold mb-0">Pratinjau Dokumen Laporan RDP</h5>
        <span class="small text-secondary tabular-nums">Total: <strong>{{ $laporans->count() }}</strong> baris data</span>
    </div>
    <div class="table-responsive">
        <table class="table table-minimal mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Wilayah</th>
                    <th>Alamat</th>
                    <th>Disabilitas</th>
                    <th>Alat Bantu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $i => $row)
                <tr>
                    <td class="tabular-nums">{{ $i + 1 }}</td>
                    <td class="tabular-nums">{{ $row->warga->nik ?? '-' }}</td>
                    <td class="fw-bold">{{ $row->warga->nama_lengkap ?? '-' }}</td>
                    <td>{{ $row->warga->kecamatan ?? '-' }} / {{ $row->warga->kelurahan ?? '-' }}</td>
                    <td>{{ $row->warga->alamat_lengkap ?? '-' }}</td>
                    <td>{{ $row->warga->kategori_disabilitas ?? '-' }}</td>
                    <td>{{ $row->jenis_alat }}</td>
                    <td><span class="status-pill status-{{ $row->status }}">{{ $row->status }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-secondary">Tidak ada data yang sesuai dengan filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection