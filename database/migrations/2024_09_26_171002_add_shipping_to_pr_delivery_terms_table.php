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
        Schema::table('pr_delivery_terms', function (Blueprint $table) {            //
            $table->string('shipping_mode')->nullable()->after('delivery_term');
            $table->string('availability')->nullable()->after('shipping_mode');
            $table->string('warranty')->nullable()->after('availability');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_delivery_terms', function (Blueprint $table) {
            //
        });
    }
};
