<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kartes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('pet_id');
            $table->text('karte_for_staff');
            $table->text('karte_for_owner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartes');
    }
};
