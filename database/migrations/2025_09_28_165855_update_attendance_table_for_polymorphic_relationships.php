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
        Schema::table('attendance', function (Blueprint $table) {
            // Check if columns already exist before adding them
            if (!Schema::hasColumn('attendance', 'attendable_type')) {
                $table->string('attendable_type')->nullable()->after('id');
            }
            if (!Schema::hasColumn('attendance', 'attendable_id')) {
                $table->unsignedBigInteger('attendable_id')->nullable()->after('attendable_type');
            }
            if (!Schema::hasColumn('attendance', 'status')) {
                $table->enum('status', ['present', 'absent', 'leave'])->default('present')->after('attendable_id');
            }
            
            // Make existing fields nullable since we're transitioning
            $table->unsignedBigInteger('customer_id')->nullable()->change();
            $table->unsignedBigInteger('trainer_id')->nullable()->change();
            
            // Add index for polymorphic relationship if it doesn't exist
            if (!Schema::hasIndex('attendance', ['attendable_type', 'attendable_id'])) {
                $table->index(['attendable_type', 'attendable_id']);
            }
        });
        
        // Drop foreign key constraints first, then unique constraint
        Schema::table('attendance', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['trainer_id']);
        });
        
        // Now drop the unique constraint
        Schema::table('attendance', function (Blueprint $table) {
            try {
                $table->dropUnique(['customer_id', 'date']);
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            
            if (!Schema::hasIndex('attendance', 'attendance_unique')) {
                $table->unique(['attendable_type', 'attendable_id', 'date'], 'attendance_unique');
            }
        });
        
        // Re-add foreign key constraints
        Schema::table('attendance', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['trainer_id']);
        });
        
        Schema::table('attendance', function (Blueprint $table) {
            // Remove polymorphic fields
            $table->dropIndex(['attendable_type', 'attendable_id']);
            $table->dropUnique('attendance_unique');
            $table->dropColumn(['attendable_type', 'attendable_id', 'status']);
            
            // Restore original unique constraint
            $table->unique(['customer_id', 'date']);
            
            // Make fields non-nullable again
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
            $table->unsignedBigInteger('trainer_id')->nullable(false)->change();
        });
        
        // Re-add foreign key constraints
        Schema::table('attendance', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('trainers')->onDelete('set null');
        });
    }
};