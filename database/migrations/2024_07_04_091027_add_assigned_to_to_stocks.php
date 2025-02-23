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
    Schema::table('stocks', function (Blueprint $table) {
      $table->integer('created_by')->nullable()->after('buying_price');
      $table->integer('assigned_to')->default(0)->after('created_by');
      $table->integer('status')->nullable()->after('assigned_to');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('stocks', function (Blueprint $table) {
      //
    });
  }
};
