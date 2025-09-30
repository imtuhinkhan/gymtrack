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
        Schema::create('pwa_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Gym Management');
            $table->string('short_name')->default('GymApp');
            $table->text('description')->nullable();
            $table->string('theme_color', 7)->default('#3B82F6');
            $table->string('background_color', 7)->default('#FFFFFF');
            $table->string('display')->default('standalone');
            $table->string('orientation')->default('portrait');
            $table->string('start_url')->default('/');
            $table->string('scope')->default('/');
            $table->string('icon_192')->nullable();
            $table->string('icon_512')->nullable();
            $table->string('splash_icon')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pwa_settings');
    }
};
