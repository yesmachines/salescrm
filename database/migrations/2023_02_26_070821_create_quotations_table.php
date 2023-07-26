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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('reference_no')->unique();
            $table->integer('category_id')->default(0);
            $table->integer('supplier_id')->default(0);
            $table->decimal('total_amount', 24, 2)->default(0);
            $table->decimal('gross_margin', 24, 2)->nullable()->default(0);
            $table->date('submitted_date')->nullable();
            $table->date('closure_date')->nullable();
            $table->decimal('winning_probability', 5, 2)->default(0);
            $table->integer('parent_id')->default(0);
            $table->integer('root_parent_id')->default(0);
            $table->tinyInteger('status_id')->default(0);
            $table->text('remarks')->nullable();
            $table->integer('assigned_to')->default(0);
            $table->tinyInteger('is_active')->default(1); // 0 draft, 1 active, 2 disabled
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
};
