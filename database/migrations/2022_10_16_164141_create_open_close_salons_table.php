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
        Schema::create('open_close_salons', function (Blueprint $table) {
            $table->id();
            $table->integer('salon_id');
            $table->integer('course_id');
            $table->date('date');
            $table->integer('time');
            $table->integer('isOpen')->default(1);
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
        Schema::dropIfExists('open_close_salons');
    }
};
