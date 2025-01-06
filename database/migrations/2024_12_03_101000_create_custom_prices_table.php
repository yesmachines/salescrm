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
        Schema::create('custom_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
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
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
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
        Schema::dropIfExists('custom_prices');
    }
};
