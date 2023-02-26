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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('expert_id')->references('id')->on('experts')->onDelete('cascade');
            $table->morphs('appointmentable');
            $table->foreignId('time_id')->references('id')->on('times')->onDelete('cascade');
            $table->foreignId('consultation_id')->references('id')->on('consultations')->onDelete('cascade');
            $table->time('start');
            $table->time('end');
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
        Schema::dropIfExists('appointments');
    }
};
