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
        Schema::table('seller_sms_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('institute_branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_sms_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('institute_branch_id');
        });
    }
};
