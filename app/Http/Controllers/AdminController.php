<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WargaDisabilitas;
use App\Models\PengajuanAlat;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalWarga = WargaDisabilitas::count();
        $totalAjuan = PengajuanAlat::count();
        $belumVerifikasi = WargaDisabilitas::where('is_verified', false)->count();

        $statusCount = [
            'Menunggu'    => PengajuanAlat::where('status', 'Menunggu')->count(),
            'Diproses'    => PengajuanAlat::where('status', 'Diproses')->count(),
            'Terealisasi' => PengajuanAlat::where('status', 'Terealisasi')->count(),
        ];

        $kecamatanCount = [
            'Cipondoh' => WargaDisabilitas::where('kecamatan', 'Cipondoh')->count(),
            'Pinang'   => WargaDisabilitas::where('kecamatan', 'Pinang')->count(),
        ];

        $ajuanTerbaru = PengajuanAlat::with('warga')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalWarga', 'totalAjuan', 'belumVerifikasi', 'statusCount', 'kecamatanCount', 'ajuanTerbaru'
        ));
    }

    public function petaGis()
    {
        return view('admin.peta-gis');
    }

    public function getMapAdminJson()
    {
        $data = WargaDisabilitas::with('pengajuanAlat')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($w) {
                $ajuan = $w->pengajuanAlat->first();
                return [
                    'id'          => $w->id,
                    'nama'        => $w->nama_lengkap,
                    'nik'         => $w->nik,
                    'no_hp'       => $w->no_hp ?? '-',
                    'kecamatan'   => $w->kecamatan,
                    'kelurahan'   => $w->kelurahan,
                    'alamat'      => $w->alamat_lengkap,
                    'kategori'    => $w->kategori_disabilitas,
                    'lat'         => (float) $w->latitude,
                    'lng'         => (float) $w->longitude,
                    'is_verified' => (bool) $w->is_verified,
                    'alat'        => $ajuan ? $ajuan->jenis_alat : 'Belum Mengajukan',
                    'status'      => $ajuan ? $ajuan->status : 'Belum Ada',
                    'edit_url'    => route('admin.warga.edit', $w->id),
                ];
            });

        return response()->json($data);
    }

    public function wargaIndex()
    {
        $wargas = WargaDisabilitas::with('pengajuanAlat')->latest()->get();
        return view('admin.warga-index', compact('wargas'));
    }

    public function wargaCreate()
    {
        return view('admin.warga-form', ['warga' => null]);
    }

    public function wargaStore(Request $request)
    {
        $validated = $request->validate([
            'nik'                  => 'required|digits:16|unique:warga_disabilitas,nik',
            'nama_lengkap'         => 'required|string|max:255',
            'no_hp'                => 'nullable|string|max:20',
            'kecamatan'            => 'required|in:Cipondoh,Pinang',
            'kelurahan'            => 'required|string|max:100',
            'alamat_lengkap'       => 'required|string|max:500',
            'kategori_disabilitas' => 'nullable|in:Tunadaksa,Tunanetra,Tunarungu,Tunawicara,Tunagrahita,Disabilitas Ganda',
            'latitude'             => 'required|numeric|between:-90,90',
            'longitude'            => 'required|numeric|between:-180,180',
        ]);

        $validated['is_verified'] = true;
        WargaDisabilitas::create($validated);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga penyandang disabilitas berhasil ditambahkan.');
    }

    public function wargaEdit($id)
    {
        $warga = WargaDisabilitas::findOrFail($id);
        return view('admin.warga-form', compact('warga'));
    }

    public function wargaUpdate(Request $request, $id)
    {
        $warga = WargaDisabilitas::findOrFail($id);
        $validated = $request->validate([
            'nik'                  => 'required|digits:16|unique:warga_disabilitas,nik,' . $warga->id,
            'nama_lengkap'         => 'required|string|max:255',
            'no_hp'                => 'nullable|string|max:20',
            'kecamatan'            => 'required|in:Cipondoh,Pinang',
            'kelurahan'            => 'required|string|max:100',
            'alamat_lengkap'       => 'required|string|max:500',
            'kategori_disabilitas' => 'nullable|in:Tunadaksa,Tunanetra,Tunarungu,Tunawicara,Tunagrahita,Disabilitas Ganda',
            'latitude'             => 'required|numeric|between:-90,90',
            'longitude'            => 'required|numeric|between:-180,180',
        ]);

        $validated['is_verified'] = true;
        $warga->update($validated);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil diperbarui dan diverifikasi.');
    }

    public function wargaDestroy($id)
    {
        WargaDisabilitas::findOrFail($id)->delete();
        return redirect()->route('admin.warga.index')->with('success', 'Data warga beserta riwayat pengajuannya berhasil dihapus.');
    }

    public function pengajuanIndex()
    {
        $pengajuans = PengajuanAlat::with('warga')->latest()->get();
        $wargas = WargaDisabilitas::orderBy('nama_lengkap')->get();
        return view('admin.pengajuan', compact('pengajuans', 'wargas'));
    }

    public function pengajuanStore(Request $request)
    {
        $validated = $request->validate([
            'warga_id'           => 'required|exists:warga_disabilitas,id',
            'jenis_alat'         => 'required|in:Kursi Roda,Kruk,Tongkat Penuntun,Alat Bantu Dengar,Kaki/Tangan Palsu,Lainnya,Kursi Roda Standart,Kursi Roda 3 in 1,Walker,Nutrisi,Sepatu AFO (Ankle Foot Orthosis),Tongkat Kaki 3 atau 4,Kursi Roda Adaptif anak,Kursi Roda CP,Kaki Palsu Diatas Lutut,Kursi Roda Adaptif Anak,Kaki Palsu Dibawah Lutut',
            'tanggal_pengajuan'  => 'required|date',
            'keterangan_kondisi' => 'nullable|string|max:1000',
        ]);

        PengajuanAlat::create([
            'warga_id'           => $validated['warga_id'],
            'jenis_alat'         => $validated['jenis_alat'],
            'keterangan_kondisi' => $validated['keterangan_kondisi'] ?? null,
            'status'             => 'Menunggu',
            'tanggal_pengajuan'  => $validated['tanggal_pengajuan'],
        ]);

        return back()->with('success', 'Data pengajuan alat bantu berhasil ditambahkan.');
    }

    public function pengajuanUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Terealisasi',
        ]);

        $pengajuan = PengajuanAlat::with('warga')->findOrFail($id);
        $pengajuan->update(['status' => $request->status]);

        if ($pengajuan->warga) {
            $pengajuan->warga->update(['is_verified' => true]);
        }

        return back()->with('success', 'Status pengajuan ' . ($pengajuan->warga->nama_lengkap ?? '') . ' diperbarui menjadi: ' . $request->status);
    }

    private function buildFilteredLaporanQuery(Request $request)
    {
        $query = PengajuanAlat::with('warga')->whereHas('warga');

        if ($request->filled('kecamatan')) {
            $query->whereHas('warga', fn($q) => $q->where('kecamatan', $request->kecamatan));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->latest('tanggal_pengajuan')->latest('id');
    }

    public function laporanIndex(Request $request)
    {
        $laporans = $this->buildFilteredLaporanQuery($request)->get();
        return view('admin.laporan', compact('laporans'));
    }

    public function cetakPdf(Request $request)
    {
        $laporans = $this->buildFilteredLaporanQuery($request)->get();

        $pdf = Pdf::loadView('admin.laporan-pdf', compact('laporans'))
                  ->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan-RDP-SIDISAB-Dapil-III.pdf');
    }

    public function exportExcel(Request $request)
    {
        // Perbaikan Bug: Menerapkan filter kecamatan dan status yang sama dengan tampilan pratinjau
        $laporans = $this->buildFilteredLaporanQuery($request)->get();
        $fileName = 'Rekap_Data_SIDISAB_Dapil_III_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'No', 'NIK', 'Nama Lengkap', 'No HP/WA', 'Kecamatan', 'Kelurahan',
            'Alamat Lengkap', 'Kategori Disabilitas', 'Alat Bantu Diajukan',
            'Status Bantuan', 'Tanggal Pengajuan'
        ];

        $callback = function() use ($laporans, $columns) {
            $file = fopen('php://output', 'w');
            // Tambahkan UTF-8 BOM agar Microsoft Excel langsung mengenali encoding karakter dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($laporans as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    "'" . ($row->warga->nik ?? '-'),
                    $row->warga->nama_lengkap ?? '-',
                    $row->warga->no_hp ?? '-',
                    $row->warga->kecamatan ?? '-',
                    $row->warga->kelurahan ?? '-',
                    $row->warga->alamat_lengkap ?? '-',
                    $row->warga->kategori_disabilitas ?? '-',
                    $row->jenis_alat,
                    $row->status,
                    $row->tanggal_pengajuan ? $row->tanggal_pengajuan->format('Y-m-d') : '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function pengaturanIndex()
    {
        return view('admin.pengaturan');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ], [
            'password_baru.min'       => 'Kata sandi baru minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (!Hash::check($request->password_lama, auth()->user()->password)) {
            return back()->withErrors(['password_lama' => 'Kata sandi lama yang Anda masukkan salah.']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Kata sandi akun berhasil diperbarui!');
    }
}