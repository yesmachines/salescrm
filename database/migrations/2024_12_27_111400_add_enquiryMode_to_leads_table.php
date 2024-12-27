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
        Schema::table('leads', function (Blueprint $table) {            //
            $table->string('enquiry_mode', 50)->nullable()->after('lead_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('meeting_shares', function (Blueprint $table) {
            $table->dropColumn('enquiry_mode');
        });
    }
};
