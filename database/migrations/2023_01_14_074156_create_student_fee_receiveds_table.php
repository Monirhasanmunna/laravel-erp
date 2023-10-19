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
        Schema::create('student_fee_receiveds', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('date');
            $table->foreignId('student_id');
            $table->string('year');
            $table->string('month');
            $table->float('invoice_total',10,2)->default(0);
            $table->float('discount',10,2)->default(0);
            $table->float('advance',10,2)->default(0);
            $table->float('due_amount',10,2)->default(0);
            $table->float('total_payable',10,2)->default(0);
            $table->float('paid_amount',10,2)->default(0);
            $table->string('payment_type')->default('offline');
            $table->string('payment_method')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('student_fee_receiveds');
    }
};
