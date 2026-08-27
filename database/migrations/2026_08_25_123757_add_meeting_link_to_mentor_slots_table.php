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
        Schema::table('mentor_slots', function (Blueprint $table) {
            $table->string('meeting_link')->nullable()->after('end_time');
            $table->integer('duration')->nullable()->after('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentor_slots', function (Blueprint $table) {
            $table->dropColumn(['meeting_link', 'duration']);
        });
    }
};
