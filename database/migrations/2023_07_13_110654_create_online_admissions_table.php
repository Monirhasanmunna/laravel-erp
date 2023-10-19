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
        Schema::create('online_admissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_id')->default(1);
            $table->foreign('institute_id')->references('id')->on('institutes');
            $table->integer('institute_branch_id')->nullable();
            $table->foreignId('session_id');
            $table->foreignId('class_id');
            $table->foreignId('division_id');
            $table->foreignId('district_id');
            $table->foreignId('upazila_id');
            $table->string('admission_no')->nullable();
            $table->string('image')->nullable();
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('date_of_birth');
            $table->string('mobile_number');
            $table->enum('religion',['Islam','Hindu','Christian']);
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->date('admission_date')->nullable();
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
        Schema::dropIfExists('online_admissions');
    }
};
