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
    Schema::create('delivery_terms', function (Blueprint $table) {
      $table->id();
      $table->integer('quotation_id');
      $table->tinyInteger('isStock');
      $table->integer('working_weeks');
      $table->date('date_po');
      $table->double('advance_payment');
      $table->tinyInteger('status');
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
    Schema::dropIfExists('delivery_terms');
  }
};
