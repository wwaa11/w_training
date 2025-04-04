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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->text('project_detail')->nullable();
            $table->boolean('project_active')->default(true);
            $table->boolean('project_delete')->default(false);
            $table->timestamps();
        });
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->integer('slot_index')->nullable();
            $table->date('slot_date');
            $table->string('slot_name');
            $table->boolean('slot_active')->default(true);
            $table->timestamps();
        });
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('slot_id');
            $table->string('item_name');
            $table->integer('item_index')->nullable();
            $table->integer('item_available')->default(1);
            $table->text('item_detail')->nullable();
            $table->boolean('item_note_1_active')->default(false);
            $table->text('item_note_1_title')->nullable();
            $table->text('item_note_1_value')->nullable();
            $table->boolean('item_note_2_active')->default(false);
            $table->text('item_note_2_title')->nullable();
            $table->text('item_note_2_value')->nullable();
            $table->boolean('item_note_3_active')->default(false);
            $table->text('item_note_3_title')->nullable();
            $table->text('item_note_3_value')->nullable();
            $table->boolean('item_active')->default(true);
            $table->timestamps();
        });
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('item_id');
            $table->string('user');
            $table->boolean('transaction_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('slots');
        Schema::dropIfExists('items');
        Schema::dropIfExists('transactions');
    }
};
