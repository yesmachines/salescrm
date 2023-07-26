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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('yespo_no')->nullable();
            $table->string('po_number');
            $table->date('po_date')->nullable();
            $table->date('po_received')->nullable();
            $table->string('otp_code')->nullable();
            $table->datetime('otp_expire_date_time')->nullable();
            $table->string('short_link_code')->nullable();
            $table->enum('status', ['open', 'partial', 'closed']);
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');


            $table->index(['po_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
