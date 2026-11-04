<?php

namespace App\Http\Controllers;

use App\Imports\BnbaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportBnbaController extends Controller
{
    public function index()
    {
        return view('admin.import-bnba');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes' => 'File harus berformat .xlsx atau .xls.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        $import = new BnbaImport();

        try {
            DB::transaction(function () use ($request, $import) {
                Excel::import($import, $request->file('file'));

                if (!empty($import->errors())) {
                    throw new \RuntimeException(implode("\n", $import->errors()));
                }
            });

            return redirect()->route('admin.import-bnba.index')
                ->with('success', 'Import BNBA berhasil diproses.')
                ->with('import_stats', $import->stats());
        } catch (Throwable $e) {
            report($e);

            $message = $e instanceof \RuntimeException
                ? $e->getMessage()
                : 'Terjadi kesalahan saat membaca file atau menyimpan data. Periksa format Excel dan log Laravel.';

            return back()->withInput()->with('import_error', $message);
        }
    }
}
