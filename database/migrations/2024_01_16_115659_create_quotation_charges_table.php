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
    Schema::create('quotation_charges', function (Blueprint $table) {
      $table->id();
      $table->Integer('quotation_id');
      $table->string('title');
      $table->string('short_code');
      $table->double('amount');
      $table->tinyInteger('sort_order')->nullable();
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
    Schema::dropIfExists('quotation_charges');
  }
};
