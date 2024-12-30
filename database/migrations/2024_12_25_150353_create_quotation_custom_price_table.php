<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('quotation_custom_price', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('custom_price_id');
      $table->unsignedBigInteger('quotation_id');
      $table->unsignedBigInteger('product_id');
      $table->integer('company_id')->nullable();

      $table->double('selling_price');
      $table->double('margin_price');
      $table->double('margin_percent');
      $table->string('selling_currency');

      $table->double('gross_price');
      $table->double('discount');
      $table->double('discount_amount');
      $table->double('buying_price');
      $table->string('buying_currency');

      $table->double('packing')->nullable();
      $table->double('road_transport_to_port')->nullable();
      $table->double('freight')->nullable();
      $table->double('insurance')->nullable();
      $table->double('clearing')->nullable();
      $table->double('boe')->nullable();
      $table->double('handling_and_local_transport')->nullable();
      $table->double('customs')->nullable();
      $table->double('delivery_charge')->nullable();
      $table->double('mofaic')->nullable();
      $table->double('surcharges')->nullable();

      $table->integer('updated_by')->nullable();
      $table->timestamps();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('quotation_custom_price');
  }
};
