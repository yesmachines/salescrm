<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomField;

class CustomFieldsSeeder extends Seeder
{
  public function run()
  {

    CustomField::truncate();

    // 1-Exworks Yesmachinery,2-FOB,3-CIF,4-DDU,5-DDP,6-manufacturer_factory
    $fields = [
      // Exworks manufacturer_factory
      [
        'field_name' => 'packing',
        'short_code'=>'packing',
        'price_basis' => '1',
        'sort_order' => '1',
        'status'=>'1'
      ],
      // Exworks our_warehouse
      [
        'field_name' => 'Freight',
        'short_code'=>'freight',
        'price_basis' => '12',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'Insurance',
        'short_code'=>'insurance',
        'price_basis' => '12',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'short_code'=>'packing',
        'price_basis' => '12',
        'sort_order' => '3',
        'status'=>'1'
      ],
      [
        'field_name' => 'Customs',
        'short_code'=>'customs',
        'price_basis' => '12',
        'sort_order' => '4',
        'status'=>'1'
      ],
      [
        'field_name' => 'Clearing',
        'short_code'=>'clearing',
        'price_basis' => '12',
        'sort_order' => '5',
        'status'=>'1'
      ],
      [
        'field_name' => 'BOE',
        'short_code'=>'boe',
        'price_basis' => '12',
        'sort_order' => '6',
        'status'=>'1'
      ],

      // FOB
      [
        'field_name' => 'packing',
        'short_code'=>'packing',
        'price_basis' => '2',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'Road transport to port',
        'short_code'=>'road_transport_to_port',
        'price_basis' => '2',
        'sort_order' => '2',
        'status'=>'1'
      ],
      // CIF
      [
        'field_name' => 'Freight',
        'short_code'=>'freight',
        'price_basis' => '3',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'short_code'=>'packing',
        'price_basis' => '3',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'Insurance',
        'short_code'=>'insurance',
        'price_basis' => '3',
        'sort_order' => '3',
        'status'=>'1'
      ],
      // DDP
      [
        'field_name' => 'Freight',
        'short_code'=>'freight',
        'price_basis' => '5',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'Insurance',
        'short_code'=>'insurance',
        'price_basis' => '5',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'short_code'=>'packing',
        'price_basis' => '5',
        'sort_order' => '3',
        'status'=>'1'
      ],
      [
        'field_name' => 'Customs',
        'short_code'=>'customs',
        'price_basis' => '5',
        'sort_order' => '4',
        'status'=>'1'
      ],
      [
        'field_name' => 'Clearing',
        'short_code'=>'clearing',
        'price_basis' => '5',
        'sort_order' => '5',
        'status'=>'1'
      ],
      [
        'field_name' => 'BOE',
        'short_code'=>'boe',
        'price_basis' => '5',
        'sort_order' => '6',
        'status'=>'1'
      ],
      [
        'field_name' => 'Delivery Charge',
        'short_code'=>'delivery_charge',
        'price_basis' => '5',
        'sort_order' => '7',
        'status'=>'1'
      ],
      [
        'field_name' => 'MOFAIC',
        'short_code'=>'mofaic',
        'price_basis' => '5',
        'sort_order' => '8',
        'status'=>'1'
      ],
      // DDU
      [
        'field_name' => 'Freight',
        'short_code'=>'freight',
        'price_basis' => '4',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'Insurance',
        'short_code'=>'insurance',
        'price_basis' => '4',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'short_code'=>'packing',
        'price_basis' => '4',
        'sort_order' => '3',
        'status'=>'1'
      ],
      [
        'field_name' => 'Clearing',
        'short_code'=>'clearing',
        'price_basis' => '4',
        'sort_order' => '4',
        'status'=>'1'
      ],
      [
        'field_name' => 'Handling and local transport',
        'short_code'=>'handling_and_local_transport',
        'price_basis' => '4',
        'sort_order' => '5',
        'status'=>'1'
      ],
      [
        'field_name' => 'BOE',
        'short_code'=>'boe',
        'price_basis' => '4',
        'sort_order' => '6',
        'status'=>'1'
      ],
    ];

    // Insert the new data
    foreach ($fields as $field) {
      CustomField::create($field);
    }
  }
}
