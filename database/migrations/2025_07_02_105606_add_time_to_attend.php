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
        Schema::table('training_attends', function (Blueprint $table) {
            $table->dateTime('user_date')->nullable();
            $table->dateTime('admin_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_attends', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('user_date');
            $table->dropColumn('admin_date');
        });
    }
};
