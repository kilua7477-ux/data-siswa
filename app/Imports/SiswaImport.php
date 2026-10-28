<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    protected int $importedCount = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Cek variasi nama kolom NISN / NIS
            $nisn = $row['nisn'] ?? $row['nis'] ?? null;
            $nama = $row['nama_lengkap'] ?? $row['nama'] ?? null;

            // Lewati baris jika NISN dan Nama kosong (baris kosong)
            if (empty($nisn) || empty($nama)) {
                continue;
            }

            // Normalisasi NISN (hapus spasi atau format angka ilmiah)
            $nisn = trim((string)$nisn);
            $nama = trim((string)$nama);

            // Normalisasi jenis kelamin
            $rawJk = strtolower(trim((string)($row['jenis_kelamin_lp'] ?? $row['jenis_kelamin'] ?? $row['jk'] ?? 'L')));
            $jenisKelamin = 'L';
            if (in_array($rawJk, ['p', 'perempuan', 'wanita', 'f', 'female'])) {
                $jenisKelamin = 'P';
            }

            $kelas = trim((string)($row['kelas'] ?? '-'));
            $alamat = isset($row['alamat']) ? trim((string)$row['alamat']) : null;

            // Simpan atau perbarui data siswa berdasarkan NISN
            Siswa::updateOrCreate(
                ['nisn' => $nisn],
                [
                    'nama' => $nama,
                    'jenis_kelamin' => $jenisKelamin,
                    'kelas' => $kelas,
                    'alamat' => $alamat,
                ]
            );

            $this->importedCount++;
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
}
