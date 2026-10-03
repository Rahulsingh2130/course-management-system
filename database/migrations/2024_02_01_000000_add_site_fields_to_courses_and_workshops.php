<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('short_description')->nullable()->after('description');
            $table->unsignedTinyInteger('duration_days')->default(2)->after('price');
            $table->string('level')->default('Foundation')->after('duration_days');
            $table->decimal('rating', 2, 1)->default(4.7)->after('level');
            $table->boolean('is_featured')->default(false)->after('rating');
            $table->json('outcomes')->nullable()->after('is_featured');
            $table->json('syllabus')->nullable()->after('outcomes');
        });

        Schema::table('workshops', function (Blueprint $table) {
            $table->string('mode')->default('classroom')->after('batch_name');
            $table->string('location')->nullable()->after('mode');
            $table->decimal('price', 10, 2)->nullable()->after('seat_limit');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'duration_days', 'level', 'rating', 'is_featured', 'outcomes', 'syllabus']);
        });
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn(['mode', 'location', 'price']);
        });
    }
};
