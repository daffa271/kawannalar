<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Alasan penolakan untuk pendaftaran mentor, modul Ruang Nalar, dan booking bimbingan.
 * Kolom nullable → data lama tetap valid tanpa perubahan.
 */
return new class extends Migration
{
    private const TABLES = ['users', 'modules', 'mentoring_bookings'];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->text('rejection_reason')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('rejection_reason');
            });
        }
    }
};
