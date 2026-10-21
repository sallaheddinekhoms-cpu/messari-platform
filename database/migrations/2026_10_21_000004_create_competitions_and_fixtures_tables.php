<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('age_category_id')->constrained('age_categories')->cascadeOnDelete();
            $table->string('name_ar');
            $table->string('name_fr');
            $table->enum('type', ['championship', 'cup', 'playoff', 'festival'])->default('championship');
            $table->string('logo_path')->nullable();
            $table->enum('status', ['upcoming', 'in_progress', 'completed'])->default('in_progress');
            $table->timestamps();
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->cascadeOnDelete();
            $table->string('name'); // e.g. المجموعة أ (وسط)
            $table->unsignedTinyInteger('order')->default(1);
            $table->timestamps();
        });

        Schema::create('group_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('club_team_id')->constrained('club_teams')->cascadeOnDelete();
            $table->unsignedSmallInteger('played')->default(0);
            $table->unsignedSmallInteger('won')->default(0);
            $table->unsignedSmallInteger('drawn')->default(0);
            $table->unsignedSmallInteger('lost')->default(0);
            $table->unsignedSmallInteger('goals_for')->default(0);
            $table->unsignedSmallInteger('goals_against')->default(0);
            $table->integer('goal_difference')->default(0);
            $table->unsignedSmallInteger('points')->default(0);
            $table->smallInteger('penalty_deductions')->default(0);
            $table->unsignedTinyInteger('rank')->default(1);
            $table->string('form', 20)->nullable(); // e.g. W,W,D,L,W
            $table->timestamps();

            $table->unique(['group_id', 'club_team_id']);
        });

        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->unsignedSmallInteger('round_number');
            $table->string('name_ar'); // الجولة الأولى
            $table->string('name_fr')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds')->cascadeOnDelete();
            $table->foreignId('home_team_id')->constrained('club_teams')->cascadeOnDelete();
            $table->foreignId('away_team_id')->constrained('club_teams')->cascadeOnDelete();
            $table->foreignId('stadium_id')->nullable()->constrained('stadiums')->nullOnDelete();
            $table->date('match_date');
            $table->time('kickoff_time')->default('10:00:00');
            $table->enum('status', ['scheduled', 'live', 'finished', 'postponed', 'cancelled'])->default('scheduled');
            $table->unsignedSmallInteger('home_score')->nullable();
            $table->unsignedSmallInteger('away_score')->nullable();
            $table->unsignedSmallInteger('home_penalty_score')->nullable();
            $table->unsignedSmallInteger('away_penalty_score')->nullable();
            $table->string('referee_name')->nullable();
            $table->string('assistant1_name')->nullable();
            $table->string('assistant2_name')->nullable();
            $table->string('delegate_name')->nullable();
            $table->string('match_sheet_path')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->timestamps();
        });

        Schema::create('match_lineups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('club_team_id')->constrained('club_teams')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->unsignedTinyInteger('shirt_number');
            $table->boolean('is_starter')->default(true);
            $table->boolean('is_captain')->default(false);
            $table->string('position_played', 10)->nullable();
            $table->unsignedSmallInteger('subbed_in_minute')->nullable();
            $table->unsignedSmallInteger('subbed_out_minute')->nullable();
            $table->unsignedSmallInteger('minutes_played')->default(0);
            $table->decimal('rating', 3, 1)->nullable();
            $table->timestamps();
        });

        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('club_team_id')->constrained('club_teams')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('assist_player_id')->nullable()->constrained('players')->nullOnDelete();
            $table->enum('event_type', ['goal', 'penalty_goal', 'own_goal', 'yellow_card', 'red_card', 'sub_in', 'sub_out', 'penalty_saved']);
            $table->unsignedSmallInteger('minute');
            $table->unsignedSmallInteger('extra_minute')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('player_season_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('club_team_id')->constrained('club_teams')->cascadeOnDelete();
            $table->unsignedSmallInteger('matches_played')->default(0);
            $table->unsignedSmallInteger('starts')->default(0);
            $table->unsignedInteger('minutes_played')->default(0);
            $table->unsignedSmallInteger('goals')->default(0);
            $table->unsignedSmallInteger('assists')->default(0);
            $table->unsignedSmallInteger('clean_sheets')->default(0);
            $table->unsignedSmallInteger('yellow_cards')->default(0);
            $table->unsignedSmallInteger('red_cards')->default(0);
            $table->unsignedSmallInteger('mvp_count')->default(0);
            $table->timestamps();

            $table->unique(['player_id', 'season_id', 'club_team_id'], 'player_season_team_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_season_stats');
        Schema::dropIfExists('match_events');
        Schema::dropIfExists('match_lineups');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('rounds');
        Schema::dropIfExists('group_teams');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('competitions');
    }
};
