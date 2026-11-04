<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WargaDisabilitas extends Model
{
    use HasFactory;

    protected $table = 'warga_disabilitas';
    protected $guarded = ['id'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_verified' => 'boolean',
    ];

    public function pengajuanAlat()
    {
        return $this->hasMany(PengajuanAlat::class, 'warga_id')
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('id');
    }
}