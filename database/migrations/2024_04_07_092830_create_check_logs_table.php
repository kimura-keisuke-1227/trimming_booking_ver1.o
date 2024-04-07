<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\CheckLog;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(CheckLog::STR_TABLE_NAME_OF_CHECK_LOGS, function (Blueprint $table) {
            $table->id();
            $table->string(CheckLog::STR_COLUMN_NAME_OF_USER_INFO);
            $table->string(CheckLog::STR_COLUMN_NAME_OF_SUMMARY);
            $table->string(CheckLog::STR_COLUMN_NAME_OF_DETAIL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(CheckLog::STR_TABLE_NAME_OF_CHECK_LOGS);
    }
};
