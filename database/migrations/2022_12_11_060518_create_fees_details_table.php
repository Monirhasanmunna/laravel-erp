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
        Schema::create('fees_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fees_id')->constrained('fees')->onDelete('cascade');
            $table->string('date');
            $table->tinyInteger('month');
            $table->string('year');
            $table->date('due_date');
            $table->decimal('total_amount', 10,2)->nullable();
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
        Schema::dropIfExists('fees_details');
    }
};
