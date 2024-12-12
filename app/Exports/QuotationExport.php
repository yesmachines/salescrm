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
    $datas = $this->data;

    foreach ($datas as $key => $value) {

      $supplierBrand = 'N/A';

      if ($value->supplier_id != 0) {
        $supplierBrand = optional($value->supplier)->brand ?? 'N/A';
      } else {

        $filteredItems = $value->quotationItem->filter(function ($item) {
          return isset($item->supplier->brand) && $item->supplier->id == $this->supplier;
        });


        if ($filteredItems->isNotEmpty()) {
          $supplierBrand = $filteredItems->first()->supplier->brand;
        } else {
          foreach ($value->quotationItem as $item) {
            if (isset($item->supplier->brand)) {
              $supplierBrand = $item->supplier->brand;
              break;
            }
          }
        }
      }

      $array[] = [
        'REFERENCE NO' => $value->reference_no ?? '',
        'Supplier' => $supplierBrand,
        'Margin Price (AED)' => $value->gross_margin ?? '0.00',
        'Submitted On' => $value->created_at ? $value->created_at->format('d-m-Y') : '',
        'Status' => $value->status_id? $value->quoteStatus->name: '--',
      ];
    }

    return collect($array);
  }
  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $cellRange = 'A1:G1';
        $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
      },
    ];
  }

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
