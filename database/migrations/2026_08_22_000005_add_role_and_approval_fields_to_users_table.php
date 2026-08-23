<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('siswa');
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active');
            }

            if (! Schema::hasColumn('users', 'approved_by')) {
                $table->foreignId('approved_by')->nullable();
            }

            if (! Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = collect(['approved_at', 'approved_by', 'status', 'role'])
                ->filter(fn(string $column): bool => Schema::hasColumn('users', $column))
                ->values()
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
