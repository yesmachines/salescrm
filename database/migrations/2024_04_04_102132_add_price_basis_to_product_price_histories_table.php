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
        Schema::table('product_price_histories', function (Blueprint $table) {
           $table->string('price_basis')->after('margin_price');
           $table->integer('margin_percent')->after('price_basis');
           $table->string('currency')->after('margin_percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_price_histories', function (Blueprint $table) {
            //
        });
    }
};
