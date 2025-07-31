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
        Schema::create('hr_projects', function (Blueprint $table) {
            $table->id();
            $table->enum('project_type', ['single', 'multiple', 'attendance'])->default('single');
            $table->string('project_name');
            $table->string('project_detail')->nullable();
            $table->boolean('project_seat_assign')->default(false);
            $table->dateTime('project_start_register');
            $table->dateTime('project_end_register');
            $table->boolean('project_register_today')->default(true);
            $table->boolean('project_active')->default(true);
            $table->boolean('project_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('hr_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('hr_projects');
            $table->string('date_title');
            $table->string('date_detail')->nullable();
            $table->string('date_location')->nullable();
            $table->dateTime('date_datetime');
            $table->boolean('date_active')->default(true);
            $table->boolean('date_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('hr_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('date_id')->constrained('hr_dates');
            $table->string('time_title');
            $table->string('time_detail')->nullable();
            $table->time('time_start');
            $table->time('time_end');
            $table->boolean('time_limit')->default(false);
            $table->integer('time_max')->default(0);
            $table->boolean('time_active')->default(true);
            $table->boolean('time_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('hr_attends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('hr_projects');
            $table->foreignId('date_id')->constrained('hr_dates');
            $table->foreignId('time_id')->constrained('hr_times');
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('attend_datetime')->nullable();
            $table->dateTime('approve_datetime')->nullable();
            $table->boolean('attend_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('hr_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('hr_projects');
            $table->string('link_name');
            $table->string('link_url');
            $table->boolean('link_limit')->default(false);
            $table->dateTime('link_time_start')->nullable();
            $table->dateTime('link_time_end')->nullable();
            $table->boolean('link_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('hr_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_id')->constrained('hr_times');
            $table->foreignId('user_id')->constrained('users');
            $table->string('department');
            $table->integer('seat_number');
            $table->boolean('seat_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('hr_result_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('hr_projects');
            $table->string('result_1_name')->nullable();
            $table->string('result_2_name')->nullable();
            $table->string('result_3_name')->nullable();
            $table->string('result_4_name')->nullable();
            $table->string('result_5_name')->nullable();
            $table->string('result_6_name')->nullable();
            $table->string('result_7_name')->nullable();
            $table->string('result_8_name')->nullable();
            $table->string('result_9_name')->nullable();
            $table->string('result_10_name')->nullable();
            $table->timestamps();
        });

        Schema::create('hr_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('hr_projects');
            $table->foreignId('attend_id')->constrained('hr_attends');
            $table->foreignId('user_id')->constrained('users');
            $table->string('result_1')->nullable();
            $table->string('result_2')->nullable();
            $table->string('result_3')->nullable();
            $table->string('result_4')->nullable();
            $table->string('result_5')->nullable();
            $table->string('result_6')->nullable();
            $table->string('result_7')->nullable();
            $table->string('result_8')->nullable();
            $table->string('result_9')->nullable();
            $table->string('result_10')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_results');
        Schema::dropIfExists('hr_result_headers');
        Schema::dropIfExists('hr_seats');
        Schema::dropIfExists('hr_links');
        Schema::dropIfExists('hr_attends');
        Schema::dropIfExists('hr_times');
        Schema::dropIfExists('hr_dates');
        Schema::dropIfExists('hr_projects');
    }
};
