<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Holiday;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Holiday::CONST_STR_TABLE_NAME_OF_HOLIDAYS, function (Blueprint $table) {
            $table->id();
            $table->foreignId(Holiday::CONST_STR_COLUMN_NAME_OF_SALON_ID);
            $table->date(Holiday::CONST_STR_COLUMN_NAME_OF_DATE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Holiday::CONST_STR_TABLE_NAME_OF_HOLIDAYS);
    }
};
