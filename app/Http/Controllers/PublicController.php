<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WargaDisabilitas;
use App\Models\PengajuanAlat;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $totalWarga = WargaDisabilitas::count();
        $totalAjuan = PengajuanAlat::count();
        $totalSelesai = PengajuanAlat::where('status', 'Terealisasi')->count();

        $kategoriStats = WargaDisabilitas::selectRaw('kategori_disabilitas, count(*) as total')
            ->groupBy('kategori_disabilitas')
            ->pluck('total', 'kategori_disabilitas');

        $hasilCekNik = null;
        if ($request->filled('nik')) {
            $nikBersih = preg_replace('/\D/', '', $request->nik);
            $hasilCekNik = WargaDisabilitas::with('pengajuanAlat')
                ->where('nik', $nikBersih)
                ->first();
        }

        return view('public.landing', compact(
            'totalWarga', 'totalAjuan', 'totalSelesai', 'kategoriStats', 'hasilCekNik'
        ));
    }

    public function getMapPublicJson()
    {
        $data = WargaDisabilitas::with('pengajuanAlat')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($w) {
                $ajuan = $w->pengajuanAlat->first();
                return [
                    'nama_sensor' => Str::mask($w->nama_lengkap, '*', 3),
                    'kecamatan'   => $w->kecamatan,
                    'kelurahan'   => $w->kelurahan,
                    'kategori'    => $w->kategori_disabilitas,
                    'lat'         => (float) $w->latitude,
                    'lng'         => (float) $w->longitude,
                    'alat'        => $ajuan ? $ajuan->jenis_alat : '-',
                    'status'      => $ajuan ? $ajuan->status : 'Terdata',
                ];
            });

        return response()->json($data);
    }

    public function formPengajuan()
    {
        return view('public.ajukan');
    }

    public function simpanPengajuan(Request $request)
    {
        $validated = $request->validate([
            'nik'                  => 'required|digits:16',
            'nama_lengkap'         => 'required|string|max:255',
            'no_hp'                => 'required|string|max:20',
            'kecamatan'            => 'required|in:Cipondoh,Pinang',
            'kelurahan'            => 'required|string|max:100',
            'alamat_lengkap'       => 'required|string|max:500',
            'kategori_disabilitas' => 'required|in:Tunadaksa,Tunanetra,Tunarungu,Tunawicara,Tunagrahita,Disabilitas Ganda',
            'jenis_alat'           => 'required|in:Kursi Roda,Kruk,Tongkat Penuntun,Alat Bantu Dengar,Kaki/Tangan Palsu,Lainnya',
            'keterangan_kondisi'   => 'nullable|string|max:1000',
            'latitude'             => 'nullable|numeric|between:-90,90',
            'longitude'            => 'nullable|numeric|between:-180,180',
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits'   => 'NIK harus berjumlah tepat 16 digit angka.',
        ]);

        // Cek apakah warga sudah pernah terdata tetapi masih memiliki pengajuan yang aktif (Menunggu/Diproses)
        $existingWarga = WargaDisabilitas::where('nik', $validated['nik'])->first();
        if ($existingWarga) {
            $ajuanAktif = $existingWarga->pengajuanAlat()
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->first();

            if ($ajuanAktif) {
                return redirect()->route('public.landing', ['nik' => $validated['nik']])
                    ->with('info', 'NIK ini sudah memiliki permohonan aktif dengan status "' . $ajuanAktif->status . '". Berikut rincian status Anda.');
            }

            // Perbarui kontak/lokasi jika warga sudah ada namun ingin mengajukan permohonan baru
            $existingWarga->update([
                'no_hp'          => $validated['no_hp'],
                'alamat_lengkap' => $validated['alamat_lengkap'],
                'latitude'       => $validated['latitude'] ?? $existingWarga->latitude,
                'longitude'      => $validated['longitude'] ?? $existingWarga->longitude,
            ]);
            $warga = $existingWarga;
        } else {
            $warga = WargaDisabilitas::create([
                'nik'                  => $validated['nik'],
                'nama_lengkap'         => $validated['nama_lengkap'],
                'no_hp'                => $validated['no_hp'],
                'kecamatan'            => $validated['kecamatan'],
                'kelurahan'            => $validated['kelurahan'],
                'alamat_lengkap'       => $validated['alamat_lengkap'],
                'kategori_disabilitas' => $validated['kategori_disabilitas'],
                'latitude'             => $validated['latitude'],
                'longitude'            => $validated['longitude'],
                'is_verified'          => false,
            ]);
        }

        PengajuanAlat::create([
            'warga_id'           => $warga->id,
            'jenis_alat'         => $validated['jenis_alat'],
            'keterangan_kondisi' => $validated['keterangan_kondisi'],
            'status'             => 'Menunggu',
            'tanggal_pengajuan'  => now()->toDateString(),
        ]);

        return redirect()->route('public.landing', ['nik' => $validated['nik']])
            ->with('success', 'Permohonan alat bantu berhasil dikirim! Silakan pantau status verifikasi di bawah ini.');
    }
}