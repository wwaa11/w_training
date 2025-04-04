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
        Schema::table('projects', function (Blueprint $table) {
            $table->dateTime('start_register_datetime')->nullable();
            $table->dateTime('last_register_datetime')->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('checkin')->default(false);
            $table->dateTime('checkin_datetime')->nullable();
            $table->boolean('hr_approve')->default(false);
            $table->dateTime('hr_approve_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('start_register_datetime');
            $table->dropColumn('last_register_datetime');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('checkin');
            $table->dropColumn('checkin_datetime');
            $table->dropColumn('hr_approve');
            $table->dropColumn('hr_approve_datetime');
        });
    }
};
