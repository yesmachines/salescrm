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
       Schema::create('custom_fields', function (Blueprint $table) {
           $table->id();
           $table->string('field_name');
           $table->integer('price_basis');
           $table->tinyInteger('sort_order')->nullable();
           $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('custom_fields');
    }
};
