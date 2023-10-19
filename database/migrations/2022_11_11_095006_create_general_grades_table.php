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
        Schema::create('general_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('institute_branch_id');
            $table->foreign('institute_id')->references('id')->on('institutes');
            $table->foreignId('ins_class_id')->constrained('ins_classes')->onDelete('cascade');
            $table->string('gpa_name');
            $table->string('range_from');
            $table->string('range_to');
            $table->string('point_from');
            $table->string('point_to');
            $table->string('gpa_point');
            $table->longText('comment')->nullable();
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
        Schema::dropIfExists('general_grades');
    }
};
