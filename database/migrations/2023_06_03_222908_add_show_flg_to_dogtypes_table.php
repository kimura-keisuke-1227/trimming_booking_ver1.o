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
        Schema::table('dogtypes', function (Blueprint $table) {
            $table->boolean('flg_show')->after('order')
                ->default(true);  //カラム追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dogtypes', function (Blueprint $table) {
            $table->dropColumn('flg_show');  //カラムの削除
        });
    }
};
