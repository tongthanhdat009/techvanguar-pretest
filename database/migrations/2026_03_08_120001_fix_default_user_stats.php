<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix existing NULL values to default 0
        DB::statement("UPDATE users SET experience_points = 0 WHERE experience_points IS NULL");
        DB::statement("UPDATE users SET daily_streak = 0 WHERE daily_streak IS NULL");

        // Now make the columns NOT NULL with default
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('experience_points')->default(0)->change();
            $table->unsignedInteger('daily_streak')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('experience_points')->nullable()->change();
            $table->unsignedInteger('daily_streak')->nullable()->change();
        });
    }
};
