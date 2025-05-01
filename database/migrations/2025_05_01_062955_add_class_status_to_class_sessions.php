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
            // add enum values for class session status and createc column

            $table->enum('class_status', ['scheduled', 'completed', 'cancelled'])
                ->default('scheduled')
                ->after('date')
                ->comment('Status of the class session: scheduled, completed, or cancelled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            //
            // drop the class_status column
            $table->dropColumn('class_status');
        });
    }
};
