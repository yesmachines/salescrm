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
        Schema::table('users', function (Blueprint $table) {
            $table->char('device_type', 15)->nullable()->after('remember_token');
            //$table->text('device_id')->nullable()->after('device_type');
            //$table->boolean('os_subscribed')->default(0)->after('device_id');
            $table->string('os_sid')->nullable()->after('os_subscribed');
        });

        Schema::create('user_odevices', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('os_sid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
           // $table->dropColumn(['device_type', 'device_id', 'os_subscribed', 'os_sid']);
            $table->dropColumn(['device_type', 'os_sid']);
        });
        Schema::dropIfExists('user_odevices');
    }
};
