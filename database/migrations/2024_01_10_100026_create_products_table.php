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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description')->nullable();
            $table->string('modelno')->nullable();
            $table->integer('brand_id');
            $table->integer('division_id');
            $table->string('product_type');
            $table->integer('manager_id');
            $table->double('selling_price');
            $table->double('margin_price');
            $table->integer('margin_percent');
            $table->integer('allowed_discount');
            $table->tinyInteger('freeze_discount')->default(0);
            $table->string('image_url')->nullable();
            $table->date('price_valid_from')->nullable();
            $table->date('price_valid_to')->nullable();
            $table->string('status');
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
