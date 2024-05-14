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
        Schema::create('quotation_installations', function (Blueprint $table) {
            $table->id();
            $table->Integer('quotation_id');
            $table->string('installation_by')->nullable();
            $table->integer('installation_periods')->nullable();
            $table->string('install_accommodation')->nullable();
            $table->string('install_tickets')->nullable();
            $table->string('install_transport')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_installations');
    }
};
