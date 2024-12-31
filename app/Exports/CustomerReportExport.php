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

class CustomerReportExport implements FromCollection,WithHeadings, ShouldAutoSize,WithEvents
{
  use Exportable;

  public function __construct($customer_data){

    $this->data=$customer_data;

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
        'Customer Name'       => $value->fullname,
        'Company'             => $value->company_name ?:'',
        'Mobile Number'       =>$value->phone,
        'Email Id'            => $value->email?:'',
        'Region'             => $value->state ?? '',
        'Country'             => $value->country_name ?? '',
        'Reference No'        => $value->company_reference_no ?:'',

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

      'Name',
      'Company',
      'Mobile Number',
      'Email Id',
      'Region',
      'Country',
      'Reference No',
    ];
  }
}
