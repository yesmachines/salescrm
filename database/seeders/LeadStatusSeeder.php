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
                'is_qualify' => 0,
                'send_mail' => 0,
                'priority' => 1,
                'status' => 1
            ],
            [
                'name' => 'Good',
                'is_qualify' => 1,
                'send_mail' => 0,
                'priority' => 2,
                'status' => 1
            ],
            [
                'name' => 'Very Good',
                'is_qualify' => 1,
                'send_mail' => 0,
                'priority' => 3,
                'status' => 1
            ],
            [
                'name' => 'Not to Proceed',
                'is_qualify' => 0,
                'send_mail' => 0,
                'priority' => 4,
                'status' => 1
            ],
            [
                'name' => 'Future Potential',
                'is_qualify' => 1,
                'send_mail' => 0,
                'priority' => 5,
                'status' => 1
            ],
            [
                'name' => 'Converted',
                'is_qualify' => 0,
                'send_mail' => 0,
                'priority' => 6,
                'status' => 0
            ]

        );
        foreach ($statuses as $status) {
            DB::table('lead_statuses')->insert($status);
        }
    }
}
