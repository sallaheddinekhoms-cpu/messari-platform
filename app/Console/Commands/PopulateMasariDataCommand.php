<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Season;
use App\Models\AgeCategory;
use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\Competition;
use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\Round;
use App\Models\GameMatch;
use App\Models\Stadium;
use App\Models\Player;
use App\Models\CareerTimeline;
use App\Models\ScoutingReport;
use App\Models\MediaGallery;
use App\Models\User;
use Illuminate\Support\Str;

class PopulateMasariDataCommand extends Command
{
    protected $signature = 'messari:populate-full-system';
    protected $description = 'Populate all divisions (Élite, Niveau 1, Régional) for U20, U17, U15, career timelines from 2021, DTR 7-pillar ratings, and media gallery.';

    public function handle()
    {
        $this->info('Starting MESSARI full data population...');

        // 1. Seasons Setup (from 2021 to 2027)
        $seasonsData = [
            ['name' => '2021/2022', 'start_date' => '2021-09-01', 'end_date' => '2022-06-30', 'is_current' => false],
            ['name' => '2022/2023', 'start_date' => '2022-09-01', 'end_date' => '2023-06-30', 'is_current' => false],
            ['name' => '2023/2024', 'start_date' => '2023-09-01', 'end_date' => '2024-06-30', 'is_current' => false],
            ['name' => '2024/2025', 'start_date' => '2024-09-01', 'end_date' => '2025-06-30', 'is_current' => false],
            ['name' => '2025/2026', 'start_date' => '2025-09-01', 'end_date' => '2026-06-30', 'is_current' => false],
            ['name' => '2026/2027', 'start_date' => '2026-09-01', 'end_date' => '2027-06-30', 'is_current' => true],
        ];

        $seasons = [];
        foreach ($seasonsData as $sData) {
            $seasons[$sData['name']] = Season::firstOrCreate(['name' => $sData['name']], $sData);
        }
        $currentSeason = $seasons['2026/2027'];
        $this->info('Seasons (2021 - 2027) verified.');

        // 2. Age Categories (Ensure U20, U17, U15 exist)
        $catU20 = AgeCategory::firstOrCreate(['code' => 'U20'], [
            'name_ar' => 'أقل من 20 سنة (النخبة)',
            'name_fr' => 'Moins de 20 ans (Élite)',
            'min_age' => 18,
            'max_age' => 20
        ]);
        $catU17 = AgeCategory::firstOrCreate(['code' => 'U17'], [
            'name_ar' => 'أقل من 17 سنة (الأشبال)',
            'name_fr' => 'Moins de 17 ans (Cadets)',
            'min_age' => 15,
            'max_age' => 17
        ]);
        $catU15 = AgeCategory::firstOrCreate(['code' => 'U15'], [
            'name_ar' => 'أقل من 15 سنة (الأصاغر)',
            'name_fr' => 'Moins de 15 ans (Minimes)',
            'min_age' => 13,
            'max_age' => 15
        ]);
        $targetCategories = [$catU20, $catU17, $catU15];

        // 3. Divisions Structure (6 Divisions × 3 Categories = 18 Competitions)
        $divisions = [
            [
                'tier' => 'elite',
                'name_ar' => 'بطولة النخبة',
                'name_fr' => 'Division Élite Régionale',
            ],
            [
                'tier' => 'level_1_a',
                'name_ar' => 'المستوى الأول (أ)',
                'name_fr' => 'Niveau 1 - Groupe A',
            ],
            [
                'tier' => 'level_1_b',
                'name_ar' => 'المستوى الأول (ب)',
                'name_fr' => 'Niveau 1 - Groupe B',
            ],
            [
                'tier' => 'regional_a',
                'name_ar' => 'المستوى الجهوي (أ)',
                'name_fr' => 'Division Régionale - Groupe A',
            ],
            [
                'tier' => 'regional_b',
                'name_ar' => 'المستوى الجهوي (ب)',
                'name_fr' => 'Division Régionale - Groupe B',
            ],
            [
                'tier' => 'regional_c',
                'name_ar' => 'المستوى الجهوي (ج)',
                'name_fr' => 'Division Régionale - Groupe C',
            ],
        ];

        $clubs = Club::all();
        if ($clubs->count() < 10) {
            $this->error('Not enough clubs found.');
            return 1;
        }

        $stadiums = Stadium::all();
        $defaultStadium = $stadiums->first();

        // Create club teams if missing for categories
        foreach ($clubs as $club) {
            foreach ($targetCategories as $cat) {
                ClubTeam::firstOrCreate([
                    'club_id' => $club->id,
                    'age_category_id' => $cat->id,
                ], [
                    'season_id' => $currentSeason->id,
                    'is_active' => true,
                ]);
            }
        }

        $competitionsCreated = 0;
        $matchesCreated = 0;

        foreach ($divisions as $divIndex => $div) {
            foreach ($targetCategories as $catIndex => $cat) {
                $compNameAr = "{$div['name_ar']} - فئة {$cat->code} ({$cat->name_ar})";
                $compNameFr = "{$div['name_fr']} - Catégorie {$cat->code}";

                $competition = Competition::updateOrCreate([
                    'season_id' => $currentSeason->id,
                    'age_category_id' => $cat->id,
                    'division_tier' => $div['tier'],
                ], [
                    'name_ar' => $compNameAr,
                    'name_fr' => $compNameFr,
                    'division_name_ar' => $div['name_ar'],
                    'type' => 'championship',
                    'status' => 'in_progress',
                ]);

                // Create or get Group
                $group = Group::firstOrCreate([
                    'competition_id' => $competition->id,
                    'name' => 'المجموعة الموحدة',
                ]);

                // Select 8 to 10 teams for this group
                $sliceStart = ($divIndex * 4 + $catIndex * 2) % ($clubs->count() - 10);
                $compClubs = $clubs->slice(max(0, $sliceStart), 8);

                $clubTeams = [];
                foreach ($compClubs as $cIndex => $club) {
                    $team = ClubTeam::where('club_id', $club->id)
                        ->where('age_category_id', $cat->id)
                        ->first();
                    if ($team) {
                        $clubTeams[] = $team;
                        
                        // Seed standings with realistic scores
                        $played = 6;
                        $won = max(0, 5 - ($cIndex % 4));
                        $drawn = ($cIndex % 2 === 0) ? 1 : 0;
                        $lost = max(0, $played - ($won + $drawn));
                        $gf = $won * 2 + $drawn + ($cIndex % 3);
                        $ga = $lost * 2 + ($cIndex % 2);
                        $pts = ($won * 3) + $drawn;

                        GroupTeam::updateOrCreate([
                            'group_id' => $group->id,
                            'club_team_id' => $team->id,
                        ], [
                            'played' => $played,
                            'won' => $won,
                            'drawn' => $drawn,
                            'lost' => $lost,
                            'goals_for' => $gf,
                            'goals_against' => $ga,
                            'goal_difference' => $gf - $ga,
                            'points' => $pts,
                            'rank' => $cIndex + 1,
                            'form' => 'W,W,D,L,W',
                        ]);
                    }
                }

                // Create 8 Rounds with actual fixtures
                for ($rNum = 1; $rNum <= 8; $rNum++) {
                    $isCurrent = ($rNum === 7);
                    $round = Round::firstOrCreate([
                        'group_id' => $group->id,
                        'round_number' => $rNum,
                    ], [
                        'name_ar' => "الجولة {$rNum}",
                        'name_fr' => "Journée {$rNum}",
                        'start_date' => now()->subWeeks(7 - $rNum)->format('Y-m-d'),
                        'end_date' => now()->subWeeks(7 - $rNum)->addDays(2)->format('Y-m-d'),
                        'is_current' => $isCurrent,
                    ]);

                    // Generate matches if round has no matches
                    if ($round->matches()->count() === 0 && count($clubTeams) >= 4) {
                        for ($m = 0; $m < 4 && ($m * 2 + 1) < count($clubTeams); $m++) {
                            $home = $clubTeams[$m * 2];
                            $away = $clubTeams[$m * 2 + 1];
                            $mDate = now()->subWeeks(7 - $rNum)->addDays($m % 2);

                            $isFinished = ($rNum < 7);
                            $homeScore = $isFinished ? rand(0, 3) : null;
                            $awayScore = $isFinished ? rand(0, 2) : null;
                            $status = $isFinished ? 'finished' : ($rNum === 7 ? 'scheduled' : 'scheduled');

                            GameMatch::create([
                                'round_id' => $round->id,
                                'home_team_id' => $home->id,
                                'away_team_id' => $away->id,
                                'stadium_id' => $home->club->stadium_id ?: ($defaultStadium?->id),
                                'match_date' => $mDate->format('Y-m-d'),
                                'kickoff_time' => ($m % 2 === 0) ? '09:30:00' : '11:00:00',
                                'status' => $status,
                                'home_score' => $homeScore,
                                'away_score' => $awayScore,
                                'referee_name' => 'حكم معتمد من الرابطة الجهوية',
                            ]);
                            $matchesCreated++;
                        }
                    }
                }

                $competitionsCreated++;
            }
        }
        $this->info("Created {$competitionsCreated} competitions with {$matchesCreated} schedule matches.");

        // 4. Generate Timelines Starting from 2021 for Players
        $samplePlayers = Player::with('currentLicense.club')->take(500)->get();
        $this->info("Generating career timelines from 2021 for {$samplePlayers->count()} sample players...");

        $timelineSeasons = [
            '2021/2022' => ['code' => 'U13', 'cat' => $catU15],
            '2022/2023' => ['code' => 'U15', 'cat' => $catU15],
            '2023/2024' => ['code' => 'U15', 'cat' => $catU15],
            '2024/2025' => ['code' => 'U17', 'cat' => $catU17],
            '2025/2026' => ['code' => 'U17', 'cat' => $catU17],
            '2026/2027' => ['code' => 'U20', 'cat' => $catU20],
        ];

        foreach ($samplePlayers as $p) {
            $clubId = $p->currentLicense?->club_id ?? $clubs->first()->id;

            foreach ($timelineSeasons as $sName => $info) {
                $season = $seasons[$sName];
                $matchesCount = rand(10, 22);
                $goalsCount = in_array($p->primary_position, ['CF', 'ST', 'RW', 'LW', 'AM']) ? rand(3, 14) : rand(0, 3);
                $assistsCount = rand(1, 8);
                $minutes = $matchesCount * rand(60, 85);

                $achievement = null;
                if ($sName === '2023/2024' && $goalsCount > 8) {
                    $achievement = 'وصيف بطل دورة كؤوس الرابطة للفئات الصغرى';
                } elseif ($sName === '2024/2025' && $goalsCount > 6) {
                    $achievement = 'بطل كأس الرابطة الجهوية بالبليدة U17';
                } elseif ($sName === '2025/2026') {
                    $achievement = 'دعوة رسمية لتربص انتقاء المنتخب الجهوي (DTR)';
                }

                CareerTimeline::updateOrCreate([
                    'player_id' => $p->id,
                    'season_id' => $season->id,
                ], [
                    'club_id' => $clubId,
                    'age_category_id' => $info['cat']->id,
                    'matches_count' => $matchesCount,
                    'minutes_played' => $minutes,
                    'goals_count' => $goalsCount,
                    'assists_count' => $assistsCount,
                    'yellow_cards' => rand(0, 3),
                    'red_cards' => 0,
                    'achievement_title' => $achievement,
                    'notes' => "مسار رياضي موثق للموسم الرياضي {$sName}",
                ]);
            }
        }
        $this->info('Career timelines from 2021 seeded successfully.');

        // 5. Seed Advanced 7-Pillar DTR Scouting Reports
        $adminUser = User::first() ?? User::create([
            'name' => 'المديرية الفنية الجهوية DTR',
            'email' => 'dtr@lrf-blida.dz',
            'password' => bcrypt('password123'),
        ]);

        $featured = Player::take(25)->get();
        foreach ($featured as $fp) {
            // Specific 7 pillars based on position
            $isAttacker = in_array($fp->primary_position, ['CF', 'ST', 'RW', 'LW', 'AM']);
            $isDefender = in_array($fp->primary_position, ['CB', 'LB', 'RB', 'DM']);

            $attScore = $isAttacker ? rand(8, 10) : rand(5, 7);
            $passScore = rand(7, 9);
            $dribbleScore = $isAttacker ? rand(8, 10) : rand(6, 8);
            $defScore = $isDefender ? rand(8, 10) : rand(5, 7);
            $physScore = rand(7, 9);
            $tactScore = rand(7, 9);
            $impactScore = rand(8, 10);

            $overall = round(($attScore + $passScore + $dribbleScore + $defScore + $physScore + $tactScore + $impactScore) / 7, 1);

            ScoutingReport::updateOrCreate([
                'player_id' => $fp->id,
            ], [
                'scout_user_id' => $adminUser->id,
                'attacking_efficiency' => $attScore,
                'passing_playmaking' => $passScore,
                'dribbling_progression' => $dribbleScore,
                'defensive_performance' => $defScore,
                'physical_performance' => $physScore,
                'tactical_intelligence' => $tactScore,
                'match_impact' => $impactScore,
                'overall_score' => $overall,
                'technical_score' => $attScore,
                'tactical_score' => $tactScore,
                'physical_score' => $physScore,
                'mental_score' => $impactScore,
                'potential_score' => 5,
                'strengths' => 'امتلاك نظرة استباقية ممتازة وسرعة عالية في التحول الهجومي، دقة في اتخاذ القرارات تحت الضغط العالي، وتحكم فني متميز بالكرة مع قدرة على حسم النزاعات الثنائية.',
                'weaknesses' => 'الحاجة لتعزيز التمركز الدفاعي عند الكرات الثابتة والمزيد من التواصل الصوتي مع بقية الخطوط.',
                'recommendation' => 'regional_selection',
                'visibility' => 'public_badge',
            ]);
        }
        $this->info('7-Pillar DTR Scouting Reports populated.');

        // 6. Media Gallery (Photos and Videos)
        MediaGallery::truncate();

        $mediaData = [
            [
                'title' => 'نهائي كأس الرابطة الجهوية بالبليدة فئة الأشبال U17',
                'type' => 'video',
                'media_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // or direct video stream
                'thumbnail_url' => asset('images/masari-logo.jpg'),
                'category' => 'elite',
                'event_date' => '2026-05-18',
                'description' => 'تغطية مصورة كاملة لأجواء النهائي الحماسي وتتويج بطل كأس الرابطة الجهوية بحضور أعضاء المديرية الفنية.',
                'is_featured' => true,
                'views_count' => 1420,
            ],
            [
                'title' => 'ملخص أهداف الجولة السادسة لبطولة النخبة U20',
                'type' => 'video',
                'media_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'thumbnail_url' => asset('images/masari-logo.jpg'),
                'category' => 'goals',
                'event_date' => '2026-10-14',
                'description' => 'أجمل 5 أهداف سُجلت في ملاعب ولايات البليدة، المدية، وتيبازة خلال الجولة السادسة.',
                'is_featured' => true,
                'views_count' => 2850,
            ],
            [
                'title' => 'تربص انتقاء المنتخب الجهوي للمواهب الشبانية بالمركب الرياضي مصطفى تشاكر',
                'type' => 'photo',
                'media_url' => asset('images/masari-logo.jpg'),
                'thumbnail_url' => asset('images/masari-logo.jpg'),
                'category' => 'camps',
                'event_date' => '2026-09-28',
                'description' => 'ألبوم صور من الحصص التدريبية والاختبارات البدنية والتقنية التي أشرفت عليها المديرية الفنية الجهوية.',
                'is_featured' => true,
                'views_count' => 980,
            ],
            [
                'title' => 'مهرجان البراعم الكروي U13 بميدان براكني بالبليدة',
                'type' => 'photo',
                'media_url' => asset('images/masari-logo.jpg'),
                'thumbnail_url' => asset('images/masari-logo.jpg'),
                'category' => 'regional',
                'event_date' => '2026-10-05',
                'description' => 'مشاركة أكثر من 24 مدرسة كروية وأكاديمية شبانية في المهرجان السنوي للبراعم والروح الرياضية.',
                'is_featured' => false,
                'views_count' => 740,
            ],
            [
                'title' => 'مهارات ومراوغات موهبة الأسبوع في بطولة الأصاغر U15',
                'type' => 'video',
                'media_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'thumbnail_url' => asset('images/masari-logo.jpg'),
                'category' => 'goals',
                'event_date' => '2026-10-17',
                'description' => 'لقطات مميزة لصانع الألعاب البارز وتمريراته الحاسمة التي قادت ناديه لصدارة المجموعة الأولى.',
                'is_featured' => true,
                'views_count' => 1910,
            ],
            [
                'title' => 'صور التكريم السنوي لهدافي بطولات الرابطة والمواهب الواعدة',
                'type' => 'photo',
                'media_url' => asset('images/masari-logo.jpg'),
                'thumbnail_url' => asset('images/masari-logo.jpg'),
                'category' => 'awards',
                'event_date' => '2026-06-20',
                'description' => 'حفل اختتام الموسم الرياضي وتوزيع الجوازات الرقمية الشرفية والجوائز التشجيعية.',
                'is_featured' => false,
                'views_count' => 1150,
            ],
        ];

        foreach ($mediaData as $m) {
            MediaGallery::create($m);
        }
        $this->info('Media Gallery (Photos & Videos) populated.');

        $this->info('ALL DATA POPULATED SUCCESSFULLY!');
        return 0;
    }
}
