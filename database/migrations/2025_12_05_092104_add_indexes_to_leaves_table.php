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
        Schema::table('leaves', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('leave_type_id');
            $table->index('status_final');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['leave_type_id']);
            $table->dropIndex(['status_final']);
            $table->dropIndex(['start_date']);
        });
    }
};
