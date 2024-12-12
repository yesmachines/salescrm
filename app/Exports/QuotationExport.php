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
    protected $supplier;

    public function __construct($quotationData, $supplierId)
    {
        $this->data = $quotationData;
        $this->supplier = $supplierId;
    }

    /**
     * Prepare the collection for export.
     *
     * @return \Illuminate\Support\Collection
     */
     public function collection()
     {
         $array = [];
         $datas = $this->data->load(['supplier', 'quotationItem.supplier']);

         foreach ($datas as $value) {
             $supplierBrand = $value->supplier->brand ?? 'N/A';
             if ($this->supplier && $value->supplier_id == $this->supplier) {
                 $supplierBrand = $value->supplier->brand ?? 'N/A';
             } else {
                 foreach ($value->quotationItem as $item) {
                     if (isset($item->supplier->brand) && ($this->supplier == $item->supplier->id || !$this->supplier)) {
                         $supplierBrand = $item->supplier->brand;
                         break;
                     }
                 }
             }
             $array[] = [
                 'REFERENCE NO' => $value->reference_no ?? '',
                 'Supplier' => $supplierBrand,
                 'Margin Price (AED)' => $value->gross_margin ?? '0.00',
                 'Submitted On' => $value->created_at ? $value->created_at->format('d-m-Y') : '',
                 'Status' => $value->status_id ? $value->quoteStatus->name : '--',
             ];
         }

         return collect($array);
     }


    /**
     * Register events for styling.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:E1'; // Adjust based on the number of columns
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }

    /**
     * Define column headings.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'REFERENCE NO',
            'SUPPLIER',
            'MARGIN PRICE (AED)',
            'SUBMITTED ON',
            'STATUS',
        ];
    }
}
