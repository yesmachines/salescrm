<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentTerm; // Import the PaymentTerm model
use Illuminate\Support\Facades\DB;

class PaymentTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        PaymentTerm::truncate();


        DB::table('payment_terms')->insert([
            [
                'title' => 'Exworks',
                'short_code' => 'Exworks',
                'isdefault' => 1,
                'status' => 1,
                'parent_id' => 0,
                'extra_options' => 0
            ],
            [
                'title' => 'FOB',
                'short_code' => 'FOB',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 0,
                'extra_options' => 0
            ],
            [
                'title' => 'CIF',
                'short_code' => 'CIF',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 0,
                'extra_options' => 0
            ],
            [
                'title' => 'DDU',
                'short_code' => 'DDU',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 0,
                'extra_options' => 0
            ],
            [
                'title' => 'DDP',
                'short_code' => 'DDP',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 0,
                'extra_options' => 0
            ],
            [
                'title' => 'Manufacturer’s Factory',
                'short_code' => 'manufacturer_factory',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 1,
                'extra_options' => 1
            ],
            [
                'title' => 'Our Warehouse',
                'short_code' => 'our_warehouse',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 1,
                'extra_options' => 0
            ],
            [
                'title' => 'Manufacturer’s Nearest Port',
                'short_code' => 'manufacturers_nearest',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 2,
                'extra_options' => 1
            ],
            [
                'title' => 'Nearest Port of Buyer’s Company Location',
                'short_code' => 'nearest_port',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 3,
                'extra_options' => 0
            ],
            [
                'title' => 'Buyer’s Factory',
                'short_code' => 'buyers_factory',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 4,
                'extra_options' => 0
            ],
            [
                'title' => 'Buyer’s Factory',
                'short_code' => 'buyers_factory',
                'isdefault' => 0,
                'status' => 1,
                'parent_id' => 5,
                'extra_options' => 1
            ],
        ]);
    }
}
