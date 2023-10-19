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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id');
            $table->foreignId('institute_branch_id');
            $table->string('invoice_no');
            $table->string('date');
            $table->float('amount',10,2);
            $table->foreignId('account_id');
            $table->string('pay_to');
            $table->mediumText('details')->nullable();
            $table->string('reference_no')->nullable();
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
        Schema::dropIfExists('expenses');
    }
};
