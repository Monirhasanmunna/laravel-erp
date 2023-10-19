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
        Schema::create('migrated_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('institute_branch_id');
            $table->string('name');
            $table->string('id_no');
            $table->string('roll_no');
            $table->string('photo')->nullable();
            $table->string('session');
            $table->string('class');
            $table->string('category')->nullable();
            $table->string('group')->nullable();
            $table->string('mobile_number');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('religion');
            $table->string('father_name');
            $table->string('mother_name');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
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
        Schema::dropIfExists('migrated_students');
    }
};
