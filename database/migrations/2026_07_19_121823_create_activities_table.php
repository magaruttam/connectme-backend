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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // Creator of the activity
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Activity category
            $table->foreignId('category_id')
                ->constrained('activity_categories')
                ->restrictOnDelete();

            // Basic information
            $table->string('title');
            $table->text('description')->nullable();

            // Cover image
            $table->string('cover_image_url')->nullable();
            $table->string('cover_image_public_id')->nullable();

            // Location
            $table->string('location_name');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Date & Time
            $table->timestamp('start_at');
            $table->timestamp('end_at');

            // Participants
            $table->unsignedInteger('max_participants')->nullable();
            $table->unsignedInteger('current_participants')->default(0);

            // Pricing
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('NPR');

            // Status
            $table->enum('status', [
                'draft',
                'published',
                'cancelled',
                'completed',
            ])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
