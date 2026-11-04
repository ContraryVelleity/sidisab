<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warga_disabilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('no_hp', 20)->nullable();
            $table->enum('kecamatan', ['Cipondoh', 'Pinang']);
            $table->string('kelurahan');
            $table->text('alamat_lengkap');
            $table->enum('kategori_disabilitas', [
                'Tunadaksa', 'Tunanetra', 'Tunarungu', 'Tunawicara', 'Tunagrahita', 'Disabilitas Ganda'
            ]);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warga_disabilitas');
    }
};