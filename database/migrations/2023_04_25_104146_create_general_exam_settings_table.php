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
        Schema::create('general_exam_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_id')->default(1);
            $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('ins_classes')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('class_test')->default(0)->nullable();
            $table->string('calss_test_per')->nullable();
            $table->string('attn_show')->default(0)->nullable();
            $table->string('calss_pos_gpa')->nullable();
            $table->string('calss_pos_total')->nullable();
            $table->string('calss_pos_atten')->nullable();
            $table->string('sequentially')->default(0)->nullable();
            $table->string('non_sequentially')->default(0)->nullable();
            $table->foreign('institute_id')->references('id')->on('institutes');
            $table->unsignedBigInteger('institute_branch_id');
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
        Schema::dropIfExists('general_exam_settings');
    }
};
