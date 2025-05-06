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
        Schema::table('nurse_lecturers', function (Blueprint $table) {
            $table->renameColumn('nurse_time_id', 'nurse_date_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nurse_lecturers', function (Blueprint $table) {
            $table->renameColumn('nurse_date_id', 'nurse_time_id');
        });
    }
};
