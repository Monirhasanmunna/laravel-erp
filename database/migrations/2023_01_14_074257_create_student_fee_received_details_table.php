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
        Schema::create('student_fee_received_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_fee_received_id')->constrained('student_fee_receiveds')->onDelete('cascade');
            $table->foreignId('fees_type_id')->constrained('fees_types')->onDelete('cascade');
            $table->string('source_id')->nullable();
            $table->string('source_type')->nullable();
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('student_fee_received_details');
    }
};
