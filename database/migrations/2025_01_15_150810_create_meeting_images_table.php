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
        Schema::create('meeting_docs', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('meeting_id')->nullable();
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->string('file_name')->nullable();
            $table->char('file_type', 20)->default('image');
            $table->timestamps();
        });
        Schema::create('meeting_shared_docs', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('meeting_shared_id')->nullable();
            $table->foreign('meeting_shared_id')->references('id')->on('meeting_shares')->onDelete('cascade');
            $table->uuid('meeting_doc_id')->nullable();
            $table->foreign('meeting_doc_id')->references('id')->on('meeting_docs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('meeting_docs');
        Schema::dropIfExists('meeting_shared_docs');
    }
};
