<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('meetings', function (Blueprint $table) {            //
            $table->unsignedBigInteger('area_id')->nullable()->after('mqs');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });
    }
};
