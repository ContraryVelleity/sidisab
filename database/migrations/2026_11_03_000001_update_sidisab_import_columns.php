
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah kategori disabilitas agar boleh NULL.
        DB::statement("
            ALTER TABLE warga_disabilitas
            MODIFY kategori_disabilitas
            ENUM(
                'Tunadaksa',
                'Tunanetra',
                'Tunarungu',
                'Tunawicara',
                'Tunagrahita',
                'Disabilitas Ganda'
            ) NULL DEFAULT NULL
        ");

        // Menambahkan jenis alat bantu dari data BNBA 2026.
        DB::statement("
            ALTER TABLE pengajuan_alats
            MODIFY jenis_alat ENUM(
                'Kursi Roda',
                'Kruk',
                'Tongkat Penuntun',
                'Alat Bantu Dengar',
                'Kaki/Tangan Palsu',
                'Lainnya',
                'Kursi Roda Standart',
                'Kursi Roda 3 in 1',
                'Walker',
                'Nutrisi',
                'Sepatu AFO (Ankle Foot Orthosis)',
                'Tongkat Kaki 3 atau 4',
                'Kursi Roda Adaptif Anak',
                'Kursi Roda CP',
                'Kaki Palsu Diatas Lutut',
                'Kaki Palsu Dibawah Lutut'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        // Tidak mengembalikan ENUM lama agar data BNBA tidak hilang.
    }
};
