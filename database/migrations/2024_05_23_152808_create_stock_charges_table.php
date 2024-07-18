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
        Schema::create('stock_charges', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('stock_id');
          $table->text('title')->nullable();
          $table->string('currency')->default('aed');
          $table->decimal('considered', 24, 2)->default(0);
          $table->decimal('actual', 24, 2)->nullable()->default(0);
          $table->text('remarks')->nullable();
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
        Schema::dropIfExists('stock_charges');
    }
};
