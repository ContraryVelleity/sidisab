<?php

namespace App\Imports;

use App\Models\PengajuanAlat;
use App\Models\WargaDisabilitas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class BnbaSheetImport implements ToCollection, WithHeadingRow
{
    private array $errors = [];
    private int $rowsRead = 0;
    private int $wargaCreated = 0;
    private int $wargaMatched = 0;
    private int $pengajuanCreated = 0;
    private int $pengajuanSkipped = 0;
    private string $label = 'BNBA';

    public function __construct(string $label = 'BNBA')
    {
        $this->label = $label;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $rowCollection) {
            $row = $rowCollection->toArray();
            $excelRow = $index + 2; // baris 1 adalah header

            if (count(array_filter($row, fn ($value) => $value !== null && $value !== '')) === 0) {
                continue;
            }

            $this->rowsRead++;
            $nik = preg_replace('/\s+/', '', (string) ($row['nik_calon_penerima'] ?? ''));
            $nama = trim((string) ($row['nama_calon_penerima'] ?? ''));
            $kecamatanRaw = strtoupper(trim((string) ($row['kecamatan'] ?? '')));
            $kecamatan = match ($kecamatanRaw) {
                'CIPONDOH' => 'Cipondoh',
                'PINANG' => 'Pinang',
                default => $kecamatanRaw,
            };
            $kelurahan = trim((string) ($row['kelurahan'] ?? ''));
            $alamat = trim((string) ($row['alamat_calon_penerima'] ?? ''));
            $jenisAlat = trim((string) ($row['jenis_alat_bantu'] ?? ''));

            $validator = Validator::make([
                'nik' => $nik,
                'nama_lengkap' => $nama,
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
                'alamat_lengkap' => $alamat,
                'jenis_alat' => $jenisAlat,
            ], [
                'nik' => ['required', 'regex:/^[0-9]{16}$/'],
                'nama_lengkap' => ['required', 'string', 'max:255'],
                'kecamatan' => ['required', 'in:Cipondoh,Pinang'],
                'kelurahan' => ['required', 'string', 'max:255'],
                'alamat_lengkap' => ['required', 'string'],
                'jenis_alat' => ['required', 'in:' . implode(',', BnbaImport::JENIS_ALAT)],
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $this->errors[] = "Sheet {$this->sheetLabel()} baris {$excelRow}: {$message}";
                }
                continue;
            }

            $warga = WargaDisabilitas::where('nik', $nik)->first();
            if ($warga) {
                // NIK menjadi kunci pencocokan. Sinkronkan kolom identitas/wilayah
                // dari BNBA, tetapi pertahankan no_hp, kategori, dan koordinat yang
                // mungkin sudah dilengkapi admin di aplikasi.
                $warga->update([
                    'nama_lengkap' => $nama,
                    'kecamatan' => $kecamatan,
                    'kelurahan' => $kelurahan,
                    'alamat_lengkap' => $alamat,
                ]);
                $this->wargaMatched++;
            } else {
                $warga = WargaDisabilitas::create([
                    'nik' => $nik,
                    'nama_lengkap' => $nama,
                    'no_hp' => null,
                    'kecamatan' => $kecamatan,
                    'kelurahan' => $kelurahan,
                    'alamat_lengkap' => $alamat,
                    // File BNBA tidak menyediakan kategori disabilitas maupun koordinat.
                    'kategori_disabilitas' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'is_verified' => true,
                ]);
                $this->wargaCreated++;
            }

            // File BNBA tidak memiliki tanggal pengajuan; gunakan tanggal import.
            // firstOrCreate mencegah baris bantuan yang sama terduplikasi saat file diimpor ulang.
            $tanggal = now()->toDateString();
            $sumberImport = 'Diimpor dari BNBA Alat Bantu Disabilitas Tahun 2026';
            $pengajuan = PengajuanAlat::firstOrCreate(
                [
                    'warga_id' => $warga->id,
                    'jenis_alat' => $jenisAlat,
                    'keterangan_kondisi' => $sumberImport,
                ],
                [
                    'tanggal_pengajuan' => $tanggal,
                    'status' => 'Menunggu',
                ]
            );

            if ($pengajuan->wasRecentlyCreated) {
                $this->pengajuanCreated++;
            } else {
                $this->pengajuanSkipped++;
            }
        }
    }

    protected function sheetLabel(): string
    {
        return $this->label;
    }

    public function errors(): array { return $this->errors; }
    public function rowsRead(): int { return $this->rowsRead; }
    public function wargaCreated(): int { return $this->wargaCreated; }
    public function wargaMatched(): int { return $this->wargaMatched; }
    public function pengajuanCreated(): int { return $this->pengajuanCreated; }
    public function pengajuanSkipped(): int { return $this->pengajuanSkipped; }

    public static function parseExcelDate(mixed $value): ?string
    {
        if ($value === null || $value === '') return null;
        if ($value instanceof \DateTimeInterface) return $value->format('Y-m-d');
        if (is_numeric($value)) return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        try { return date('Y-m-d', strtotime((string) $value)); } catch (\Throwable) { return null; }
    }
}
