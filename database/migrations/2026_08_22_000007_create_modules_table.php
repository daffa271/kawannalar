<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('subject');
            $table->string('grade');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('download_count')->default(0);
            $table->timestamps();

            $table->index(['subject', 'grade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
