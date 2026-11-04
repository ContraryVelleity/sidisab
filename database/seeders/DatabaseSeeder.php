<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WargaDisabilitas;
use App\Models\PengajuanAlat;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sidisab.com'],
            [
                'name' => 'Admin Staf Dapil III',
                'password' => Hash::make('password123'),
            ]
        );

        $dataWarga = [
            [
                'nik' => '3671051205700001',
                'nama_lengkap' => 'Ahmad Supriyadi',
                'no_hp' => '081211223344',
                'kecamatan' => 'Cipondoh',
                'kelurahan' => 'Cipondoh Makmur',
                'alamat_lengkap' => 'Jl. Maulana Hasanudin RT 002/004 No. 15',
                'kategori_disabilitas' => 'Tunadaksa',
                'latitude' => -6.18351000,
                'longitude' => 106.68120000,
                'is_verified' => true,
                'alat' => 'Kursi Roda',
                'status' => 'Menunggu',
                'hari_lalu' => 3,
                'ket' => 'Mengalami kelumpuhan kaki pasca stroke 2 tahun lalu.'
            ],
            [
                'nik' => '3671054408850002',
                'nama_lengkap' => 'Siti Aminah',
                'no_hp' => '081355667788',
                'kecamatan' => 'Cipondoh',
                'kelurahan' => 'Gondrong',
                'alamat_lengkap' => 'Gg. H. Mansyur RT 004/001',
                'kategori_disabilitas' => 'Tunanetra',
                'latitude' => -6.19140000,
                'longitude' => 106.69230000,
                'is_verified' => true,
                'alat' => 'Tongkat Penuntun',
                'status' => 'Diproses',
                'hari_lalu' => 7,
                'ket' => 'Butuh tongkat lipat penuntun jalan.'
            ],
            [
                'nik' => '3671111902650003',
                'nama_lengkap' => 'Budi Santoso',
                'no_hp' => '085799887766',
                'kecamatan' => 'Pinang',
                'kelurahan' => 'Panunggangan Utara',
                'alamat_lengkap' => 'Jl. Kyai Maja RT 001/003',
                'kategori_disabilitas' => 'Tunadaksa',
                'latitude' => -6.21560000,
                'longitude' => 106.65410000,
                'is_verified' => true,
                'alat' => 'Kruk',
                'status' => 'Terealisasi',
                'hari_lalu' => 14,
                'ket' => 'Kaki kanan diamputasi akibat kecelakaan kerja.'
            ],
            [
                'nik' => '3671115511980004',
                'nama_lengkap' => 'Rina Oktaviani',
                'no_hp' => '087812345678',
                'kecamatan' => 'Pinang',
                'kelurahan' => 'Kunciran',
                'alamat_lengkap' => 'Jl. Sultan Ageng Tirtayasa RT 003/005',
                'kategori_disabilitas' => 'Tunarungu',
                'latitude' => -6.22180000,
                'longitude' => 106.67150000,
                'is_verified' => true,
                'alat' => 'Alat Bantu Dengar',
                'status' => 'Diproses',
                'hari_lalu' => 5,
                'ket' => 'Gangguan pendengaran berat sejak lahir.'
            ],
        ];

        foreach ($dataWarga as $item) {
            $warga = WargaDisabilitas::updateOrCreate(
                ['nik' => $item['nik']],
                [
                    'nama_lengkap' => $item['nama_lengkap'],
                    'no_hp' => $item['no_hp'],
                    'kecamatan' => $item['kecamatan'],
                    'kelurahan' => $item['kelurahan'],
                    'alamat_lengkap' => $item['alamat_lengkap'],
                    'kategori_disabilitas' => $item['kategori_disabilitas'],
                    'latitude' => $item['latitude'],
                    'longitude' => $item['longitude'],
                    'is_verified' => $item['is_verified'],
                ]
            );

            PengajuanAlat::create([
                'warga_id' => $warga->id,
                'jenis_alat' => $item['alat'],
                'keterangan_kondisi' => $item['ket'],
                'status' => $item['status'],
                'tanggal_pengajuan' => now()->subDays($item['hari_lalu'])->toDateString(),
            ]);
        }
    }
}