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
            $table->unsignedBigInteger('quotation_id');
            $table->string('os_number'); // OS NO
            $table->date('os_date');
            $table->string('po_number')->nullable();
            $table->date('po_date')->nullable();
            $table->date('po_received')->nullable();
            $table->string('currency')->default('aed');
            $table->decimal('selling_price', 24, 2)->default(0);
            $table->decimal('buying_price', 24, 2)->default(0);
            $table->decimal('projected_margin', 24, 2)->default(0);
            $table->decimal('actual_margin', 24, 2)->nullable()->default(0);
            $table->string('material_status')->nullable(); // in stock, to purchase, partial stock /partial purchase
            $table->text('material_details')->nullable();

            $table->integer('created_by')->default(0);
            $table->enum('status', ['open', 'partial', 'closed']);
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations');


            $table->index(['os_number', 'po_number']);
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
