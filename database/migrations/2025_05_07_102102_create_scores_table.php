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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id')->unsigned();
            $table->string('user_id');
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
            $table->string('result_11')->nullable();
            $table->string('result_12')->nullable();
            $table->string('result_13')->nullable();
            $table->string('result_14')->nullable();
            $table->string('result_15')->nullable();
            $table->string('result_16')->nullable();
            $table->string('result_17')->nullable();
            $table->string('result_18')->nullable();
            $table->string('result_19')->nullable();
            $table->string('result_20')->nullable();
            $table->timestamps();
        });
        Schema::create('score_headers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned();
            $table->string('title_1')->nullable();
            $table->string('title_2')->nullable();
            $table->string('title_3')->nullable();
            $table->string('title_4')->nullable();
            $table->string('title_5')->nullable();
            $table->string('title_6')->nullable();
            $table->string('title_7')->nullable();
            $table->string('title_8')->nullable();
            $table->string('title_9')->nullable();
            $table->string('title_10')->nullable();
            $table->string('title_11')->nullable();
            $table->string('title_12')->nullable();
            $table->string('title_13')->nullable();
            $table->string('title_14')->nullable();
            $table->string('title_15')->nullable();
            $table->string('title_16')->nullable();
            $table->string('title_17')->nullable();
            $table->string('title_18')->nullable();
            $table->string('title_19')->nullable();
            $table->string('title_20')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
        Schema::dropIfExists('score_headers');
    }
};
