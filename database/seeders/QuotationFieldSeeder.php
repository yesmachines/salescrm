<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class QuotationFieldSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('quotation_fields')->where('is_default', 1)->delete();

    $charges = array(
      [
        'title' => 'SurCharges',

        'amount' => 10.00,
        'short_code' => 'Su',
        'is_default' => '1',
        'field_type' => 'quotation_charges',
        'status' => '1',
      ],
      [
        'title' => 'PO cancellation Charge',
        'amount' => 20.00,
        'short_code' => 'Po',
        'is_default' => '1',
        'field_type' => 'quotation_charges',
        'status' => '1',
      ],
      [
        'title' => 'Price Basis',
        'description' => 'Quoted in',
        'is_default' => '1',
        'field_type' => 'quotation_terms',
        'status' => '1',
      ],
      [
        'title' => 'Payment Term',
        'description' => '100% advance along with Purchase order.',
        'is_default' => '1',
        'field_type' => 'quotation_terms',

        'status' => '1',
      ],
      [
        'title' => 'Delivery',
        'description' => '',
        'is_default' => '1',
        'field_type' => 'quotation_terms',
        'status' => '1',
      ],
      [
        'title' => 'Installing and Training',
        'description' => '',
        'is_default' => '1',
        'field_type' => 'quotation_terms',
        'status' => '1',
      ],
      [
        'title' => 'Import Code',

        'description' => 'Sharjah import code is mandatory in case of generating a code an additional service fee of 150 AED is applicable (subject to receipt of required supporting documents)',
        'is_default' => '1',
        'field_type' => 'quotation_terms',
        'status' => '1',
      ],
      [
        'title' => 'Warranty Period',
        'description' => 'Warranty shall cover component replacement under normal operation for a period of 12 months from date of delivery. However, warranty is not valid in the damage/breakdown caused due to the operator negligence.',
        'is_default' => '1',
        'field_type' => 'quotation_terms',
        'status' => '1',
      ],
      [
        'title' => 'Purchase Order Cancellation',
        'description' => 'The buyer has the right to cancel the PO within 72 hours of PO placement without incurring any fees from the seller(Our Company) to the buyer. However, a cancellation fee equal to 20% of the order value will be charged for any cancellations made after 72 hours of order placement.',
        'is_default' => '1',
        'field_type' => 'quotation_terms',
        'status' => '1',
      ],
      [
        'title' => 'Offer Validity',
        'description' => '30 days',
        'is_default' => '1',
        'field_type' => 'quotation_terms',
        'status' => '1',
      ],

      [
        'title' => 'advance along with po',
        'description' => '30%',
        'is_default' => '1',
        'field_type' => 'payment_terms',
        'status' => '1',
      ],
      [
        'title' => 'upon delivery',
        'description' => '30%',
        'is_default' => '1',
        'field_type' => 'payment_terms',
        'status' => '1',
      ],

    );
    foreach ($charges as $charge) {
      DB::table('quotation_fields')->insert($charge);
    }
  }
}
