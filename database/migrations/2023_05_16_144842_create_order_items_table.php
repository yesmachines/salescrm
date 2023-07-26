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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_delivery_id');
            $table->string('item')->nullable();
            $table->string('partno')->nullable();
            $table->integer('quantity')->default(0);
            $table->date('delivered')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('order_delivery_id')
                ->references('id')
                ->on('order_deliveries')
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
        Schema::dropIfExists('order_items');
    }
};
