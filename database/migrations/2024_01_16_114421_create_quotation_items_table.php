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
    Schema::create('quotation_items', function (Blueprint $table) {
      $table->id();
      $table->Integer('quotation_id');
      $table->Integer('item_id');
      $table->text('description');
      $table->double('unit_price');
      $table->integer('quantity');
      $table->double('subtotal');
      $table->double('discount');
      $table->double('total_after_discount');
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
    Schema::dropIfExists('quotation_items');
  }
};
