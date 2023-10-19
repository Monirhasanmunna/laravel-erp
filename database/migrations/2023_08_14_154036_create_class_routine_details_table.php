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
        Schema::create('class_routine_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_routine_id')->constrained('class_routines')->onDelete('cascade');
            $table->unsignedBigInteger('period_time_details_id')->constrained('routine_period_time_setting_details')->onDelete('cascade');
            $table->unsignedBigInteger('class_subject_id');
            $table->unsignedBigInteger('teacher_id');
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
        Schema::dropIfExists('class_routine_details');
    }
};
