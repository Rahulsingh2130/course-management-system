<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('batch_name');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedInteger('seat_limit')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['starts_at', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
