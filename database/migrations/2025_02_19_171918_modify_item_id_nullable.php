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
        Schema::table('cm_stock_items', function (Blueprint $table) {
             DB::statement("ALTER TABLE cm_stock_items MODIFY item_id BIGINT(20) UNSIGNED NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cm_stock_items', function (Blueprint $table) {
           DB::statement("ALTER TABLE cm_stock_items MODIFY item_id BIGINT(20) UNSIGNED NOT NULL");
        });
    }
};
