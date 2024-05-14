<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromArray, WithHeadings, WithStyles
{
    use Exportable;

    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [
            'Title',
            'Model No',
            'PartNO',
            'Description',
            'Selling price',
            'Margin price',
            'MOSP',
            'Permissible Discount',
            'Product category',
            'Price basis',
            'Currency',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply bold font style to the headings
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        // Apply different color to the headings
        $sheet->getStyle('A1:M1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                     'rgb' => 'BDC3C7', // Ash color
                 ],
            ],
        ]);
    }
}
