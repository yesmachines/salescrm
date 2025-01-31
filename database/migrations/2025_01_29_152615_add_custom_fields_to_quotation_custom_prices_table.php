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
          $table->double('final_buying_price')->nullable()->after('surcharges');
          $table->double('mobp')->nullable()->after('final_buying_price');
          $table->double('margin_amount_bp')->nullable()->after('mobp');
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
            //
        });
    }
};
