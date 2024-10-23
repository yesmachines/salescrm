<?php

namespace App\Exports;
use App\Models\Coilpanel\Lead;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class SummaryNumberExport implements FromCollection,WithHeadings, ShouldAutoSize,WithEvents
{
  use Exportable;

  public function __construct($summaryNumbers){

    $this->data=$summaryNumbers;

  }
  /**
  * @return \Illuminate\Support\Collection
  */
  public function collection()
  {


    $array=[];
    $datas=$this->data;

    foreach ($datas as $key => $value) {


      $array[]=([
        'os number'            => $value->os_number ?:'',
        'Received Date'        => $value->po_received ?:'',
        'EMP'                  => $value->assigned->user->name ?:'',
        'Supplier/Brand' => $value->supplier_brands ?? $value->stock_supplier ?? '',
        'Material Status' =>
        $value->material_status == 'is_stock' ? 'In Stock' :
        ($value->material_status == 'out_stock' ? 'Out of Stock' : 'Stock OS'),

        'Material'             => $value->product_categories  ?:'',
        'Client' => optional($value->company)->company ?: '',
        'Country' => optional(optional($value->company)->country)->name ?: '',
        'Product'              =>  $value->product_names ?? $value->stock_product ?? '',
        'VAT Status' => optional($value->quotation)->is_vat == 1 ? 'Include' : 'Exclude',
        'Vat Amount' => optional($value->quotation)->vat_amount ?: '',
        'Total Selling Price' => $value->selling_price ?? '',
        'Total Buying Price' => $value->buying_price ?? '',
        'Margin'               =>$value->projected_margin ?:'N/A',

      ]);
    }


    return collect($array);
  }
  public function registerEvents(): array
  {
    return [
      AfterSheet::class    => function(AfterSheet $event) {
        $cellRange = 'A1:W1'; // All headers
        $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
      },
    ];
  }
  public function headings(): array
  {

    return [
      'SUMMARY NUMBER',
      'RECEIVED DATE',
      'EMP',
      'SUPPLIER/BRAND',
      'MATERIAL STATUS',
      'MATERIAL',
      'CLIENT',
      'COUNTRY',
      'PRODUCT',
      'VAT STATUS',
      'VAT AMOUNT',
      'TOTAL SELLING PRICE',
      'TOTAL BUYING PRICE',
      'MARGIN'
  ];

  }
}
