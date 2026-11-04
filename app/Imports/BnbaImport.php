<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BnbaImport implements WithMultipleSheets
{
    public const JENIS_ALAT = [
        'Kursi Roda Standart',
        'Kursi Roda 3 in 1',
        'Walker',
        'Alat Bantu Dengar',
        'Nutrisi',
        'Sepatu AFO (Ankle Foot Orthosis)',
        'Tongkat Kaki 3 atau 4',
        'Kursi Roda Adaptif anak',
        'Kursi Roda CP',
        'Kaki Palsu Diatas Lutut',
        'Kursi Roda Adaptif Anak',
        'Kaki Palsu Dibawah Lutut',
    ];

    private BnbaSheetImport $cipondoh;
    private BnbaSheetImport $pinang;

    public function __construct()
    {
        $this->cipondoh = new BnbaSheetImport('CIPONDOH');
        $this->pinang = new BnbaSheetImport('PINANG');
    }

    public function sheets(): array
    {
        return [
            'CIPONDOH' => $this->cipondoh,
            'PINANG' => $this->pinang,
        ];
    }

    public function errors(): array
    {
        return array_merge($this->cipondoh->errors(), $this->pinang->errors());
    }

    public function stats(): array
    {
        return [
            'rows' => $this->cipondoh->rowsRead() + $this->pinang->rowsRead(),
            'warga_created' => $this->cipondoh->wargaCreated() + $this->pinang->wargaCreated(),
            'warga_matched' => $this->cipondoh->wargaMatched() + $this->pinang->wargaMatched(),
            'pengajuan_created' => $this->cipondoh->pengajuanCreated() + $this->pinang->pengajuanCreated(),
            'pengajuan_skipped' => $this->cipondoh->pengajuanSkipped() + $this->pinang->pengajuanSkipped(),
        ];
    }
}
