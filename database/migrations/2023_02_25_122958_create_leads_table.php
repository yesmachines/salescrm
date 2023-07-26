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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->enum('lead_type', ['internal', 'external']);
            $table->date('enquiry_date')->nullable();
            $table->text('details')->nullable();
            $table->integer('assigned_to')->default(0);
            $table->date('assigned_on')->nullable();
            $table->date('respond_on')->nullable();
            $table->tinyInteger('status_id')->default(0);
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('leads');
    }
};
