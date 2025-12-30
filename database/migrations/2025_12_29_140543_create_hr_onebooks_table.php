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
        Schema::create('hr_onebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('hr_projects');
            $table->unsignedBigInteger('skip_hours')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_onebooks');
    }
};
