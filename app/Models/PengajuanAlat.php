<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanAlat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_alats';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];

    public function warga()
    {
        return $this->belongsTo(WargaDisabilitas::class, 'warga_id');
    }
}