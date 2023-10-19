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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_id')->default(1);
            $table->integer('institute_branch_id')->nullable();
            $table->integer('source_id')->nullable();
            $table->string('source_type')->nullable();
            $table->integer('template_id');
            $table->string('application');
            $table->date('to_date');
            $table->date('from_date');
            $table->date('approved_date')->nullable();
            $table->bigInteger('total_day');
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('leave_applications');
    }
};
