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
        Schema::create('hr_attend_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attend_id');
            $table->foreign('attend_id')->references('id')->on('hr_attends')->onDelete('cascade');
            $table->text('attend_note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_attend_notes');
    }
};
