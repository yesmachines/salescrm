<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyConversionSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    DB::table('currency_conversion')->truncate();
    DB::table('currency_conversion')->insert([
      [
        'currency' => 'aed',
        'standard_rate' => '1',
        'date_on' => '2024-03-13',
        'status' => 1,
      ],
      [
        'currency' => 'usd',
        'standard_rate' => '3.6724',
        'date_on' => '2024-03-13',
        'status' => 1,
      ],
      [
        'currency' => 'sr',
        'standard_rate' => '0.9792',
        'date_on' => '2024-03-13',
        'status' => 1,
      ],
      [
        'currency' => 'euro',
        'standard_rate' => '4.0029',
        'date_on' => '2024-03-13',
        'status' => 1,
      ],

    ]);
  }
}
