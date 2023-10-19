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
        Schema::create('sub_marks_dist_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_marks_dist_id')->constrained('sub_marks_dists')->onDelete('cascade');
            $table->foreignId('sub_marks_dist_type_id')->constrained('sub_marks_dist_types')->onDelete('cascade');
            $table->string('mark');
            $table->string('pass_mark');
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
        Schema::dropIfExists('sub_marks_dist_details');
    }
};
