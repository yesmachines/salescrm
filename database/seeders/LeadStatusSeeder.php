<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class LeadStatusSeeder extends Seeder
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
                'name' => 'Open',
                'priority' => 1,
                'status' => 1
            ],
            [
                'name' => 'Good',
                'priority' => 2,
                'status' => 1
            ],
            [
                'name' => 'Very Good',
                'priority' => 3,
                'status' => 1
            ],
            [
                'name' => 'Not to Proceed',
                'priority' => 4,
                'status' => 1
            ],
            [
                'name' => 'Future Potential',
                'priority' => 5,
                'status' => 1
            ],
            [
                'name' => 'Converted',
                'priority' => 6,
                'status' => 0
            ]

        );
        foreach ($statuses as $status) {
            DB::table('lead_statuses')->insert($status);
        }
    }
}
