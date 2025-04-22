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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('seat')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('item_max_available')->default(1);
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('item_id');
            $table->text('seats')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('seat');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('item_max_available');
        });

        Schema::dropIfExists('seats');
    }
};
