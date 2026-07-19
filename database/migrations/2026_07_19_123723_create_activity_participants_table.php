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
        Schema::create('activity_participants', function (Blueprint $table) {
            $table->id();
            // Activity
            $table->foreignId('activity_id')
                ->constrained('activities')
                ->cascadeOnDelete();

            // Participant
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();


            // When the user joined
            $table->timestamp('joined_at')->useCurrent();

            $table->timestamps();

            // Prevent duplicate joins
            $table->unique(['activity_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_participants');
    }
};
