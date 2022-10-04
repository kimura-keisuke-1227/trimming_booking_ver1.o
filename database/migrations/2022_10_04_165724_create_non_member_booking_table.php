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
        Schema::create('non_member_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->string('last_name');
            $table->string('last_name_kana');
            $table->string('first_name');
            $table->string('first_name_kana');
            $table->string('email');
            $table->string('phone');
            $table->foreignId('dogtype_id');
            $table->string('name');
            $table->date('date');
            $table->integer('st_time');
            $table->integer('ed_time');
            $table->foreignId('course_id');
            $table->foreignId('salon_id');
            $table->integer('price');
            $table->integer('booking_status');
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
        Schema::dropIfExists('non_member_booking');
    }
};
