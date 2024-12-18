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
        Schema::table('buying_prices', function (Blueprint $table) {
            //
            $table->integer('added_by')->nullable()->default(0)->after('buying_currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buying_prices', function (Blueprint $table) {
            //
            $table->dropColumn('added_by');
        });
    }
};
