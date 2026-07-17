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
        Schema::table('users', function (Blueprint $table) {
           // Profile
            $table->string('avatar_url')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('avatar_url');

            // Personal Information
            $table->string('city', 100)->nullable()->after('date_of_birth');
            $table->string('country', 100)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn([
                'avatar_url',
                'bio',
                'city',
                'country',
            ]);
        });
    }
};
