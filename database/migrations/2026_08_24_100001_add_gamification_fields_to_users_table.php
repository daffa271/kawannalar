<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('school_name')->nullable()->after('name');
            $table->unsignedBigInteger('xp_points')->default(0)->after('school_name');
            $table->unsignedInteger('streak_days')->default(0)->after('xp_points');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['school_name', 'xp_points', 'streak_days']);
        });
    }
};
