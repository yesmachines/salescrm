<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomField;

class CustomFieldsSeeder extends Seeder
{
  public function run()
  {
    // Truncate the table to remove existing data
    CustomField::truncate();

    // Define the fields to be inserted
    $fields = [
      [
        'field_name' => 'packing',
        'price_basis' => '1',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'price_basis' => '2',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'Road transport to port',
        'price_basis' => '2',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'Freight',
        'price_basis' => '3',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'price_basis' => '3',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'Insurance',
        'price_basis' => '3',
        'sort_order' => '3',
        'status'=>'1'
      ],
      [
        'field_name' => 'Freight',
        'price_basis' => '5',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'Insurance',
        'price_basis' => '5',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'price_basis' => '5',
        'sort_order' => '3',
        'status'=>'1'
      ],
      [
        'field_name' => 'Customs',
        'price_basis' => '5',
        'sort_order' => '4',
        'status'=>'1'
      ],
      [
        'field_name' => 'Clearing',
        'price_basis' => '5',
        'sort_order' => '5',
        'status'=>'1'
      ],
      [
        'field_name' => 'Freight',
        'price_basis' => '4',
        'sort_order' => '1',
        'status'=>'1'
      ],
      [
        'field_name' => 'Insurance',
        'price_basis' => '4',
        'sort_order' => '2',
        'status'=>'1'
      ],
      [
        'field_name' => 'packing',
        'price_basis' => '4',
        'sort_order' => '3',
        'status'=>'1'
      ],
      [
        'field_name' => 'Clearing',
        'price_basis' => '4',
        'sort_order' => '4',
        'status'=>'1'
      ],
      [
        'field_name' => 'Handling and local transport',
        'price_basis' => '4',
        'sort_order' => '5',
        'status'=>'1'
      ],
    ];

    // Insert the new data
    foreach ($fields as $field) {
      CustomField::create($field);
    }
  }
}
