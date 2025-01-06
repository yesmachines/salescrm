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
        Schema::create('buying_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->decimal('gross_price', 24, 2);
            $table->integer('discount');
            $table->decimal('discount_amount', 24, 2)->default(0);
            $table->decimal('buying_price', 24, 2)->default(0);
            $table->string('buying_currency', 10);
            $table->date('validity_from')->nullable();
            $table->date('validity_to')->nullable();
            $table->boolean('status')->default(1)->comment('1 is active, 0 is inactive');
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
        Schema::dropIfExists('buying_prices');
    }
};
