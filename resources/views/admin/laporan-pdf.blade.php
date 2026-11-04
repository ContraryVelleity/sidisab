<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan RDP SIDISAB Dapil III Kota Tangerang</title>
    <style>
        body { font-family: sans-serif; font-size: 11.5px; color: #111; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background-color: #f1f5f9; }
        .ttd { margin-top: 35px; float: right; text-align: center; width: 250px; }
    </style>
</head>
<body>
    <div class="kop">
        <h2 style="margin:0;">REKAPITULASI PENDATAAN PENYANDANG DISABILITAS &amp; ALAT BANTU</h2>
        <h3 style="margin:4px 0;">DAERAH PEMILIHAN (DAPIL) III KOTA TANGERANG (KEC. CIPONDOH &amp; KEC. PINANG)</h3>
        <small>Dokumen Bukti Data Lapangan untuk Bahan Rapat Dengar Pendapat (RDP) Bersama Dinas Sosial Kota Tangerang</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Warga</th>
                <th>Kecamatan / Kelurahan</th>
                <th>Alamat Lengkap</th>
                <th>Kategori Disabilitas</th>
                <th>Alat Bantu Diajukan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->warga->nik ?? '-' }}</td>
                <td>{{ $row->warga->nama_lengkap ?? '-' }}</td>
                <td>{{ $row->warga->kecamatan ?? '-' }} / {{ $row->warga->kelurahan ?? '-' }}</td>
                <td>{{ $row->warga->alamat_lengkap ?? '-' }}</td>
                <td>{{ $row->warga->kategori_disabilitas ?? '-' }}</td>
                <td>{{ $row->jenis_alat }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd">
        <p>Kota Tangerang, {{ date('d F Y') }}<br>Koordinator Tim Pendata Dapil III</p>
        <br><br><br>
        <p><strong>( _______________________ )</strong></p>
    </div>
</body>
</html>