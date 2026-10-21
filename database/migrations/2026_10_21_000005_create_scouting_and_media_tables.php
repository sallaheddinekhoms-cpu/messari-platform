<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scouting_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('match_id')->nullable()->constrained('matches')->nullOnDelete();
            $table->foreignId('scout_user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('technical_score')->default(5); // 1 - 10
            $table->unsignedTinyInteger('tactical_score')->default(5);  // 1 - 10
            $table->unsignedTinyInteger('physical_score')->default(5);  // 1 - 10
            $table->unsignedTinyInteger('mental_score')->default(5);    // 1 - 10
            $table->unsignedTinyInteger('potential_score')->default(3); // 1 - 5 stars
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->enum('recommendation', [
                'regional_selection', // دعوة للمنتخب الجهوي
                'keep_monitoring',   // مواصلة المتابعة
                'club_alert',        // إشعار النادي بتطوير جانب محدد
                'elite_prospect'     // موهبة استثنائية
            ])->default('keep_monitoring');
            $table->enum('visibility', ['dtr_only', 'public_badge', 'shared_club'])->default('dtr_only');
            $table->timestamps();
        });

        Schema::create('regional_camps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('age_category_id')->constrained('age_categories')->cascadeOnDelete();
            $table->string('title'); // e.g. "تجمع انتقاء منتخب رابطة البليدة U17"
            $table->string('location');
            $table->foreignId('stadium_id')->nullable()->constrained('stadiums')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('lead_coach')->nullable();
            $table->text('objectives')->nullable();
            $table->timestamps();
        });

        Schema::create('camp_callups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_camp_id')->constrained('regional_camps')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->string('invitation_path')->nullable();
            $table->enum('attendance_status', ['confirmed', 'attended', 'absent_excused', 'absent_unexcused', 'injured'])->default('confirmed');
            $table->text('technical_notes')->nullable();
            $table->timestamps();

            $table->unique(['regional_camp_id', 'player_id']);
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['talent_of_week', 'tactical_review', 'medical_advice', 'academy_focus', 'announcement'])->default('announcement');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('weekly_talents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('age_category_id')->constrained('age_categories')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->unsignedTinyInteger('week_number');
            $table->string('headline'); // e.g. "موهبة الأسبوع: هداف بطولة U17 يسطع في سماء البليدة"
            $table->text('highlight_summary');
            $table->string('media_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_talents');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('camp_callups');
        Schema::dropIfExists('regional_camps');
        Schema::dropIfExists('scouting_reports');
    }
};
