<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use App\Models\Article;
use App\Models\CareerTimeline;
use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\Competition;
use App\Models\GameMatch;
use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\Player;
use App\Models\PlayerLicense;
use App\Models\PlayerSeasonStat;
use App\Models\RegionalCamp;
use App\Models\Round;
use App\Models\ScoutingReport;
use App\Models\Season;
use App\Models\Stadium;
use App\Models\User;
use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasariInitialSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin & Technical Direction Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@lrf-blida.dz'],
            [
                'name' => 'مدير النظام - الرابطة الجهوية بالبليدة',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $techUser = User::firstOrCreate(
            ['email' => 'technique@lrf-blida.dz'],
            [
                'name' => 'المدير الفني الجهوي لرابطة البليدة',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Wilayas of LRF Blida
        $wilayasData = [
            ['code' => '09', 'name_ar' => 'البليدة', 'name_fr' => 'Blida'],
            ['code' => '42', 'name_ar' => 'تيبازة', 'name_fr' => 'Tipaza'],
            ['code' => '26', 'name_ar' => 'المدية', 'name_fr' => 'Médéa'],
            ['code' => '17', 'name_ar' => 'الجلفة', 'name_fr' => 'Djelfa'],
            ['code' => '44', 'name_ar' => 'عين الدفلى', 'name_fr' => 'Aïn Defla'],
        ];

        $wilayas = [];
        foreach ($wilayasData as $w) {
            $wilayas[$w['code']] = Wilaya::firstOrCreate(['code' => $w['code']], $w);
        }

        // 3. Stadiums
        $stadiumsData = [
            ['wilaya_code' => '09', 'name' => 'ملعب الإخوة براكني', 'city' => 'البليدة', 'surface_type' => 'natural_grass', 'capacity' => 10000],
            ['wilaya_code' => '09', 'name' => 'ملعب مصطفى تشاكر (الملحق)', 'city' => 'البليدة', 'surface_type' => 'natural_grass', 'capacity' => 2000],
            ['wilaya_code' => '09', 'name' => 'ملعب محمد رڤاز', 'city' => 'بوفاريك', 'surface_type' => 'artificial_turf', 'capacity' => 8000],
            ['wilaya_code' => '09', 'name' => 'ملعب إسماعيل مخلوف', 'city' => 'الأربعاء', 'surface_type' => 'artificial_turf', 'capacity' => 5000],
            ['wilaya_code' => '42', 'name' => 'ملعب 5 جويلية', 'city' => 'حجوط', 'surface_type' => 'artificial_turf', 'capacity' => 6000],
            ['wilaya_code' => '42', 'name' => 'ملعب علي حمرود', 'city' => 'القليعة', 'surface_type' => 'artificial_turf', 'capacity' => 7000],
            ['wilaya_code' => '26', 'name' => 'ملعب إلياس إمام', 'city' => 'المدية', 'surface_type' => 'artificial_turf', 'capacity' => 12000],
        ];

        $stadiums = [];
        foreach ($stadiumsData as $std) {
            $w = $wilayas[$std['wilaya_code']] ?? null;
            $stadiums[] = Stadium::firstOrCreate(
                ['name' => $std['name']],
                [
                    'wilaya_id' => $w?->id,
                    'city' => $std['city'],
                    'surface_type' => $std['surface_type'],
                    'capacity' => $std['capacity'],
                ]
            );
        }

        // 4. Seasons
        $seasonCurrent = Season::firstOrCreate(
            ['name' => '2026/2027'],
            [
                'start_date' => '2026-09-01',
                'end_date' => '2027-06-30',
                'is_current' => true,
            ]
        );

        $seasonPrevious = Season::firstOrCreate(
            ['name' => '2025/2026'],
            [
                'start_date' => '2025-09-01',
                'end_date' => '2026-06-30',
                'is_current' => false,
            ]
        );

        // 5. Age Categories (المعتمدة رسمياً في رزنامة الرابطة لموسم 2026/2027)
        $categoriesData = [
            ['code' => 'U20', 'name_ar' => 'أقل من 20 سنة', 'name_fr' => 'Moins de 20 ans', 'min_birth_year' => 2007, 'max_birth_year' => 2009, 'match_duration_minutes' => 90, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U17', 'name_ar' => 'أقل من 17 سنة', 'name_fr' => 'Moins de 17 ans', 'min_birth_year' => 2010, 'max_birth_year' => 2011, 'match_duration_minutes' => 80, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U15', 'name_ar' => 'أقل من 15 سنة', 'name_fr' => 'Moins de 15 ans', 'min_birth_year' => 2012, 'max_birth_year' => 2013, 'match_duration_minutes' => 70, 'substitutions_allowed' => 7, 'ball_size' => 4],
            ['code' => 'U18', 'name_ar' => 'أقل من 18 سنة (النخبة)', 'name_fr' => 'Moins de 18 ans', 'min_birth_year' => 2009, 'max_birth_year' => 2009, 'match_duration_minutes' => 90, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U16', 'name_ar' => 'أقل من 16 سنة (النخبة)', 'name_fr' => 'Moins de 16 ans', 'min_birth_year' => 2011, 'max_birth_year' => 2011, 'match_duration_minutes' => 80, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U19', 'name_ar' => 'أواسط ما دون 19 سنة', 'name_fr' => 'Moins de 19 ans', 'min_birth_year' => 2007, 'max_birth_year' => 2008, 'match_duration_minutes' => 90, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U13', 'name_ar' => 'براعم ما دون 13 سنة', 'name_fr' => 'Moins de 13 ans', 'min_birth_year' => 2014, 'max_birth_year' => 2015, 'match_duration_minutes' => 60, 'substitutions_allowed' => 9, 'ball_size' => 4],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['code']] = AgeCategory::firstOrCreate(['code' => $c['code']], $c);
        }

        // 6. Clubs
        $clubsData = [
            [
                'wilaya_code' => '09',
                'acronym' => 'USMB',
                'name_ar' => 'الاتحاد الرياضي لمدينة البليدة',
                'name_fr' => 'Union Sportive Médinat Blida',
                'short_name' => 'اتحاد البليدة',
                'primary_color' => '#008000',
                'secondary_color' => '#ffffff',
                'founded_year' => 1932,
            ],
            [
                'wilaya_code' => '09',
                'acronym' => 'WAB',
                'name_ar' => 'الوداد الرياضي لبوفاريك',
                'name_fr' => 'Widad Amel Boufarik',
                'short_name' => 'وداد بوفاريك',
                'primary_color' => '#ff8000',
                'secondary_color' => '#008000',
                'founded_year' => 1945,
            ],
            [
                'wilaya_code' => '09',
                'acronym' => 'RCA',
                'name_ar' => 'أمل الأربعاء',
                'name_fr' => 'Raed Chabab Kouba Arbaâ',
                'short_name' => 'أمل الأربعاء',
                'primary_color' => '#0000ff',
                'secondary_color' => '#ffffff',
                'founded_year' => 1941,
            ],
            [
                'wilaya_code' => '42',
                'acronym' => 'ESMK',
                'name_ar' => 'النجم الرياضي لمدينة القليعة',
                'name_fr' => 'Étoile Sportive Médinat Koléa',
                'short_name' => 'نجم القليعة',
                'primary_color' => '#008000',
                'secondary_color' => '#ff0000',
                'founded_year' => 1946,
            ],
            [
                'wilaya_code' => '42',
                'acronym' => 'USMMH',
                'name_ar' => 'الاتحاد الرياضي لبلدية حجوط',
                'name_fr' => 'Union Sportive Musulmane Marengo Hadjout',
                'short_name' => 'اتحاد حجوط',
                'primary_color' => '#008000',
                'secondary_color' => '#ffffff',
                'founded_year' => 1947,
            ],
            [
                'wilaya_code' => '26',
                'acronym' => 'OM',
                'name_ar' => 'أولمبي المدية',
                'name_fr' => 'Olympique de Médéa',
                'short_name' => 'أولمبي المدية',
                'primary_color' => '#ff8000',
                'secondary_color' => '#0000ff',
                'founded_year' => 1945,
            ],
        ];

        $clubs = [];
        foreach ($clubsData as $cd) {
            $w = $wilayas[$cd['wilaya_code']] ?? null;
            $logoCandidate = 'images/clubs/' . strtolower($cd['acronym']) . '.png';
            $clubs[$cd['acronym']] = Club::firstOrCreate(
                ['acronym' => $cd['acronym']],
                [
                    'wilaya_id' => $w?->id,
                    'name_ar' => $cd['name_ar'],
                    'name_fr' => $cd['name_fr'],
                    'short_name' => $cd['short_name'],
                    'slug' => Str::slug($cd['acronym']),
                    'primary_color' => $cd['primary_color'],
                    'secondary_color' => $cd['secondary_color'],
                    'founded_year' => $cd['founded_year'],
                    'logo_path' => file_exists(public_path($logoCandidate)) ? $logoCandidate : null,
                    'is_active' => true,
                ]
            );
        }

        // 7. Club Teams (for U17 and U15)
        $teamsU17 = [];
        $teamsU15 = [];
        foreach ($clubs as $acronym => $club) {
            $teamsU17[$acronym] = ClubTeam::firstOrCreate(
                [
                    'club_id' => $club->id,
                    'season_id' => $seasonCurrent->id,
                    'age_category_id' => $categories['U17']->id,
                ],
                [
                    'head_coach_name' => 'المدرب ' . $acronym . ' U17',
                    'coach_license_type' => 'CAF B',
                    'is_active' => true,
                ]
            );

            $teamsU15[$acronym] = ClubTeam::firstOrCreate(
                [
                    'club_id' => $club->id,
                    'season_id' => $seasonCurrent->id,
                    'age_category_id' => $categories['U15']->id,
                ],
                [
                    'head_coach_name' => 'المدرب ' . $acronym . ' U15',
                    'coach_license_type' => 'CAF C',
                    'is_active' => true,
                ]
            );
        }

        // 8. Competitions: U17 Championship
        $compU17 = Competition::firstOrCreate(
            [
                'season_id' => $seasonCurrent->id,
                'age_category_id' => $categories['U17']->id,
                'name_ar' => 'بطولة الرابطة الجهوية لأشبال البليدة U17',
            ],
            [
                'name_fr' => 'Championnat Régional U17 - LRF Blida',
                'type' => 'championship',
                'status' => 'in_progress',
            ]
        );

        $groupU17 = Group::firstOrCreate(
            ['competition_id' => $compU17->id, 'name' => 'المجموعة الأولى - وسط'],
            ['order' => 1]
        );

        // Group Teams & Standings
        $standings = [
            'USMB' => ['p' => 6, 'w' => 5, 'd' => 1, 'l' => 0, 'gf' => 16, 'ga' => 3, 'pts' => 16, 'rank' => 1, 'form' => 'W-W-W-D-W'],
            'WAB'  => ['p' => 6, 'w' => 4, 'd' => 1, 'l' => 1, 'gf' => 12, 'ga' => 5, 'pts' => 13, 'rank' => 2, 'form' => 'W-L-W-W-D'],
            'ESMK' => ['p' => 6, 'w' => 3, 'd' => 2, 'l' => 1, 'gf' => 10, 'ga' => 7, 'pts' => 11, 'rank' => 3, 'form' => 'D-W-W-L-D'],
            'RCA'  => ['p' => 6, 'w' => 2, 'd' => 1, 'l' => 3, 'gf' => 8,  'ga' => 11, 'pts' => 7,  'rank' => 4, 'form' => 'L-W-L-D-W'],
            'OM'   => ['p' => 6, 'w' => 1, 'd' => 1, 'l' => 4, 'gf' => 5,  'ga' => 13, 'pts' => 4,  'rank' => 5, 'form' => 'L-L-D-W-L'],
            'USMMH'=> ['p' => 6, 'w' => 0, 'd' => 0, 'l' => 6, 'gf' => 2,  'ga' => 17, 'pts' => 0,  'rank' => 6, 'form' => 'L-L-L-L-L'],
        ];

        foreach ($standings as $acr => $st) {
            $team = $teamsU17[$acr];
            GroupTeam::firstOrCreate(
                ['group_id' => $groupU17->id, 'club_team_id' => $team->id],
                [
                    'played' => $st['p'],
                    'won' => $st['w'],
                    'drawn' => $st['d'],
                    'lost' => $st['l'],
                    'goals_for' => $st['gf'],
                    'goals_against' => $st['ga'],
                    'goal_difference' => $st['gf'] - $st['ga'],
                    'points' => $st['pts'],
                    'rank' => $st['rank'],
                    'form' => $st['form'],
                ]
            );
        }

        // 9. Rounds & Matches
        $round1 = Round::firstOrCreate(
            ['group_id' => $groupU17->id, 'round_number' => 1],
            ['name_ar' => 'الجولة الأولى', 'is_current' => false]
        );

        $roundLatest = Round::firstOrCreate(
            ['group_id' => $groupU17->id, 'round_number' => 7],
            ['name_ar' => 'الجولة السابعة', 'is_current' => true]
        );

        // Finished Match
        $m1 = GameMatch::firstOrCreate(
            [
                'round_id' => $round1->id,
                'home_team_id' => $teamsU17['USMB']->id,
                'away_team_id' => $teamsU17['WAB']->id,
            ],
            [
                'stadium_id' => $stadiums[0]->id,
                'match_date' => '2026-10-04',
                'kickoff_time' => '10:00:00',
                'status' => 'finished',
                'home_score' => 3,
                'away_score' => 1,
                'referee_name' => 'أحمد بوقرة (حكم جهوي رابطة البليدة)',
                'is_validated' => true,
            ]
        );

        // Upcoming Match
        $m2 = GameMatch::firstOrCreate(
            [
                'round_id' => $roundLatest->id,
                'home_team_id' => $teamsU17['WAB']->id,
                'away_team_id' => $teamsU17['ESMK']->id,
            ],
            [
                'stadium_id' => $stadiums[2]->id,
                'match_date' => '2026-10-24',
                'kickoff_time' => '10:30:00',
                'status' => 'scheduled',
                'referee_name' => 'لجنة التحكيم الجهوية',
            ]
        );

        // 10. Featured Talents / Youth Players
        $playersData = [
            [
                'passport_number' => 'MASARI-09-2026-001',
                'first_name_ar' => 'أكرم',
                'last_name_ar' => 'منصوري',
                'first_name_fr' => 'Akram',
                'last_name_fr' => 'Mansouri',
                'birth_date' => '2009-03-15',
                'birth_place' => 'البليدة',
                'wilaya_code' => '09',
                'blood_type' => 'O+',
                'dominant_foot' => 'right',
                'height_cm' => 176,
                'weight_kg' => 67,
                'primary_position' => 'ST',
                'club_acronym' => 'USMB',
                'category' => 'U17',
                'stats' => ['goals' => 9, 'assists' => 4, 'matches' => 6, 'minutes' => 520],
                'bio' => 'مهاجم هداف يتميز بالسرعة والإنهاء الدقيق داخل منطقة الجزاء، ترعرع في مدرسة اتحاد البليدة منذ فئة البراعم U13.',
            ],
            [
                'passport_number' => 'MASARI-09-2026-002',
                'first_name_ar' => 'ياسين',
                'last_name_ar' => 'زرقي',
                'first_name_fr' => 'Yacine',
                'last_name_fr' => 'Zerki',
                'birth_date' => '2009-08-20',
                'birth_place' => 'بوفاريك',
                'wilaya_code' => '09',
                'blood_type' => 'A+',
                'dominant_foot' => 'left',
                'height_cm' => 172,
                'weight_kg' => 62,
                'primary_position' => 'AM',
                'club_acronym' => 'WAB',
                'category' => 'U17',
                'stats' => ['goals' => 5, 'assists' => 8, 'matches' => 6, 'minutes' => 540],
                'bio' => 'صانع ألعاب موهوب، صاحب قدم يسرى مميزة ورؤية شاملة للملعب، مرشح رئيسي لمنتخب رابطة البليدة U17.',
            ],
            [
                'passport_number' => 'MASARI-42-2026-003',
                'first_name_ar' => 'رياض',
                'last_name_ar' => 'بوزيد',
                'first_name_fr' => 'Ryad',
                'last_name_fr' => 'Bouzid',
                'birth_date' => '2009-05-11',
                'birth_place' => 'القليعة',
                'wilaya_code' => '42',
                'blood_type' => 'B+',
                'dominant_foot' => 'right',
                'height_cm' => 184,
                'weight_kg' => 74,
                'primary_position' => 'CB',
                'club_acronym' => 'ESMK',
                'category' => 'U17',
                'stats' => ['goals' => 2, 'assists' => 1, 'matches' => 6, 'minutes' => 540],
                'bio' => 'مدافع محوري قوي في الكرات الهوائية وقائد شجاع في خط الخلف لنجم القليعة.',
            ],
            [
                'passport_number' => 'MASARI-09-2026-004',
                'first_name_ar' => 'صهيب',
                'last_name_ar' => 'عمارة',
                'first_name_fr' => 'Sohaib',
                'last_name_fr' => 'Amara',
                'birth_date' => '2011-06-18',
                'birth_place' => 'الأربعاء',
                'wilaya_code' => '09',
                'blood_type' => 'AB+',
                'dominant_foot' => 'right',
                'height_cm' => 165,
                'weight_kg' => 54,
                'primary_position' => 'RW',
                'club_acronym' => 'RCA',
                'category' => 'U15',
                'stats' => ['goals' => 7, 'assists' => 5, 'matches' => 5, 'minutes' => 350],
                'bio' => 'جناح أيمن مهاري وسريع، أحد أبرز مواهب فئة الأصاغر U15 للرابطة الجهوية بالبليدة.',
            ],
        ];

        foreach ($playersData as $pd) {
            $w = $wilayas[$pd['wilaya_code']] ?? null;
            $club = $clubs[$pd['club_acronym']] ?? null;
            $cat = $categories[$pd['category']] ?? null;

            $player = Player::firstOrCreate(
                ['passport_number' => $pd['passport_number']],
                [
                    'uuid' => (string) Str::uuid(),
                    'first_name_ar' => $pd['first_name_ar'],
                    'last_name_ar' => $pd['last_name_ar'],
                    'first_name_fr' => $pd['first_name_fr'],
                    'last_name_fr' => $pd['last_name_fr'],
                    'birth_date' => $pd['birth_date'],
                    'birth_place' => $pd['birth_place'],
                    'wilaya_id' => $w?->id,
                    'nationality' => 'جزائرية',
                    'blood_type' => $pd['blood_type'],
                    'dominant_foot' => $pd['dominant_foot'],
                    'height_cm' => $pd['height_cm'],
                    'weight_kg' => $pd['weight_kg'],
                    'primary_position' => $pd['primary_position'],
                    'qr_token' => Str::random(40),
                    'status' => 'active',
                    'bio' => $pd['bio'],
                ]
            );

            // Create License for current season
            PlayerLicense::firstOrCreate(
                ['player_id' => $player->id, 'season_id' => $seasonCurrent->id],
                [
                    'club_id' => $club->id,
                    'age_category_id' => $cat->id,
                    'license_number' => 'LRFB-' . $cat->code . '-' . str_pad($player->id, 5, '0', STR_PAD_LEFT),
                    'medical_status' => 'fit',
                    'medical_check_date' => '2026-08-25',
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => $admin->id,
                ]
            );

            // Career timeline for current season
            CareerTimeline::firstOrCreate(
                [
                    'player_id' => $player->id,
                    'season_id' => $seasonCurrent->id,
                    'club_id' => $club->id,
                ],
                [
                    'age_category_id' => $cat->id,
                    'matches_count' => $pd['stats']['matches'],
                    'goals_count' => $pd['stats']['goals'],
                    'assists_count' => $pd['stats']['assists'],
                    'minutes_played' => $pd['stats']['minutes'],
                    'achievement_title' => 'متصدر قائمة هدافي فئة ' . $cat->code,
                ]
            );

            // Career timeline for previous season (U15)
            if ($cat->code === 'U17') {
                CareerTimeline::firstOrCreate(
                    [
                        'player_id' => $player->id,
                        'season_id' => $seasonPrevious->id,
                        'club_id' => $club->id,
                    ],
                    [
                        'age_category_id' => $categories['U15']->id,
                        'matches_count' => 18,
                        'goals_count' => 14,
                        'assists_count' => 9,
                        'minutes_played' => 1250,
                        'achievement_title' => 'وصيف كأس رابطة البليدة U15',
                    ]
                );
            }

            // Technical Scouting Report
            ScoutingReport::firstOrCreate(
                ['player_id' => $player->id, 'scout_user_id' => $techUser->id],
                [
                    'technical_score' => 9,
                    'tactical_score' => 8,
                    'physical_score' => 8,
                    'mental_score' => 9,
                    'potential_score' => 5,
                    'strengths' => 'حس تهديفي ممتاز، سرعة تنفيذ وتمركز ذكي داخل الصندوق، روح رياضية والتزام عالي.',
                    'weaknesses' => 'يحتاج لتطوير اللعب الدفاعي عند فقدان الكرة والمشاركة في الضغط العالي.',
                    'recommendation' => 'regional_selection',
                    'visibility' => 'dtr_only',
                ]
            );
        }

        // 11. Regional Camp
        $camp = RegionalCamp::firstOrCreate(
            [
                'season_id' => $seasonCurrent->id,
                'age_category_id' => $categories['U17']->id,
                'title' => 'التجمع الانتقائي الأول لمنتخب رابطة البليدة U17',
            ],
            [
                'location' => 'المركب الرياضي مصطفى تشاكر - البليدة',
                'stadium_id' => $stadiums[1]->id,
                'start_date' => '2026-11-05',
                'end_date' => '2026-11-08',
                'lead_coach' => 'مراد بوعلي (المدرب الجهوي لفئة U17)',
                'objectives' => 'معاينة أفضل 30 موهبة من ولايات البليدة، تيبازة، المدية، الجلفة وعين الدفلى لاختيار القائمة الرسمية لمنتخب الرابطة في الدورة الوطنية للشبان.',
            ]
        );

        // 12. Articles & Youth News
        Article::firstOrCreate(
            ['slug' => 'lancement-officiel-plateforme-masari-lrf-blida'],
            [
                'category' => 'announcement',
                'title' => 'الرابطة الجهوية لكرة القدم بالبليدة تطلق رسمياً منصة «مساري» الرقمية لمتابعة المواهب الشبانية',
                'excerpt' => 'في خطوة نوعية لتحديث تسيير كرة القدم القاعدية، دشنت رابطة البليدة منصة مساري لتوفير الملف الرياضي الرقمي لجميع لاعبي الفئات الصغرى.',
                'content' => 'أعلنت الرابطة الجهوية لكرة القدم بالبليدة عن الإطلاق الرسمي لمنظومة «مساري» (MASARI)، المنصة الرقمية المخصصة لتتبع المسار الرياضي التراكمي للمواهب الكروية الصاعدة في فئات U20, U17 و U15. تتيح المنصة لكل لاعب ملفاً رياضياً رقمياً مزوداً برمز QR، إضافة إلى لوحة بيانات متقدمة للمديرية الفنية الجهوية.',
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
