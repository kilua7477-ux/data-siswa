<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Memastikan hanya user login yang bisa akses
    }

    public function index(Request $request)
    {
        $query = Siswa::query();

        // Fitur pencarian simpel untuk UX yang lebih baik
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
        }

        $siswas = $query->latest()->paginate(10);
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|numeric|unique:siswas,nisn',
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string'
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|numeric|unique:siswas,nisn,' . $siswa->id,
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string'
        ]);

        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'template-data-siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_excel.required' => 'Pilih file Excel terlebih dahulu!',
            'file_excel.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau .csv!',
            'file_excel.max' => 'Ukuran file maksimal adalah 5MB!',
        ]);

        try {
            $import = new SiswaImport;
            Excel::import($import, $request->file('file_excel'));

            $count = $import->getImportedCount();
            return redirect()->route('siswa.index')->with('success', "Berhasil mengimpor {$count} data siswa dari Excel!");
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }
}