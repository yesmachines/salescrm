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
        Schema::table('quotations', function (Blueprint $table) {
            //
            $table->enum('lead_type', ['internal', 'external'])->nullable();
            $table->string('quote_for')->nullable();
            $table->dateTime('reminder')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('lead_type');
            $table->dropColumn('quote_for');
            $table->dropColumn('reminder');
        });
    }
};
