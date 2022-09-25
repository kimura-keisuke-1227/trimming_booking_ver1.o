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
        Schema::create('temp_capacities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salon_id');
            $table->date('st_date');
            $table->integer('st_time');
            $table->date('ed_date');
            $table->integer('ed_time');
            $table->integer('capacity');
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
        Schema::dropIfExists('temp_capacities');
    }
};
