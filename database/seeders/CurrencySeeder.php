<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
      {
          DB::table('currency')->insert([
              [
                  'name' => 'Dirham',
                  'code' => 'aed',
                  'status' =>1,
              ],
              [
                  'name' => 'United States Doller',
                  'code' => 'usd',
                  'status' =>1,
              ],
              [
                  'name' => 'Euros',
                  'code' => 'euro',
                  'status' =>1,
              ],
              [
                  'name' => 'Saudi Riyal',
                  'code' => 'sr',
                  'status' =>1,
              ],

          ]);
      }
}
