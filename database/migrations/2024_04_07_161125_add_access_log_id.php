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
        Schema::table('open_close_salons', function (Blueprint $table) {
            $table->foreignId('access_log_id')->after('isOpen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('open_close_salons', function (Blueprint $table) {
            $table->dropColumn('access_log_id');
        });
    }
};
