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
       Schema::create('pr_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('partno')->nullable();
            $table->text('item_description');
            $table->decimal('unit_price', 24, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('total_amount', 24, 2)->default(0);
            $table->string('currency');
            $table->string('yes_number')->nullable(); // if stock, yesno add. New item,
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('pr_id')
                ->references('id')
                ->on('purchase_requisitions')
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
        Schema::dropIfExists('pr_items');
    }
};
