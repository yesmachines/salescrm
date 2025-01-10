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
        Schema::table('quotation_custom_prices', function (Blueprint $table) {
            $table->dropForeign(['custom_price_id']);
            $table->dropColumn('custom_price_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_custom_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_price_id');
            $table->foreign('custom_price_id')->references('id')->on('custom_prices')->onDelete('cascade');
        });
    }
};
