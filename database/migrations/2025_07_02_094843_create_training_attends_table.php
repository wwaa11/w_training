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
        Schema::create('training_attends', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('date_id');
            $table->string('name');
            $table->dateTime('date');
            $table->boolean('user')->default(false);
            $table->boolean('admin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_attends');
    }
};
