<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class QuotationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $statuses = array(
            [
                'name' => 'Cold',
                'priority' => 1,
                'status' => 1
            ],
            [
                'name' => 'Warm',
                'priority' => 2,
                'status' => 1
            ],
            [
                'name' => 'Hot',
                'priority' => 3,
                'status' => 1
            ],
            [
                'name' => 'OnHold',
                'priority' => 4,
                'status' => 1
            ],
            [
                'name' => 'Lost',
                'priority' => 5,
                'status' => 1
            ],
            [
                'name' => 'Win',
                'priority' => 6,
                'status' => 0
            ]
        );
        foreach ($statuses as $status) {
            DB::table('quotation_statuses')->insert($status);
        }
    }
}
