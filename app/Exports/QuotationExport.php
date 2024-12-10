<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;

class QuotationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    protected $data;

    public function __construct($quotationData)
    {
        $this->data = $quotationData;
    }

    /**
     * Prepare the collection for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $array = [];
        $datas = $this->data;

        foreach ($datas as $key => $value) {
            // Check supplier logic as per the Blade code
            $supplierBrand = 'N/A';
            if ($value->supplier_id != 0) {
                // If supplier exists, use supplier's brand
                $supplierBrand = optional($value->supplier)->brand ?? 'N/A';
            } else {
                // If no supplier, loop through quotation items and fetch first supplier's brand
                foreach ($value->quotationItem as $item) {
                    if (isset($item->supplier->brand)) {
                        $supplierBrand = $item->supplier->brand;
                        break; // Stop loop after first item
                    }
                }
            }

            $array[] = [
                'REFERENCE NO' => $value->reference_no ?? '',
                'Supplier/Brand' => $supplierBrand,
                'Margin Price (AED)' => $value->gross_margin ?? '0.00',
                'Submitted On' => $value->created_at ? $value->created_at->format('d-m-Y') : '',
                'Status' => $value->status_id == 6 ? 'WON' : 'PENDING',
            ];
        }

        return collect($array);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:G1'; // Adjust as needed for the number of columns
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'REFERENCE NO',
            'SUPPLIER/BRAND',
            'MARGIN PRICE (AED)',
            'SUBMITTED ON',
            'STATUS',
        ];
    }
}
