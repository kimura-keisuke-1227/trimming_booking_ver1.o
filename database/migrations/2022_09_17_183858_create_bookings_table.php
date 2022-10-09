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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('st_time');
            $table->integer('ed_time');
            $table->integer('ed_time_for_show');
            $table->foreignId('pet_id');
            $table->foreignId('course_id');
            $table->foreignId('salon_id');
            $table->integer('price');
            $table->integer('booking_status');
            $table->string('message') ->nullable();
            #$table->string('booking_number');
            #$table->integer('booking_express');
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
        Schema::dropIfExists('bookings');
    }
};
