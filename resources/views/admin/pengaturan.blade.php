@extends('layouts.admin')
@section('title', 'Panduan Operasional & Pengaturan Akun')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="surface-card p-4">
            <h5 class="fw-bold mb-3">Panduan Singkat Operasional Sistem</h5>
            <div class="accordion accordion-flush" id="accordionPanduan">
                <div class="accordion-item bg-transparent">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#p1">
                            1. Menambah Data Warga &amp; Titik Koordinat Peta
                        </button>
                    </h2>
                    <div id="p1" class="accordion-collapse collapse show" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary">
                            Buka menu <strong>Data Warga</strong> lalu klik <strong>Tambah Data Warga</strong>. Isi identitas lengkap sesuai KTP, kemudian klik lokasi rumah warga pada peta interaktif atau geser pin agar angka <em>Latitude</em> dan <em>Longitude</em> terisi secara presisi.
                        </div>
                    </div>
                </div>
                <div class="accordion-item bg-transparent">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#p2">
                            2. Memverifikasi Pengajuan Mandiri dari Masyarakat
                        </button>
                    </h2>
                    <div id="p2" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary">
                            Warga yang mengajukan lewat halaman publik ditandai dengan label <strong>Ajuan Mandiri (Perlu Cek)</strong>. Periksa kelengkapan titik koordinatnya di menu Data Warga, lalu ubah status permohonannya di menu <strong>Pengajuan Alat</strong>.
                        </div>
                    </div>
                </div>
                <div class="accordion-item bg-transparent">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#p3">
                            3. Mencetak Dokumen Rapat Dengar Pendapat (RDP)
                        </button>
                    </h2>
                    <div id="p3" class="accordion-collapse collapse" data-bs-parent="#accordionPanduan">
                        <div class="accordion-body text-secondary">
                            Buka menu <strong>Laporan RDP</strong>, saring menurut Kecamatan (Cipondoh/Pinang) atau status bantuan, lalu klik <strong>Cetak PDF (RDP)</strong> atau <strong>Ekspor CSV/Excel</strong>. Kedua tombol otomatis mengikuti filter yang sedang aktif.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="surface-card p-4">
            <h5 class="fw-bold mb-3">Perbarui Kata Sandi Staf</h5>
            @if($errors->any())
                <div class="alert alert-danger small rounded-3 border-0">{{ $errors->first() }}</div>
            @endif
            <form action="{{ route('admin.pengaturan.password') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="password_lama" class="form-label fw-bold small">Kata Sandi Saat Ini</label>
                    <input type="password" id="password_lama" name="password_lama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password_baru" class="form-label fw-bold small">Kata Sandi Baru (Min. 6 Karakter)</label>
                    <input type="password" id="password_baru" name="password_baru" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label for="password_baru_confirmation" class="form-label fw-bold small">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_baru_confirmation" name="password_baru_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn-civic-primary w-100">Simpan Kata Sandi Baru</button>
            </form>
        </div>
    </div>
</div>
@endsection