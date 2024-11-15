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
        Schema::create('expos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        \DB::table('expos')->insert([
            ['id' => 1, 'name' => 'Steel Fab', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Big 5 Global', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Ajban Expo', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'YES Open House', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('expo_id')->nullable()->after('status_id');
            $table->foreign('expo_id')->references('id')->on('expos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('expos');
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('expo_id');
        });
    }
};
