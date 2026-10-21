<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilaya_id')->nullable()->constrained('wilayas')->nullOnDelete();
            $table->string('name_ar');
            $table->string('name_fr');
            $table->string('short_name')->nullable();
            $table->string('acronym', 10)->index(); // USMB, WAB, RCA, ESM, SKAF, etc.
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->string('primary_color', 20)->default('#008000');
            $table->string('secondary_color', 20)->default('#ffffff');
            $table->string('logo_path')->nullable();
            $table->foreignId('main_stadium_id')->nullable()->constrained('stadiums')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('club_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('age_category_id')->constrained('age_categories')->cascadeOnDelete();
            $table->string('head_coach_name')->nullable();
            $table->string('coach_license_type')->nullable(); // CAF A, CAF B, CAF C, FAF 1...
            $table->string('team_photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['club_id', 'season_id', 'age_category_id'], 'club_season_category_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_teams');
        Schema::dropIfExists('clubs');
    }
};
