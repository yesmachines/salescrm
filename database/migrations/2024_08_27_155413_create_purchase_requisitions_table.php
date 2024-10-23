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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('os_id');
            $table->string('pr_for')->nullable()->default('yesmachinery');
            $table->string('pr_number'); // PR NO
            $table->date('pr_date');
            $table->enum('pr_type', ['stock', 'client']);
            $table->integer('company_id')->nullable(); // for client pr
            $table->decimal('total_price', 24, 2)->default(0);
            $table->string('currency');
            $table->integer('created_by')->default(0);
            $table->enum('status', ['pending', 'ordered', 'onhold']);
            $table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers');

            $table->foreign('os_id')
                ->references('id')
                ->on('orders');

            $table->index(['pr_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
