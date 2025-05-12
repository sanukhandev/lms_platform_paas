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
        // Add indexes for courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->index('created_at', 'idx_courses_created_at');
            $table->index('category_id', 'idx_courses_category');
        });

        // Add indexes for class_sessions table
        Schema::table('class_sessions', function (Blueprint $table) {
            $table->index(['batch_id', 'session_date'], 'idx_sessions_batch_date');
            $table->index('class_status', 'idx_sessions_status');
        });

        // Add indexes for batches table
        Schema::table('batches', function (Blueprint $table) {
            $table->index(['course_id', 'start_date'], 'idx_batches_course_date');
            $table->index(['start_date', 'end_date'], 'idx_batches_date_range');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('idx_courses_created_at');
            $table->dropIndex('idx_courses_category');
        });

        // Drop indexes from class_sessions table
        Schema::table('class_sessions', function (Blueprint $table) {
            $table->dropIndex('idx_sessions_batch_date');
            $table->dropIndex('idx_sessions_status');
        });

        // Drop indexes from batches table
        Schema::table('batches', function (Blueprint $table) {
            $table->dropIndex('idx_batches_course_date');
            $table->dropIndex('idx_batches_date_range');
        });
    }
};
