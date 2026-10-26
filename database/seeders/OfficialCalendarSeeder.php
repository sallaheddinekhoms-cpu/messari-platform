<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\Competition;
use App\Models\GameMatch;
use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\Round;
use App\Models\Season;
use App\Models\Stadium;
use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OfficialCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $currentSeason = Season::where('is_current', true)->first() ?? Season::firstOrCreate(['name' => '2026/2027'], ['start_date' => '2026-09-01', 'end_date' => '2027-06-30', 'is_current' => true]);

        // Target age categories: U20, U17, U15
        $catU20 = AgeCategory::firstOrCreate(['code' => 'U20'], [
            'name_ar' => 'أقل من 20 سنة',
            'name_fr' => 'Moins de 20 ans',
            'min_birth_year' => 2007,
            'max_birth_year' => 2009,
            'match_duration_minutes' => 90,
            'substitutions_allowed' => 5,
            'ball_size' => 5,
        ]);

        $catU17 = AgeCategory::firstOrCreate(['code' => 'U17'], [
            'name_ar' => 'أقل من 17 سنة',
            'name_fr' => 'Moins de 17 ans',
            'min_birth_year' => 2010,
            'max_birth_year' => 2011,
            'match_duration_minutes' => 80,
            'substitutions_allowed' => 5,
            'ball_size' => 5,
        ]);

        $catU15 = AgeCategory::firstOrCreate(['code' => 'U15'], [
            'name_ar' => 'أقل من 15 سنة',
            'name_fr' => 'Moins de 15 ans',
            'min_birth_year' => 2012,
            'max_birth_year' => 2013,
            'match_duration_minutes' => 70,
            'substitutions_allowed' => 7,
            'ball_size' => 4,
        ]);

        $targetCats = ['U20' => $catU20, 'U17' => $catU17, 'U15' => $catU15];

        // 1. All 45 Official Clubs
        $clubsDefinitions = [
            // Élite (12)
            'ASO'   => ['02', 'جمعية أولمبي الشلف', 'Association Sportive Olympique de Chlef', 'جمعية الشلف', '#ff0000', '#ffffff'],
            'OM'    => ['26', 'أولمبي المدية (وفاق المدية)', 'Olympique Médéa', 'وفاق المدية', '#ff8000', '#0000ff'],
            'USMB'  => ['09', 'الاتحاد الرياضي لمدينة البليدة', 'Union Sportive Médinat Blida', 'اتحاد البليدة', '#008000', '#ffffff'],
            'ESMK'  => ['42', 'النجم الرياضي لمدينة القليعة', 'Étoile Sportive Médinat Koléa', 'نجم القليعة', '#008000', '#ff0000'],
            'RCA'   => ['09', 'أمل الأربعاء', 'Raed Chabab Kouba Arbaâ', 'أمل الأربعاء', '#0000ff', '#ffffff'],
            'ORBOF' => ['02', 'أولمبيك رائد بلدية واد الفضة', 'Olympique Riadhi Baladiat Oued Fodda', 'أولمبيك واد الفضة', '#ff0000', '#008000'],
            'MCBOS' => ['02', 'مستقبل شباب بلدية واد سلي', 'Mouloudia Club Baladiat Oued Sly', 'مستقبل واد سلي', '#ff8000', '#ffffff'],
            'SKAF'  => ['44', 'صفاء الخميس', 'Safaa Khemis Miliana', 'صفاء الخميس', '#ff0000', '#ffffff'],
            'CRBZ'  => ['09', 'شباب بلدية الزاوية', 'Chabab Riadhi Baladiat Zaouia', 'شباب الزاوية', '#008000', '#ffffff'],
            'WRM'   => ['09', 'وداد بلدية مفتاح', 'Widad Riadhi Meftah', 'وداد مفتاح', '#0000ff', '#ffffff'],
            'OME'   => ['26', 'آمال المدية', 'Espoir Olympique Médéa', 'آمال المدية', '#0000ff', '#ff8000'],
            'MCOY'  => ['09', 'مولودية أولاد يعيش', 'Mouloudia Club Ouled Yaich', 'مولودية أولاد يعيش', '#008000', '#ff0000'],

            // Régionale A (12)
            'IRBSG' => ['42', 'اتحاد سيدي غيلاس', 'Union Sportive Sidi Ghiles', 'اتحاد سيدي غيلاس', '#008000', '#ffffff'],
            'JSM'   => ['09', 'شباب مالحة', 'Jeunesse Sportive Malha', 'شباب مالحة', '#0000ff', '#ffffff'],
            'FCMB'  => ['42', 'فريق بوركيكة', 'Football Club Médinat Bourkika', 'فريق بوركيكة', '#ff0000', '#ffffff'],
            'IRBChi'=> ['09', 'اتحاد الشفة', 'Union Sportive Chiffa', 'اتحاد الشفة', '#008000', '#ff0000'],
            'HBG'   => ['09', 'هلال قرواو', 'Hilal Baladiat Guerrouaou', 'هلال قرواو', '#008000', '#ffffff'],
            'SCG'   => ['09', 'سبورتينغ قرواو', 'Sporting Club Guerrouaou', 'سبورتينغ قرواو', '#ff8000', '#000000'],
            'CRAB'  => ['26', 'شباب عين بوسيف', 'Chabab Riadhi Ain Boussaif', 'شباب عين بوسيف', '#0000ff', '#ffffff'],
            'MBSN'  => ['26', 'مولودية سيدي نعمان', 'Mouloudia Baladiat Sidi Naamane', 'مولودية سيدي نعمان', '#ff0000', '#ffffff'],
            'USCA'  => ['26', 'اتحاد شلالة العذاورة', 'Union Sportive Chellalat El Adhaoura', 'اتحاد شلالة العذاورة', '#008000', '#ffffff'],
            'RCAE'  => ['26', 'رائد شلالة العذاورة', 'Raed Chellalat El Adhaoura', 'رائد شلالة العذاورة', '#ff0000', '#008000'],
            'CBS'   => ['26', 'شباب سغوان', 'Chabab Baladiat Seghouane', 'شباب سغوان', '#008000', '#ffffff'],
            'JSB'   => ['26', 'شباب البرواقية', 'Jeunesse Sportive Berrouaghia', 'شباب البرواقية', '#0000ff', '#ffffff'],

            // Régionale B (11 - سنجاس مستبعد)
            'SCM'   => ['44', 'اتحاد مليانة', 'Sporting Club Miliana', 'اتحاد مليانة', '#ff0000', '#ffffff'],
            'IRBBM' => ['44', 'اتحاد بومدفع', 'Union Sportive Boumedfaa', 'اتحاد بومدفع', '#008000', '#ffffff'],
            'NBBI'  => ['42', 'نادي بواسماعيل', 'Nadi Bou Ismail', 'نادي بواسماعيل', '#0000ff', '#ffffff'],
            'CRBT'  => ['42', 'شباب تيبازة', 'Chabab Riadhi Tipaza', 'شباب تيبازة', '#008000', '#ffffff'],
            'ESF'   => ['44', 'أمل الفرم', 'Espérance Sportive El Fern', 'أمل الفرم', '#ff8000', '#ffffff'],
            'JST'   => ['02', 'شباب تاوقريت', 'Jeunesse Sportive Taougrit', 'شباب تاوقريت', '#0000ff', '#ffffff'],
            'NRBS'  => ['02', 'نجم بقعة سحنون', 'Nedjm Riadhi Boughat Sahnoun', 'نجم بقعة سحنون', '#008000', '#ffffff'],
            'CRBB'  => ['02', 'شباب بوقادير', 'Chabab Riadhi Boukadir', 'شباب بوقادير', '#ff0000', '#ffffff'],
            'JSEB'  => ['44', 'شباب العطاف', 'Jeunesse Sportive El Attaf', 'شباب العطاف', '#008000', '#ffffff'],
            'HR'    => ['44', 'هلال الروينة', 'Hilal Rouina', 'هلال الروينة', '#0000ff', '#ffffff'],
            'JSA'   => ['44', 'شباب العامرة', 'Jeunesse Sportive El Amra', 'شباب العامرة', '#ff0000', '#ffffff'],

            // Régionale C - الجلفة (10)
            'WRNAO' => ['17', 'وداد عين وسارة', 'Widad Riadhi Ain Oussera', 'وداد عين وسارة', '#008000', '#ffffff'],
            'IRBHS' => ['17', 'أمل حد الصحاري', 'Ittihad Riadhi Baladiat Had Sahary', 'أمل حد الصحاري', '#ff0000', '#ffffff'],
            'MCHB'  => ['17', 'مولودية حاسي بحبح', 'Mouloudia Club Hassi Bahbah', 'مولودية حاسي بحبح', '#0000ff', '#ffffff'],
            'JMB'   => ['17', 'جيل البيرين', 'Jeunesse Médinat Birine', 'جيل البيرين', '#008000', '#ffffff'],
            'FCJ'   => ['17', 'فريق كرة القدم الجلفة', 'Football Club Djelfa', 'فريق كرة القدم الجلفة', '#ff8000', '#ffffff'],
            'ARBM'  => ['17', 'أمل مسعد', 'Amel Riadhi Baladiat Messaad', 'أمل مسعد', '#ff0000', '#ffffff'],
            'WBSL'  => ['17', 'وداد سيدي لعجال', 'Widad Baladiat Sidi Laadjal', 'وداد سيدي لعجال', '#008000', '#ffffff'],
            'ESL'   => ['17', 'ترجي سيدي لعجال', 'Espérance Sidi Laadjal', 'ترجي سيدي لعجال', '#0000ff', '#ffffff'],
            'CRBSN' => ['17', 'شباب سيدي نايل', 'Chabab Riadhi Sidi Naïl', 'شباب سيدي نايل', '#008000', '#ffffff'],
            'IRON'  => ['17', 'اتحاد أولاد نايل', 'Union Sportive Ouled Naïl', 'اتحاد أولاد نايل', '#ff0000', '#ffffff'],
        ];

        $wilayas = Wilaya::all()->keyBy('code');
        $clubs = [];

        $logoMap = [
            'USMB'  => 'images/clubs/usmb.png',
            'ASO'   => 'images/clubs/aso.png',
            'RCA'   => 'images/clubs/rca.png',
            'ESMK'  => 'images/clubs/esmk.png',
            'ORBOF' => 'images/clubs/orbof.png',
            'MCBOS' => 'images/clubs/mcbos.png',
            'SKAF'  => 'images/clubs/skaf.png',
            'MCOY'  => 'images/clubs/mcoy.png',
            'CRBAO' => 'images/clubs/crbao.png',
            'CRBBT' => 'images/clubs/crbbt.png',
            'WRM'   => 'images/clubs/wrm.png',
            'WBM'   => 'images/clubs/wrm.png',
            'MSC'   => 'images/clubs/msc.png',
            'CRBZ'  => 'images/clubs/crbz.png',
            'CRZ'   => 'images/clubs/crbz.png',
            'ACSK'  => 'images/clubs/acsk.png',
            'OME'   => 'images/clubs/ome.png',
            'WAB'   => 'images/clubs/wab.png',
            'WRNAO' => 'images/clubs/wrnao.png',
            'AEM'   => 'images/clubs/aem.png',
            'RD'    => 'images/clubs/rd.png',
            'OM'    => 'images/clubs/om.png',
        ];

        foreach ($clubsDefinitions as $acronym => $cDef) {
            $w = $wilayas[$cDef[0]] ?? null;
            $club = Club::updateOrCreate(
                ['acronym' => $acronym],
                [
                    'wilaya_id' => $w?->id,
                    'name_ar' => $cDef[1],
                    'name_fr' => $cDef[2],
                    'short_name' => $cDef[3],
                    'primary_color' => $cDef[4],
                    'secondary_color' => $cDef[5],
                    'logo_path' => $logoMap[$acronym] ?? null,
                    'is_active' => true,
                ]
            );
            $clubs[$acronym] = $club;

            // Create ClubTeam for U20, U17, U15
            foreach ($targetCats as $code => $catObj) {
                ClubTeam::firstOrCreate(
                    [
                        'club_id' => $club->id,
                        'season_id' => $currentSeason->id,
                        'age_category_id' => $catObj->id,
                    ],
                    [
                        'head_coach_name' => 'المدرب ' . $cDef[3] . ' ' . $code,
                        'coach_license_type' => 'CAF C',
                        'is_active' => true,
                    ]
                );
            }
        }

        // 2. Divisions Configurations
        $divisionsConfig = [
            'elite' => [
                'name_ar' => 'بطولة النخبة (Élite)',
                'name_fr' => 'Championnat Jeunes Élite',
                'clubs' => ['ASO', 'OM', 'USMB', 'ESMK', 'RCA', 'ORBOF', 'MCBOS', 'SKAF', 'CRBZ', 'WRM', 'OME', 'MCOY'],
                'rounds' => [
                    1 => [['ESMK', 'CRBZ'], ['USMB', 'OME'], ['ORBOF', 'ASO'], ['SKAF', 'MCBOS'], ['OM', 'MCOY'], ['WRM', 'RCA']],
                    2 => [['ESMK', 'MCBOS'], ['SKAF', 'ORBOF'], ['MCOY', 'RCA'], ['OM', 'OME'], ['USMB', 'CRBZ'], ['ASO', 'WRM']],
                    3 => [['ORBOF', 'ESMK'], ['SKAF', 'ASO'], ['OM', 'CRBZ'], ['OME', 'RCA'], ['MCBOS', 'USMB'], ['MCOY', 'WRM']],
                    4 => [['ESMK', 'SKAF'], ['CRBZ', 'RCA'], ['OM', 'MCBOS'], ['USMB', 'ORBOF'], ['WRM', 'OME'], ['ASO', 'MCOY']],
                    5 => [['ESMK', 'ASO'], ['SKAF', 'USMB'], ['CRBZ', 'WRM'], ['OME', 'MCOY'], ['ORBOF', 'OM'], ['MCBOS', 'RCA']],
                    6 => [['ESMK', 'USMB'], ['RCA', 'ORBOF'], ['OM', 'SKAF'], ['MCOY', 'CRBZ'], ['WRM', 'MCBOS'], ['OME', 'ASO']],
                    7 => [['ESMK', 'OM'], ['SKAF', 'RCA'], ['OME', 'CRBZ'], ['ORBOF', 'WRM'], ['ASO', 'USMB'], ['MCBOS', 'MCOY']],
                    8 => [['ESMK', 'RCA'], ['OME', 'MCBOS'], ['USMB', 'OM'], ['MCOY', 'ORBOF'], ['WRM', 'SKAF'], ['CRBZ', 'ASO']],
                    9 => [['ESMK', 'WRM'], ['SKAF', 'MCOY'], ['ORBOF', 'OME'], ['ASO', 'OM'], ['USMB', 'RCA'], ['MCBOS', 'CRBZ']],
                    10 => [['ESMK', 'MCOY'], ['CRBZ', 'ORBOF'], ['OM', 'RCA'], ['OME', 'SKAF'], ['MCBOS', 'ASO'], ['WRM', 'USMB']],
                    11 => [['ESMK', 'OME'], ['SKAF', 'CRBZ'], ['ORBOF', 'MCBOS'], ['OM', 'WRM'], ['USMB', 'MCOY'], ['ASO', 'RCA']],
                ]
            ],
            'regional_a' => [
                'name_ar' => 'المستوى الجهوي • فوج (أ)',
                'name_fr' => 'Championnat Régional - Groupe A',
                'clubs' => ['IRBSG', 'JSM', 'FCMB', 'IRBChi', 'HBG', 'SCG', 'CRAB', 'MBSN', 'USCA', 'RCAE', 'CBS', 'JSB'],
                'rounds' => [
                    1 => [['IRBSG', 'JSM'], ['FCMB', 'IRBChi'], ['HBG', 'SCG'], ['CRAB', 'MBSN'], ['USCA', 'RCAE'], ['CBS', 'JSB']],
                    2 => [['IRBSG', 'CRAB'], ['MBSN', 'HBG'], ['CBS', 'USCA'], ['RCAE', 'IRBChi'], ['JSM', 'FCMB'], ['JSB', 'SCG']],
                    3 => [['HBG', 'IRBSG'], ['MBSN', 'SCG'], ['JSM', 'RCAE'], ['IRBChi', 'CBS'], ['CRAB', 'FCMB'], ['USCA', 'JSB']],
                    4 => [['IRBSG', 'MBSN'], ['JSM', 'CBS'], ['RCAE', 'CRAB'], ['FCMB', 'HBG'], ['IRBChi', 'JSB'], ['SCG', 'USCA']],
                    5 => [['IRBSG', 'SCG'], ['MBSN', 'FCMB'], ['JSB', 'JSM'], ['IRBChi', 'USCA'], ['HBG', 'RCAE'], ['CRAB', 'CBS']],
                    6 => [['FCMB', 'IRBSG'], ['HBG', 'CBS'], ['RCAE', 'MBSN'], ['USCA', 'JSM'], ['JSB', 'CRAB'], ['SCG', 'IRBChi']],
                    7 => [['IRBSG', 'RCAE'], ['MBSN', 'CBS'], ['JSM', 'IRBChi'], ['HBG', 'JSB'], ['SCG', 'FCMB'], ['CRAB', 'USCA']],
                    8 => [['CBS', 'IRBSG'], ['IRBChi', 'CRAB'], ['RCAE', 'FCMB'], ['USCA', 'HBG'], ['JSB', 'MBSN'], ['JSM', 'SCG']],
                    9 => [['IRBSG', 'JSB'], ['MBSN', 'USCA'], ['HBG', 'IRBChi'], ['RCAE', 'SCG'], ['CBS', 'FCMB'], ['CRAB', 'JSM']],
                    10 => [['USCA', 'IRBSG'], ['HBG', 'JSM'], ['CBS', 'RCAE'], ['IRBChi', 'MBSN'], ['CRAB', 'SCG'], ['FCMB', 'JSB']],
                    11 => [['IRBSG', 'RCAE'], ['MBSN', 'JSM'], ['HBG', 'CRAB'], ['RCAE', 'JSB'], ['FCMB', 'USCA'], ['CBS', 'SCG']],
                ]
            ],
            'regional_b' => [
                'name_ar' => 'المستوى الجهوي • فوج (ب)',
                'name_fr' => 'Championnat Régional - Groupe B',
                'clubs' => ['SCM', 'IRBBM', 'NBBI', 'CRBT', 'ESF', 'JST', 'NRBS', 'CRBB', 'JSEB', 'HR', 'JSA'],
                'rounds' => [
                    1 => [['SCM', 'IRBBM'], ['NBBI', 'CRBT'], ['JST', 'NRBS'], ['CRBB', 'JSEB'], ['HR', 'JSA']],
                    2 => [['SCM', 'JST'], ['NRBS', 'ESF'], ['CRBB', 'JSA'], ['JSEB', 'NBBI'], ['IRBBM', 'CRBT']],
                    3 => [['SCM', 'ESF'], ['JSEB', 'IRBBM'], ['NBBI', 'JSA'], ['CRBT', 'JST'], ['CRBB', 'HR']],
                    4 => [['SCM', 'NRBS'], ['JSA', 'IRBBM'], ['JST', 'JSEB'], ['CRBT', 'ESF'], ['HR', 'NBBI']],
                    5 => [['NRBS', 'CRBT'], ['HR', 'IRBBM'], ['NBBI', 'CRBB'], ['ESF', 'JSEB'], ['JST', 'JSA']],
                    6 => [['SCM', 'CRBT'], ['JSA', 'ESF'], ['JSEB', 'NRBS'], ['IRBBM', 'CRBB'], ['HR', 'JST']],
                    7 => [['SCM', 'JSEB'], ['NRBS', 'JSA'], ['IRBBM', 'NBBI'], ['ESF', 'HR'], ['JST', 'CRBB']],
                    8 => [['JSA', 'SCM'], ['NBBI', 'JST'], ['CRBT', 'JSEB'], ['CRBB', 'ESF'], ['HR', 'NRBS']],
                    9 => [['HR', 'SCM'], ['NRBS', 'CRBB'], ['ESF', 'NBBI'], ['CRBT', 'JSA'], ['JST', 'IRBBM']],
                    10 => [['CRBB', 'SCM'], ['ESF', 'IRBBM'], ['JSEB', 'JSA'], ['NBBI', 'NRBS'], ['CRBT', 'HR']],
                    11 => [['SCM', 'NBBI'], ['NRBS', 'IRBBM'], ['JST', 'ESF'], ['HR', 'JSEB'], ['CRBT', 'CRBB']],
                ]
            ],
            'regional_c' => [
                'name_ar' => 'المستوى الجهوي • فوج (ج / س)',
                'name_fr' => 'Championnat Régional - Groupe C (Djelfa)',
                'clubs' => ['WRNAO', 'IRBHS', 'MCHB', 'JMB', 'FCJ', 'ARBM', 'WBSL', 'ESL', 'CRBSN', 'IRON'],
                'rounds' => [
                    1 => [['WRNAO', 'IRBHS'], ['MCHB', 'JMB'], ['FCJ', 'ARBM'], ['WBSL', 'ESL'], ['CRBSN', 'IRON']],
                    2 => [['JMB', 'WRNAO'], ['IRBHS', 'WBSL'], ['IRON', 'FCJ'], ['ARBM', 'MCHB'], ['ESL', 'CRBSN']],
                    3 => [['WRNAO', 'MCHB'], ['IRON', 'ARBM'], ['FCJ', 'ESL'], ['WBSL', 'JMB'], ['CRBSN', 'IRBHS']],
                    4 => [['ARBM', 'WRNAO'], ['IRBHS', 'FCJ'], ['JMB', 'CRBSN'], ['MCHB', 'WBSL'], ['ESL', 'IRON']],
                    5 => [['WBSL', 'WRNAO'], ['IRON', 'IRBHS'], ['FCJ', 'JMB'], ['ESL', 'ARBM'], ['CRBSN', 'MCHB']],
                    6 => [['WRNAO', 'CRBSN'], ['IRBHS', 'ESL'], ['ARBM', 'WBSL'], ['JMB', 'IRON'], ['MCHB', 'FCJ']],
                    7 => [['FCJ', 'WRNAO'], ['IRBHS', 'ARBM'], ['IRON', 'MCHB'], ['ESL', 'JMB'], ['CRBSN', 'WBSL']],
                    8 => [['WRNAO', 'IRON'], ['JMB', 'IRBHS'], ['MCHB', 'ESL'], ['WBSL', 'FCJ'], ['CRBSN', 'ARBM']],
                    9 => [['ESL', 'WRNAO'], ['IRBHS', 'MCHB'], ['IRON', 'WBSL'], ['ARBM', 'JMB'], ['FCJ', 'CRBSN']],
                ]
            ],
        ];

        $defaultStadium = Stadium::first();

        // 3. Seed Competitions, Groups, Standings, Rounds and Fixtures
        foreach ($divisionsConfig as $tier => $dConf) {
            foreach ($targetCats as $catCode => $catObj) {
                $compNameAr = "{$dConf['name_ar']} - فئة {$catCode}";
                $compNameFr = "{$dConf['name_fr']} - Catégorie {$catCode}";

                $competition = Competition::updateOrCreate(
                    [
                        'season_id' => $currentSeason->id,
                        'age_category_id' => $catObj->id,
                        'division_tier' => $tier,
                    ],
                    [
                        'name_ar' => $compNameAr,
                        'name_fr' => $compNameFr,
                        'division_name_ar' => $dConf['name_ar'],
                        'type' => 'championship',
                        'status' => 'in_progress',
                    ]
                );

                // Group
                $group = Group::updateOrCreate(
                    [
                        'competition_id' => $competition->id,
                        'name' => 'المجموعة الرسمية المعتمدة',
                    ],
                    [
                        'order' => 1,
                    ]
                );

                // Standings (GroupTeams)
                $clubList = $dConf['clubs'];
                $totalClubs = count($clubList);

                foreach ($clubList as $rankIdx => $clubAcr) {
                    $club = $clubs[$clubAcr] ?? null;
                    if (!$club) continue;

                    $cTeam = ClubTeam::where('club_id', $club->id)
                        ->where('age_category_id', $catObj->id)
                        ->where('season_id', $currentSeason->id)
                        ->first();

                    if (!$cTeam) continue;

                    // Standings calculation for 5 played matches
                    $played = 5;
                    $won = max(0, 5 - intval($rankIdx * 5 / $totalClubs));
                    $drawn = ($rankIdx % 2 === 0 && $won < 5) ? 1 : 0;
                    $lost = max(0, $played - ($won + $drawn));
                    $gf = $won * 2 + $drawn * 1 + (12 - $rankIdx);
                    $ga = $lost * 2 + ($rankIdx % 4);
                    $pts = ($won * 3) + $drawn;

                    GroupTeam::updateOrCreate(
                        [
                            'group_id' => $group->id,
                            'club_team_id' => $cTeam->id,
                        ],
                        [
                            'played' => $played,
                            'won' => $won,
                            'drawn' => $drawn,
                            'lost' => $lost,
                            'goals_for' => $gf,
                            'goals_against' => $ga,
                            'goal_difference' => $gf - $ga,
                            'points' => $pts,
                            'rank' => $rankIdx + 1,
                            'form' => $won >= 3 ? 'W,W,D,W,W' : ($won >= 1 ? 'W,L,D,W,L' : 'L,D,L,L,D'),
                        ]
                    );
                }

                // Rounds and Matches
                foreach ($dConf['rounds'] as $rNum => $fixtures) {
                    $isCurrent = ($rNum === 6);
                    $isFinished = ($rNum <= 5);

                    $roundDate = now()->startOfWeek()->addWeeks($rNum - 6);

                    $round = Round::updateOrCreate(
                        [
                            'group_id' => $group->id,
                            'round_number' => $rNum,
                        ],
                        [
                            'name_ar' => "الجولة " . str_pad($rNum, 2, '0', STR_PAD_LEFT),
                            'name_fr' => "Journée {$rNum}",
                            'start_date' => $roundDate->format('Y-m-d'),
                            'end_date' => $roundDate->copy()->addDays(2)->format('Y-m-d'),
                            'is_current' => $isCurrent,
                        ]
                    );

                    foreach ($fixtures as $fIndex => $pair) {
                        $homeAcr = $pair[0];
                        $awayAcr = $pair[1];

                        $homeClub = $clubs[$homeAcr] ?? null;
                        $awayClub = $clubs[$awayAcr] ?? null;

                        if (!$homeClub || !$awayClub) continue;

                        $homeTeam = ClubTeam::where('club_id', $homeClub->id)->where('age_category_id', $catObj->id)->first();
                        $awayTeam = ClubTeam::where('club_id', $awayClub->id)->where('age_category_id', $catObj->id)->first();

                        if (!$homeTeam || !$awayTeam) continue;

                        $matchDate = $roundDate->copy()->addDays($fIndex % 2);
                        $kickoff = ($fIndex % 2 === 0) ? '09:30:00' : '11:00:00';

                        $hScore = $isFinished ? (($fIndex + $rNum) % 3) + 1 : null;
                        $aScore = $isFinished ? (($fIndex * 2 + $rNum) % 2) : null;
                        $status = $isFinished ? 'finished' : 'scheduled';

                        GameMatch::updateOrCreate(
                            [
                                'round_id' => $round->id,
                                'home_team_id' => $homeTeam->id,
                                'away_team_id' => $awayTeam->id,
                            ],
                            [
                                'stadium_id' => $defaultStadium?->id,
                                'match_date' => $matchDate->format('Y-m-d'),
                                'kickoff_time' => $kickoff,
                                'status' => $status,
                                'home_score' => $hScore,
                                'away_score' => $aScore,
                                'referee_name' => 'حكم رسمي معتمد (LRFB)',
                                'is_validated' => $isFinished,
                            ]
                        );
                    }
                }
            }
        }
    }
}
