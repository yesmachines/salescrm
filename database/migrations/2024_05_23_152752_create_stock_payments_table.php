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
        Schema::create('stock_payments', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('stock_id');
          $table->text('payment_term')->nullable();
          $table->date('expected_date')->nullable();
          $table->text('remarks')->nullable();
          $table->tinyInteger('status')->default(0); 
          $table->timestamps();

          $table->foreign('stock_id')
              ->references('id')
              ->on('stocks')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_payments');
    }
};
