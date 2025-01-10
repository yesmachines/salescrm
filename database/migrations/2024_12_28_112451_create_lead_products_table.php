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
        Schema::create('lead_products', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade');
            $table->mediumText('notes')->nullable();
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('assistant_id')->nullable();
            $table->foreign('assistant_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

            $table->timestamps();
        });
        Schema::create('lead_shares', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('shared_by')->nullable();
            $table->foreign('shared_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('shared_to')->nullable();
            $table->foreign('shared_to')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('lead_products');
        Schema::dropIfExists('lead_shares');
    }
};
