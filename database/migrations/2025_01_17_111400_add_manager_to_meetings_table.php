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
            $table->unsignedBigInteger('manager_id')->nullable()->after('area_id');
            $table->foreign('manager_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('manager_id');
        });
    }
};
