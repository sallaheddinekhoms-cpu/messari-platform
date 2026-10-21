<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add division tier to competitions
        Schema::table('competitions', function (Blueprint $table) {
            $table->string('division_tier')->nullable()->after('type'); // elite, level_1_a, level_1_b, regional_a, regional_b, regional_c
            $table->string('division_name_ar')->nullable()->after('division_tier');
        });

        // 2. Add 7 DTR Technical Pillars to scouting_reports
        Schema::table('scouting_reports', function (Blueprint $table) {
            $table->unsignedTinyInteger('attacking_efficiency')->default(7)->after('scout_user_id'); // 1. الفعالية الهجومية
            $table->unsignedTinyInteger('passing_playmaking')->default(7)->after('attacking_efficiency'); // 2. التمريرات وصناعة اللعب
            $table->unsignedTinyInteger('dribbling_progression')->default(7)->after('passing_playmaking'); // 3. المراوغات والتقدم بالكرة
            $table->unsignedTinyInteger('defensive_performance')->default(7)->after('dribbling_progression'); // 4. الأداء الدفاعي
            $table->unsignedTinyInteger('physical_performance')->default(7)->after('defensive_performance'); // 5. الأداء البدني
            $table->unsignedTinyInteger('tactical_intelligence')->default(7)->after('physical_performance'); // 6. الذكاء التكتيكي
            $table->unsignedTinyInteger('match_impact')->default(7)->after('tactical_intelligence'); // 7. التأثير العام في المباراة
            $table->decimal('overall_score', 3, 1)->default(7.0)->after('match_impact'); // التقييم العام
        });

        // 3. Create Media Galleries table for Photos and Videos
        Schema::create('media_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->string('media_url'); // file path or embed link
            $table->string('thumbnail_url')->nullable();
            $table->string('category')->default('all'); // elite, level_1, regional, camps, goals
            $table->date('event_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_galleries');

        Schema::table('scouting_reports', function (Blueprint $table) {
            $table->dropColumn([
                'attacking_efficiency',
                'passing_playmaking',
                'dribbling_progression',
                'defensive_performance',
                'physical_performance',
                'tactical_intelligence',
                'match_impact',
                'overall_score'
            ]);
        });

        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn(['division_tier', 'division_name_ar']);
        });
    }
};
