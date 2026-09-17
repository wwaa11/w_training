<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nurse_score_report_departments', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->timestamps();

            $table->unique('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurse_score_report_departments');
    }
};
