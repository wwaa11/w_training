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
        Schema::create('nurse_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('detail')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('register_start')->nullable();
            $table->dateTime('register_end')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('nurse_dates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nurse_project_id')->unsigned();
            $table->string('title');
            $table->text('detail')->nullable();
            $table->string('location')->nullable();
            $table->date('date');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('nurse_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nurse_date_id')->unsigned();
            $table->string('title');
            $table->text('detail')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('time_start')->nullable();
            $table->dateTime('time_end')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('nurse_lecturers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nurse_time_id')->unsigned();
            $table->string('user_id');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('nurse_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nurse_time_id')->unsigned();
            $table->string('user_id');
            $table->dateTime('user_sign')->nullable();
            $table->dateTime('admin_sign')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse_projects');
        Schema::dropIfExists('nurse_dates');
        Schema::dropIfExists('nurse_times');
        Schema::dropIfExists('nurse_lecturers');
        Schema::dropIfExists('nurse_transactions');
    }
};
