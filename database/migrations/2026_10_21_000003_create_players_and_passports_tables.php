<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // For public verification & QR
            $table->string('passport_number', 30)->unique(); // e.g. MASARI-09-00123
            $table->string('first_name_ar');
            $table->string('last_name_ar');
            $table->string('first_name_fr');
            $table->string('last_name_fr');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->foreignId('wilaya_id')->nullable()->constrained('wilayas')->nullOnDelete();
            $table->string('nationality')->default('Algerian');
            $table->string('national_id_number', 30)->nullable()->index();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->enum('dominant_foot', ['right', 'left', 'both'])->default('right');
            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->unsignedSmallInteger('weight_kg')->nullable();
            $table->enum('primary_position', [
                'GK',  // Goalkeeper
                'CB',  // Center Back
                'LB',  // Left Back
                'RB',  // Right Back
                'DM',  // Defensive Midfielder
                'CM',  // Central Midfielder
                'AM',  // Attacking Midfielder
                'LW',  // Left Winger
                'RW',  // Right Winger
                'ST'   // Striker
            ])->default('CM');
            $table->string('secondary_position', 10)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('qr_token', 64)->unique();
            $table->enum('status', ['active', 'injured', 'suspended', 'transferred', 'archived'])->default('active');
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('player_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('age_category_id')->constrained('age_categories')->cascadeOnDelete();
            $table->string('license_number', 50)->unique();
            $table->string('medical_certificate_path')->nullable();
            $table->date('medical_check_date')->nullable();
            $table->enum('medical_status', ['fit', 'pending', 'unfit'])->default('fit');
            $table->string('insurance_policy_number')->nullable();
            $table->enum('approval_status', ['draft', 'submitted', 'approved', 'rejected'])->default('approved');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['player_id', 'season_id'], 'player_season_license_unique');
        });

        Schema::create('career_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->foreignId('age_category_id')->constrained('age_categories')->cascadeOnDelete();
            $table->unsignedSmallInteger('matches_count')->default(0);
            $table->unsignedSmallInteger('goals_count')->default(0);
            $table->unsignedSmallInteger('assists_count')->default(0);
            $table->unsignedInteger('minutes_played')->default(0);
            $table->unsignedSmallInteger('yellow_cards')->default(0);
            $table->unsignedSmallInteger('red_cards')->default(0);
            $table->string('achievement_title')->nullable(); // e.g. "بطل الرابطة الجهوية U15"
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_timelines');
        Schema::dropIfExists('player_licenses');
        Schema::dropIfExists('players');
    }
};
