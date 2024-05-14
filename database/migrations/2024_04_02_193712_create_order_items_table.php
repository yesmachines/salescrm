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
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->text('item_name');
            $table->integer('quantity')->default(0);
            $table->string('partno')->nullable();
            $table->string('yes_number')->nullable(); // if stock, yesno add. New item,
            $table->string('currency')->default('aed');
            $table->decimal('total_amount', 24, 2)->default(0);
            $table->date('expected_delivery')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(0); // delivered, not delivered
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

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
        Schema::dropIfExists('order_items');
    }
};
