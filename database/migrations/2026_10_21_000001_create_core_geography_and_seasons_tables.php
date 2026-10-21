<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayas', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // 09, 42, 26, 17, 44...
            $table->string('name_ar');
            $table->string('name_fr');
            $table->timestamps();
        });

        Schema::create('stadiums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilaya_id')->nullable()->constrained('wilayas')->nullOnDelete();
            $table->string('name');
            $table->string('city');
            $table->enum('surface_type', ['natural_grass', 'artificial_turf', 'clay', 'tartan'])->default('artificial_turf');
            $table->unsignedInteger('capacity')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->timestamps();
        });

        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. 2026/2027
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('age_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // U19, U17, U15, U13
            $table->string('name_ar');
            $table->string('name_fr');
            $table->unsignedSmallInteger('min_birth_year');
            $table->unsignedSmallInteger('max_birth_year');
            $table->unsignedSmallInteger('match_duration_minutes')->default(90);
            $table->unsignedTinyInteger('substitutions_allowed')->default(5);
            $table->unsignedTinyInteger('ball_size')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_categories');
        Schema::dropIfExists('seasons');
        Schema::dropIfExists('stadiums');
        Schema::dropIfExists('wilayas');
    }
};
