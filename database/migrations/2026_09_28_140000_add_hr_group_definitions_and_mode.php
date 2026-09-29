<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_projects', function (Blueprint $table) {
            $table->string('project_group_mode', 20)->default('manual')->after('project_group_assign');
        });

        Schema::create('hr_group_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('hr_projects')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('max_members')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['project_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_group_definitions');

        Schema::table('hr_projects', function (Blueprint $table) {
            $table->dropColumn('project_group_mode');
        });
    }
};
