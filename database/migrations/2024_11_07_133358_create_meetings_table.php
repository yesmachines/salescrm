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
        Schema::create('meetings', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_representative')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->string('timezone')->nullable();
            $table->text('scheduled_notes')->nullable();
            $table->string('business_card')->nullable();
            $table->text('meeting_notes')->nullable();
            $table->integer('mqs')->nullable();
            $table->smallInteger('status')
                    ->default(0)
                    ->comment('0-initiated 1-done(notes saved) 2-shared')
                    ->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('meetings');
    }
};
