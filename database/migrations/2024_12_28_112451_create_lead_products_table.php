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
            $table->unsignedBigInteger('lead_id');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade');
            $table->mediumText('notes')->nullable();
            $table->timestamps();

            $table->foreign('lead_id')
                    ->references('id')
                    ->on('leads')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('lead_products');
    }
};
