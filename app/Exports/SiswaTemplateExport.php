<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin (L/P)',
            'Kelas',
            'Alamat',
        ];
    }

    public function array(): array
    {
        return [
            [
                '1002345678',
                'Ahmad Fauzi',
                'L',
                'XI RPL 1',
                'Jl. Mawar No. 12',
            ],
            [
                '1002345679',
                'Siti Nurhaliza',
                'P',
                'XI RPL 1',
                'Jl. Melati No. 45',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D6EFD'],
                ],
            ],
        ];
    }
}
