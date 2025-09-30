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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('duration_type', ['days', 'weeks', 'months', 'years']);
            $table->integer('duration_value');
            $table->integer('max_visits')->nullable(); // null = unlimited
            $table->boolean('includes_trainer')->default(false);
            $table->boolean('includes_locker')->default(false);
            $table->boolean('includes_towel')->default(false);
            $table->json('features')->nullable(); // JSON array of features
            $table->string('image')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};