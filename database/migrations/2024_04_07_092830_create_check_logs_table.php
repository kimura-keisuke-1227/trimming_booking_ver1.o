<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\AccessLog;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(AccessLog::STR_TABLE_NAME_OF_CHECK_LOGS, function (Blueprint $table) {
            $table->id();
            $table->foreignId(AccessLog::STR_COLUMN_NAME_OF_USER_ID,AccessLog::INT_LENGTH_OF_USER_INFO);
            $table->string(AccessLog::STR_COLUMN_NAME_OF_METHOD,AccessLog::INT_LENGTH_OF_USER_METHOD);
            $table->string(AccessLog::STR_COLUMN_NAME_OF_USER_INFO,AccessLog::INT_LENGTH_OF_USER_INFO);
            $table->string(AccessLog::STR_COLUMN_NAME_OF_SUMMARY,AccessLog::INT_LENGTH_OF_SUMMARY);
            $table->text(AccessLog::STR_COLUMN_NAME_OF_DETAIL);
            $table->longText(AccessLog::STR_COLUMN_NAME_OF_REQUEST);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(AccessLog::STR_TABLE_NAME_OF_CHECK_LOGS);
    }
};
