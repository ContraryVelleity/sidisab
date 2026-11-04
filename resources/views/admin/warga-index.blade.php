@extends('layouts.admin')
@section('title', 'Kelola Data Warga Penyandang Disabilitas')

@section('content')
<div class="surface-card overflow-hidden">
    <div class="p-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="border-bottom: 1px solid var(--border-hairline);">
        <div class="flex-grow-1" style="max-width: 380px;">
            <label for="searchWarga" class="visually-hidden">Cari Nama atau NIK Warga</label>
            <input type="search" id="searchWarga" class="form-control" placeholder="Cari nama, NIK, atau kelurahan...">
        </div>
        <a href="{{ route('admin.warga.create') }}" class="btn-civic-primary text-decoration-none">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah Data Warga
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-minimal mb-0" id="tableWarga">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Identitas Warga</th>
                    <th>Domisili (Kec/Kel)</th>
                    <th>Kategori Disabilitas</th>
                    <th>Titik Koordinat</th>
                    <th class="text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wargas as $i => $w)
                <tr>
                    <td class="tabular-nums">{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-bold">{{ $w->nama_lengkap }}</div>
                        <div class="small text-secondary tabular-nums">NIK: {{ $w->nik }} | WA: {{ $w->no_hp ?? '-' }}</div>
                        @if(!$w->is_verified)
                            <span class="badge bg-warning text-dark mt-1">Ajuan Mandiri (Perlu Cek)</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">Kec. {{ $w->kecamatan }}</div>
                        <div class="small text-secondary">Kel. {{ $w->kelurahan }} — {{ $w->alamat_lengkap }}</div>
                    </td>
                    <td>
                        <span class="px-2 py-1 rounded-2 small fw-bold" style="background: var(--bg-subtle);">
                            {{ $w->kategori_disabilitas ?? 'Belum diisi' }}
                        </span>
                    </td>
                    <td class="tabular-nums small text-secondary">
                        @if($w->latitude && $w->longitude)
                            {{ number_format($w->latitude, 5) }},<br>{{ number_format($w->longitude, 5) }}
                        @else
                            <span class="text-danger">Belum diset</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('admin.warga.edit', $w->id) }}" class="btn btn-sm btn-outline-primary rounded-3">Edit</a>
                            <form action="{{ route('admin.warga.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Hapus data warga ini beserta riwayat pengajuannya?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-secondary">Belum ada data warga terdata.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchWarga').addEventListener('input', function(e) {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#tableWarga tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endpush