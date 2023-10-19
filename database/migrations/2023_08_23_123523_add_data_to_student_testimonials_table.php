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
        Schema::table('student_testimonials', function (Blueprint $table) {
            $table->string('serial_no')->after('semister_id');
            $table->string('issue_date')->after('result');
            $table->string('board')->after('issue_date');
            $table->bigInteger('registration_no')->after('board');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_testimonials', function (Blueprint $table) {
            //
        });
    }
};
