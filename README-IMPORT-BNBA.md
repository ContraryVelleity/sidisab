# Implementasi Import BNBA SIDISAB

Implementasi ini ditambahkan pada source Laravel SIDISAB yang diberikan.

## Yang ditambahkan
- Menu admin **Import Data BNBA**.
- Import dua sheet Excel dengan nama persis `CIPONDOH` dan `PINANG`.
- Pencocokan warga berdasarkan NIK, sehingga NIK yang sama tidak membuat baris warga baru. Jika NIK sudah ada, nama dan wilayah/alamat disinkronkan dari BNBA; no_hp, kategori disabilitas, dan koordinat yang sudah ada tetap dipertahankan.
- Setiap jenis bantuan dicatat sebagai pengajuan pada `pengajuan_alats`.
- Validasi seluruh baris dan transaksi database: bila ada baris tidak valid, semua perubahan import dibatalkan.
- Pencegahan pengajuan duplikat saat file BNBA 2026 yang sama diunggah kembali.
- Kolom `kategori_disabilitas` dibuat nullable karena tidak ada pada Excel.
- ENUM `jenis_alat` diperluas sesuai 12 nilai yang benar-benar ditemukan pada workbook.

## Cara memasang

1. Ekstrak folder source proyek.
2. Buka terminal pada folder proyek, lalu jalankan:

   ```bash
   composer require maatwebsite/excel:^3.1
   ```

3. Pastikan `.env` menunjuk ke database MySQL `db_sidisab` yang sudah diimpor dari `db_sidisab.sql`.
4. Jalankan migrasi baru:

   ```bash
   php artisan migrate
   ```

5. Bersihkan cache konfigurasi:

   ```bash
   php artisan optimize:clear
   ```

6. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

7. Login sebagai admin, lalu buka menu **Import Data BNBA** atau URL `/admin/import-bnba`.
8. Unggah `BNBA Alat Bantu Disabilitas Tahun 2026.xlsx`.

## Pemetaan workbook

| Excel | Tabel/kolom SIDISAB |
|---|---|
| NIK Calon Penerima | warga_disabilitas.nik |
| Nama Calon Penerima | warga_disabilitas.nama_lengkap |
| Alamat Calon Penerima | warga_disabilitas.alamat_lengkap |
| Kecamatan | warga_disabilitas.kecamatan |
| Kelurahan | warga_disabilitas.kelurahan |
| Jenis Alat Bantu | pengajuan_alats.jenis_alat |
| NIK yang sama | satu warga yang sama |

Nomor KK, tempat lahir, tanggal lahir, jenis kelamin, dan umur tidak disimpan karena pengguna memilih tidak menambah kolom database. `no_hp`, koordinat, dan kategori disabilitas dibiarkan NULL jika warga baru karena kolom tersebut tidak tersedia pada workbook. Status pengajuan awal `Menunggu`. Karena workbook tidak memiliki tanggal pengajuan, sistem memakai tanggal saat import.

## Catatan data workbook

Workbook yang diperiksa memiliki 2 sheet: `CIPONDOH` (171 baris data) dan `PINANG` (44 baris data), total 215 baris. Ada 11 baris tambahan akibat NIK yang muncul lebih dari sekali; NIK yang sama tetap menjadi satu data warga, sedangkan jenis bantuan berbeda tetap menjadi pengajuan terpisah. Ditemukan 2 baris dengan kombinasi NIK dan jenis bantuan yang sama; import menghindari duplikasi untuk kombinasi BNBA 2026 tersebut.

Jenis alat bantu yang dimasukkan ke ENUM adalah 12 label unik persis dari file Excel, termasuk dua variasi kapitalisasi `Kursi Roda Adaptif anak` dan `Kursi Roda Adaptif Anak`.

## Perhatian

- Migrasi perubahan ENUM menggunakan sintaks MySQL, sesuai database dump proyek (`MySQL 8`).
- Lakukan backup database sebelum migrasi dan import.
- Jangan menjalankan `migrate:fresh` pada database yang sudah berisi data.
- Jika migrasi di-rollback, skema ENUM tidak otomatis dikembalikan karena nilai alat bantu baru dan kategori NULL mungkin sudah tersimpan.
- Import memakai transaksi database. Pastikan kedua tabel menggunakan InnoDB.
