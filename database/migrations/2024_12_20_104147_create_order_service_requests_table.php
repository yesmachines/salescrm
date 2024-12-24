<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_service_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->text('site_readiness')->nullable();
            $table->text('training_requirement')->nullable();
            $table->text('consumables')->nullable();
            $table->string('warranty_period')->nullable();
            $table->text('special_offers')->nullable();
            $table->text('documents_required')->nullable();
            $table->text('machine_objective')->nullable();
            $table->text('fat_test')->nullable();
            $table->text('fat_expectation')->nullable();
            $table->text('sat_objective')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_service_requests');
    }
};
