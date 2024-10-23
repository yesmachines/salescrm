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
        Schema::create('pr_delivery_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id');
            $table->integer('country_id');
            $table->string('supplier_email');
            $table->string('supplier_contact');
            $table->string('delivery_term')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('pr_id')
                ->references('id')
                ->on('purchase_requisitions')
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
        Schema::dropIfExists('pr_delivery_terms');
    }
};
