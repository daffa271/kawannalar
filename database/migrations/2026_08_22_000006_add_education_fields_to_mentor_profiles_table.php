<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mentor_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('mentor_profiles', 'high_school')) {
                $table->string('high_school')->nullable();
            }

            if (! Schema::hasColumn('mentor_profiles', 'graduation_year')) {
                $table->unsignedSmallInteger('graduation_year')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('mentor_profiles', function (Blueprint $table) {
            $columns = collect(['graduation_year', 'high_school'])
                ->filter(fn(string $column): bool => Schema::hasColumn('mentor_profiles', $column))
                ->values()
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
