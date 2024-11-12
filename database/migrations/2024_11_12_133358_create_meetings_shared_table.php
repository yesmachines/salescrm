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
        Schema::create('meeting_shares', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('meeting_id')->nullable();
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->unsignedBigInteger('shared_by')->nullable();
            $table->foreign('shared_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('shared_to')->nullable();
            $table->foreign('shared_to')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('meeting_shares')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_representative')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('business_card')->nullable();
            $table->text('meeting_notes')->nullable();
            $table->smallInteger('status')
                    ->default(0)
                    ->comment('0-initiated 1-confirmed 2-shared, 3-rejected')
                    ->index();
            $table->timestamps();
        });

        Schema::create('meeting_shared_products', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('meetings_shared_id')->nullable();
            $table->foreign('meetings_shared_id')->references('id')->on('meeting_shares')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->smallInteger('quantity')
                    ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('meeting_shares');
        Schema::dropIfExists('meeting_shared_products');
    }
};
