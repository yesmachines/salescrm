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
        Schema::create('pr_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id');
            $table->text('title');
            $table->string('currency')->default('aed');
            $table->decimal('considered', 24, 2)->default(0);
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
        Schema::dropIfExists('pr_charges');
    }
};
