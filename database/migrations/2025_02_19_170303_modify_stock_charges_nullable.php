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
        Schema::table('stock_charges', function (Blueprint $table) {
              DB::statement("ALTER TABLE cm_stock_charges MODIFY considered DECIMAL(24,2) NULL DEFAULT 0");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_charges', function (Blueprint $table) {
          DB::statement("ALTER TABLE cm_stock_charges MODIFY considered DECIMAL(24,2) NOT NULL DEFAULT 0");
        });
    }
};
