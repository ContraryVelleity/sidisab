<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_alats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('warga_disabilitas')->onDelete('cascade');
            $table->enum('jenis_alat', [
                'Kursi Roda', 'Kruk', 'Tongkat Penuntun', 'Alat Bantu Dengar', 'Kaki/Tangan Palsu', 'Lainnya'
            ]);
            $table->text('keterangan_kondisi')->nullable();
            $table->enum('status', ['Menunggu', 'Diproses', 'Terealisasi'])->default('Menunggu');
            $table->date('tanggal_pengajuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_alats');
    }
};