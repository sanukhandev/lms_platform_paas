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
        Schema::table('class_sessions', function (Blueprint $table) {
            // add one more vaue in class_status
            // drop the column first
            $table->dropColumn('class_status');
            $table->enum('class_status', ['not_started', 'in_progress', 'completed', 'cancelled'])->default('not_started')->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress', function (Blueprint $table) {
            // remove the enum value
            $table->dropColumn('class_status');
        });
    }
};
