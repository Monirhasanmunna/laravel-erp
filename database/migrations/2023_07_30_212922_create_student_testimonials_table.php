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
        Schema::create('student_testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_id')->default(1);
            $table->integer('institute_branch_id')->nullable();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('session_id');
            $table->foreignId('class_id');
            $table->foreignId('division_id');
            $table->foreignId('district_id');
            $table->foreignId('upazila_id');
            $table->foreignId('semister_id');
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('roll');
            $table->string('result')->nullable();
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
        Schema::dropIfExists('student_testimonials');
    }
};
