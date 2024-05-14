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
        Schema::create('order_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('price_basis');
            $table->string('delivery_term')->nullable();
            $table->text('promised_delivery')->nullable();
            $table->date('targeted_delivery')->nullable();
            $table->string('installation_training')->nullable();
            $table->string('service_expert')->nullable();
            $table->string('estimated_installation')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('remarks')->nullable();
            $table->string('otp_code')->nullable();
            $table->datetime('otp_expire_date_time')->nullable();
            $table->string('short_link_code')->nullable();
            $table->text('otp_emails')->nullable();
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
        Schema::dropIfExists('order_clients');
    }
};
