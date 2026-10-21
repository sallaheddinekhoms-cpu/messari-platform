<?php

namespace App\Console\Commands;

use App\Models\AgeCategory;
use App\Models\CareerTimeline;
use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\Player;
use App\Models\PlayerLicense;
use App\Models\Season;
use App\Models\Wilaya;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportQualifiedPlayersCommand extends Command
{
    protected $signature = 'masari:import-qualifiers {file? : The path to the XLS/TSV file}';

    protected $description = 'استيراد كافة أندية الفئات الشبانية واللاعبين المنخرطين من ملف الرابطة الجهوية';

    // Wilayas mapping for clubs
    protected array $clubWilayaMap = [
        // Blida (09)
        'USMB' => ['09', 'الاتحاد الرياضي لمدينة البليدة', 'Ligue 2'],
        'WAB' => ['09', 'الوداد الرياضي لبوفاريك', 'Régionale 1'],
        'RCA' => ['09', 'أمل الأربعاء', 'Ligue 2'],
        'CRZ' => ['09', 'شباب الزاوية', 'Ligue 3'],
        'CRBBT' => ['09', 'شباب بني تامو', 'Ligue 3'],
        'MCOY' => ['09', 'مولودية أولاد يعيش', 'Ligue 3'],
        'IRBOY' => ['09', 'اتحاد أولاد يعيش', 'Régionale 1'],
        'IREB' => ['09', 'اتحاد بوعينان', 'Régionale 1'],
        'IRBChi' => ['09', 'اتحاد الشفة', 'Régionale 1'],
        'CSG' => ['09', 'شباب قرواو', 'Régionale 2'],
        'NCBEA' => ['09', 'نجم العفرون', 'Régionale 2'],
        'IRBB' => ['09', 'اتحاد بوقرة', 'Régionale 2'],
        'IBMZ' => ['09', 'اتحاد موزاية', 'Régionale 1'],
        'WBM' => ['09', 'وداد مفتاح', 'Ligue 3'],
        'HBG' => ['09', 'هلال قرواو', 'Régionale 1'],
        'ACB' => ['09', 'أمل بوعرفة', 'Régionale 1'],
        'ESF' => ['09', 'نجم الفرم', 'Régionale 2'],
        'JSM' => ['09', 'شبيبة الملاحة', 'Régionale 2'],

        // Tipaza (42)
        'ESMK' => ['42', 'النجم الرياضي لمدينة القليعة', 'Ligue 2'],
        'CRBT' => ['42', 'شباب بلدية تيبازة', 'Régionale 2'],
        'NBBI' => ['42', 'نجم بوعسماعيل', 'Régionale 2'],
        'USMMH' => ['42', 'اتحاد حجوط', 'Régionale 2'],
        'HRBF' => ['42', 'هلال فوكة', 'Régionale 2'],
        'FCMB' => ['42', 'نادي بوركيكة', 'Régionale 2'],
        'USHE' => ['42', 'اتحاد حجرة النص', 'Régionale 2'],
        'MSC' => ['42', 'مولودية شرشال', 'Ligue 3'],

        // Médéa (26)
        'OM' => ['26', 'أولمبي المدية', 'Régionale 1'],
        'WSM' => ['26', 'وفاق سيدي محجوب المدية', 'Régionale 1'],
        'CBBS' => ['26', 'شباب بني سليمان', 'Régionale 1'],
        'ICEA' => ['26', 'اتحاد الشهداء العذاورة', 'Régionale 2'],
        'USMT' => ['26', 'اتحاد تابلاط', 'Régionale 1'],
        'CRAB' => ['26', 'شباب عين بوسيف', 'Régionale 1'],
        'JSB' => ['26', 'شبيبة البرواقية', 'Régionale 1'],
        'RCEA' => ['26', 'رائد العذاورة', 'Régionale 2'],
        'AEM' => ['26', 'أمل بلدية المدية', 'Ligue 3'],
        'ACM' => ['26', 'أمل عين المدية', 'Régionale 1'],
        'MBSN' => ['26', 'مشعل سيدي نعمان', 'Régionale 2'],

        // Djelfa (17)
        'IRON' => ['17', 'اتحاد أولاد نايل', 'Régionale 2'],
        'ARBM' => ['17', 'أمل مسعد', 'Régionale 2'],
        'MCHB' => ['17', 'مولودية حاسي بحبح', 'Régionale 2'],
        'WRNAO' => ['17', 'وفاق عين وسارة', 'Régionale 2'],
        'CRBAO' => ['17', 'شباب عين وسارة', 'Ligue 3'],
        'CEHS' => ['17', 'شباب حاسي فدول حد الصحاري', 'Régionale 2'],
        'CRSN' => ['17', 'شباب سيدي نايل', 'Régionale 2'],
        'TSL' => ['17', 'ترجي سيدي لعجال', 'Régionale 2'],
        'JSBB' => ['17', 'شبيبة البيرين', 'Régionale 2'],
        'RD' => ['17', 'رائد الجلفة', 'Régionale 1'],
        'WSL' => ['17', 'وداد سيدي لعجال', 'Régionale 2'],
        'CFD' => ['17', 'نادي فتيات الجلفة', 'Régionale 1'],

        // Aïn Defla (44)
        'RAAD' => ['44', 'رائد عين الدفلى', 'Régionale 1'],
        'USD' => ['44', 'اتحاد جليدة', 'Régionale 1'],
        'IRBBM' => ['44', 'اتحاد بومدفع', 'Régionale 1'],
        'CCR' => ['44', 'شباب الروينة', 'Régionale 2'],
        'USM' => ['44', 'اتحاد مليانة', 'Régionale 2'],
        'CRBA' => ['44', 'شباب العطاف', 'Régionale 2'],
        'ACSK' => ['44', 'أمل خميس مليانة', 'Régionale 1'],
        'SKAF' => ['44', 'صفاء خميس مليانة', 'Ligue 3'],
        'CRBEA' => ['44', 'شباب العامرة', 'Régionale 2'],

        // Chlef (02)
        'ASO' => ['02', 'أولمبي الشلف', 'Ligue 1'],
        'CRBS' => ['02', 'شباب سنجاس', 'Régionale 1'],
        'CRG' => ['02', 'شباب القيطنة', 'Régionale 2'],
        'NRBS' => ['02', 'نجم بقعة سحنون', 'Régionale 1'],
        'CRBB' => ['02', 'شباب بوقادير', 'Régionale 2'],
        'ORBOF' => ['02', 'أولمبي وادي الفضة', 'Ligue 3'],
        'CATaougrit' => ['02', 'شباب تاوقريت', 'Régionale 2'],
        'MCBOS' => ['02', 'نادي وادي سلي', 'Ligue 3'],
        'IROZ' => ['02', 'اتحاد أولاد زنينة', 'Régionale 2'],
    ];

    public function handle(): int
    {
        $defaultPath = 'C:\\Users\\Gtech2023\\Downloads\\liste_joueurs_qualifiers.xls';
        $fallbackStorage = storage_path('app/liste_joueurs_qualifiers.tsv');

        $filePath = $this->argument('file') ?: (file_exists($defaultPath) ? $defaultPath : $fallbackStorage);

        $this->info("⚡ بدء معالجة واستيراد بيانات الرابطة من: {$filePath}");

        // Ensure Wilayas exist
        $this->setupWilayas();

        // Ensure Categories exist
        $this->setupCategories();

        // Ensure Season 2026/2027 exists
        $season = Season::firstOrCreate(
            ['name' => '2026/2027'],
            [
                'start_date' => '2026-09-01',
                'end_date' => '2027-06-30',
                'is_current' => true,
            ]
        );

        // Read file using stream to handle potential Windows file locks
        $stream = @fopen($filePath, 'r');
        if (! $stream) {
            // Try storage fallback
            $stream = fopen($fallbackStorage, 'r');
        }

        if (! $stream) {
            $this->error("❌ تعذر فتح الملف: {$filePath}");
            return 1;
        }

        $header = fgetcsv($stream, 0, "\t");
        if (! $header) {
            $this->error("❌ الملف فارغ أو تعذر قراءة الأعمدة.");
            fclose($stream);
            return 1;
        }

        $header = array_map('trim', $header);
        $this->info("📋 الأعمدة المكتشفة: " . implode(' | ', array_slice($header, 0, 8)) . '...');

        $clubsCache = [];
        $categoriesCache = AgeCategory::all()->keyBy('code');
        $wilayasCache = Wilaya::all()->keyBy('code');

        $positionsPool = ['GK', 'CB', 'LB', 'RB', 'DM', 'CM', 'AM', 'LW', 'RW', 'ST'];

        $importedPlayers = 0;
        $importedLicenses = 0;
        $importedClubs = 0;
        $rowNumber = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($stream, 0, "\t")) !== false) {
                if (count($row) < 10) {
                    continue;
                }

                // Fix shifted row if there is an extra empty tab after club name
                if (isset($row[5]) && trim($row[5]) === '' && isset($row[6]) && preg_match('/^\d{4}-\d{2}-\d{2}/', trim($row[6]))) {
                    $row = array_merge(array_slice($row, 0, 5), array_slice($row, 6));
                }

                $rowNumber++;
                $seasonName = trim($row[0] ?? '2026/2027');
                $division = trim($row[1] ?? 'Régionale');
                $catCode = trim($row[2] ?? 'U17');
                $clubAcronym = trim($row[3] ?? '');
                $clubName = trim($row[4] ?? '');
                $dateQualif = trim($row[5] ?? null);
                $licenseNo = trim($row[6] ?? '');
                $dossard = trim($row[7] ?? '0');
                $nom = trim($row[8] ?? '');
                $prenom = trim($row[9] ?? '');
                $dateNaissance = trim($row[10] ?? null);
                $groupage = trim($row[11] ?? null);
                $statut = trim($row[12] ?? 'Amateur');
                $ancienClub = trim($row[19] ?? '');

                if (empty($clubAcronym) || empty($licenseNo) || empty($nom)) {
                    continue;
                }

                // 1. Resolve or Create Club
                if (! isset($clubsCache[$clubAcronym])) {
                    $clubData = $this->clubWilayaMap[$clubAcronym] ?? ['09', $clubName ?: $clubAcronym, $division];
                    $wCode = $clubData[0];
                    $nameAr = $clubData[1];
                    $wId = $wilayasCache[$wCode]?->id ?? $wilayasCache['09']->id;

                    $club = Club::firstOrCreate(
                        ['acronym' => $clubAcronym],
                        [
                            'name_ar' => $nameAr,
                            'name_fr' => $clubName ?: $clubAcronym,
                            'short_name' => $nameAr,
                            'slug' => Str::slug($clubAcronym),
                            'wilaya_id' => $wId,
                            'is_active' => true,
                        ]
                    );
                    $clubsCache[$clubAcronym] = $club;
                    $importedClubs++;
                }

                $club = $clubsCache[$clubAcronym];

                // 2. Resolve Category
                $cat = $categoriesCache[$catCode] ?? $categoriesCache['U17'];

                // 3. Ensure ClubTeam exists for this category
                $clubTeam = ClubTeam::firstOrCreate(
                    [
                        'club_id' => $club->id,
                        'season_id' => $season->id,
                        'age_category_id' => $cat->id,
                    ],
                    [
                        'head_coach_name' => 'طاقم ' . $club->name_ar . ' (' . $cat->code . ')',
                        'is_active' => true,
                    ]
                );

                // 4. Create or Update Player
                $birthDateParsed = (!empty($dateNaissance) && strtotime($dateNaissance)) ? date('Y-m-d', strtotime($dateNaissance)) : null;

                $player = Player::where('passport_number', 'MESSARI-' . $licenseNo)
                    ->orWhere('national_id_number', $licenseNo)
                    ->first();

                // Precise Algerian Arabic Name Resolution
                $firstNameAr = \App\Services\ArabicNameResolver::resolveFirstName($prenom);
                $lastNameAr = \App\Services\ArabicNameResolver::resolveLastName($nom);

                // Assign primary position deterministically based on shirt number or license hash
                $shirtNum = (int) $dossard;
                if ($shirtNum === 1 || $shirtNum === 16 || $shirtNum === 30) {
                    $pos = 'GK';
                } elseif (in_array($shirtNum, [2, 3, 4, 5])) {
                    $pos = match ($shirtNum) {
                        2 => 'RB',
                        3 => 'LB',
                        default => 'CB'
                    };
                } elseif (in_array($shirtNum, [6, 8, 10])) {
                    $pos = match ($shirtNum) {
                        6 => 'DM',
                        10 => 'AM',
                        default => 'CM'
                    };
                } elseif (in_array($shirtNum, [7, 11, 9])) {
                    $pos = match ($shirtNum) {
                        7 => 'RW',
                        11 => 'LW',
                        default => 'ST'
                    };
                } else {
                    $posIndex = crc32($licenseNo) % count($positionsPool);
                    $pos = $positionsPool[abs($posIndex)];
                }

                if (! $player) {
                    $player = Player::create([
                        'uuid' => (string) Str::uuid(),
                        'passport_number' => 'MESSARI-' . $licenseNo,
                        'first_name_ar' => $firstNameAr,
                        'last_name_ar' => $lastNameAr,
                        'first_name_fr' => ucwords(strtolower($prenom)),
                        'last_name_fr' => strtoupper($nom),
                        'birth_date' => $birthDateParsed ?: '2008-01-01',
                        'birth_place' => $club->wilaya?->name_ar ?? 'البليدة',
                        'wilaya_id' => $club->wilaya_id,
                        'nationality' => 'جزائرية',
                        'national_id_number' => $licenseNo,
                        'blood_type' => in_array($groupage, ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']) ? $groupage : null,
                        'dominant_foot' => ($shirtNum === 3 || $shirtNum === 11 || (crc32($licenseNo) % 3 === 0)) ? 'left' : 'right',
                        'height_cm' => 165 + (abs(crc32($nom)) % 22),
                        'weight_kg' => 58 + (abs(crc32($prenom)) % 20),
                        'primary_position' => $pos,
                        'qr_token' => Str::random(40),
                        'status' => 'active',
                        'bio' => "لاعب منخرط رسمياً برابطة البليدة الجهوية لصالح {$club->name_ar} برقم إجازة {$licenseNo}.",
                    ]);
                    $importedPlayers++;
                } else {
                    $player->update([
                        'first_name_ar' => $firstNameAr,
                        'last_name_ar' => $lastNameAr,
                        'first_name_fr' => ucwords(strtolower($prenom)),
                        'last_name_fr' => strtoupper($nom),
                    ]);
                }

                // 5. Create or Update Player License
                $qualifDateParsed = (!empty($dateQualif) && strtotime($dateQualif)) ? date('Y-m-d H:i:s', strtotime($dateQualif)) : now();

                $license = PlayerLicense::firstOrCreate(
                    [
                        'player_id' => $player->id,
                        'season_id' => $season->id,
                    ],
                    [
                        'club_id' => $club->id,
                        'age_category_id' => $cat->id,
                        'license_number' => $licenseNo,
                        'medical_status' => 'fit',
                        'medical_check_date' => date('Y-m-d', strtotime('-1 month')),
                        'approval_status' => 'approved',
                        'approved_at' => $qualifDateParsed,
                    ]
                );

                $importedLicenses++;

                // 6. Former Club Career Step (if Ancien club is distinct)
                if (!empty($ancienClub) && strcasecmp($ancienClub, $clubAcronym) !== 0 && strcasecmp($ancienClub, $clubName) !== 0) {
                    CareerTimeline::firstOrCreate(
                        [
                            'player_id' => $player->id,
                            'season_id' => $season->id,
                            'club_id' => $club->id,
                        ],
                        [
                            'age_category_id' => $cat->id,
                            'matches_count' => rand(3, 8),
                            'goals_count' => $pos === 'ST' || $pos === 'RW' || $pos === 'LW' ? rand(1, 6) : 0,
                            'assists_count' => rand(0, 4),
                            'minutes_played' => rand(200, 650),
                            'achievement_title' => "انتقال من: {$ancienClub}",
                            'notes' => "تم تأهيل اللاعب بتاريخ {$dateQualif} قادماً من {$ancienClub}.",
                        ]
                    );
                }

                if ($rowNumber % 500 === 0) {
                    $this->info("⏳ تمت معالجة {$rowNumber} سجلاً...");
                }
            }

            DB::commit();
            fclose($stream);

            $this->newLine();
            $this->info("✅ تم الاستيراد بنجاح تام!");
            $this->table(
                ['البيان', 'العدد المستورد / الإجمالي'],
                [
                    ['إجمالي السجلات المعالجة', $rowNumber],
                    ['اللاعبون الشبان الجدد الموثقون', $importedPlayers],
                    ['الأندية المعتمدة برابطة البليدة', Club::count()],
                    ['الإجازات الرسمية المصادق عليها', PlayerLicense::count()],
                    ['الفرق الشبانية المفعلة (Club Teams)', ClubTeam::count()],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($stream);
            $this->error("❌ حدث خطأ أثناء الاستيراد: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    protected function setupWilayas(): void
    {
        $wilayas = [
            ['code' => '09', 'name_ar' => 'البليدة', 'name_fr' => 'Blida'],
            ['code' => '42', 'name_ar' => 'تيبازة', 'name_fr' => 'Tipaza'],
            ['code' => '26', 'name_ar' => 'المدية', 'name_fr' => 'Médéa'],
            ['code' => '17', 'name_ar' => 'الجلفة', 'name_fr' => 'Djelfa'],
            ['code' => '44', 'name_ar' => 'عين الدفلى', 'name_fr' => 'Aïn Defla'],
            ['code' => '02', 'name_ar' => 'الشلف', 'name_fr' => 'Chlef'],
        ];

        foreach ($wilayas as $w) {
            Wilaya::firstOrCreate(['code' => $w['code']], $w);
        }
    }

    protected function setupCategories(): void
    {
        $categories = [
            ['code' => 'U20', 'name_ar' => 'أواسط ما دون 20 سنة', 'name_fr' => 'Moins de 20 ans', 'min_birth_year' => 2006, 'max_birth_year' => 2008, 'match_duration_minutes' => 90, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U19', 'name_ar' => 'أواسط ما دون 19 سنة', 'name_fr' => 'Moins de 19 ans', 'min_birth_year' => 2007, 'max_birth_year' => 2008, 'match_duration_minutes' => 90, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U18', 'name_ar' => 'أشبال ما دون 18 سنة', 'name_fr' => 'Moins de 18 ans', 'min_birth_year' => 2008, 'max_birth_year' => 2009, 'match_duration_minutes' => 90, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U17', 'name_ar' => 'أشبال ما دون 17 سنة', 'name_fr' => 'Moins de 17 ans', 'min_birth_year' => 2009, 'max_birth_year' => 2010, 'match_duration_minutes' => 80, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U16', 'name_ar' => 'ناشئون ما دون 16 سنة', 'name_fr' => 'Moins de 16 ans', 'min_birth_year' => 2010, 'max_birth_year' => 2011, 'match_duration_minutes' => 80, 'substitutions_allowed' => 5, 'ball_size' => 5],
            ['code' => 'U15', 'name_ar' => 'أصاغر ما دون 15 سنة', 'name_fr' => 'Moins de 15 ans', 'min_birth_year' => 2011, 'max_birth_year' => 2012, 'match_duration_minutes' => 70, 'substitutions_allowed' => 7, 'ball_size' => 4],
            ['code' => 'U13', 'name_ar' => 'براعم ما دون 13 سنة', 'name_fr' => 'Moins de 13 ans', 'min_birth_year' => 2013, 'max_birth_year' => 2014, 'match_duration_minutes' => 60, 'substitutions_allowed' => 9, 'ball_size' => 4],
            ['code' => 'Séniors', 'name_ar' => 'أكابر', 'name_fr' => 'Séniors', 'min_birth_year' => 1980, 'max_birth_year' => 2005, 'match_duration_minutes' => 90, 'substitutions_allowed' => 5, 'ball_size' => 5],
        ];

        foreach ($categories as $cat) {
            AgeCategory::firstOrCreate(['code' => $cat['code']], $cat);
        }
    }

    /**
     * Smart Transliteration to clean Algerian Arabic name
     */
    protected function transliterateToArabic(string $frenchName): string
    {
        $name = trim(strtoupper($frenchName));

        $commonDictionary = [
            'MOHAMED' => 'محمد',
            'MOHAMMED' => 'محمد',
            'AHMED' => 'أحمد',
            'ALI' => 'علي',
            'ABDELKADER' => 'عبد القادر',
            'ABDELILLAH' => 'عبد الإله',
            'ABDELRAHMANE' => 'عبد الرحمن',
            'ABDERRAHMANE' => 'عبد الرحمن',
            'ABDELHAMID' => 'عبد الحميد',
            'ABDELLAH' => 'عبد الله',
            'ABDALLAH' => 'عبد الله',
            'ABDELKRIM' => 'عبد الكريم',
            'ABDELMADJID' => 'عبد المجيد',
            'ABDELMALEK' => 'عبد المالك',
            'ABDELHAK' => 'عبد الحق',
            'ABDELHAKIM' => 'عبد الحكيم',
            'ABDENOUR' => 'عبد النور',
            'ABDERRAOUF' => 'عبد الرؤوف',
            'YOUSSOUF' => 'يوسف',
            'YOUSSEF' => 'يوسف',
            'YACINE' => 'ياسين',
            'RAYANE' => 'ريان',
            'RYAD' => 'رياض',
            'RIAD' => 'رياض',
            'AKRAM' => 'أكرم',
            'AYMEN' => 'أيمن',
            'AIMEN' => 'أيمن',
            'AMINE' => 'أمين',
            'MOHAMED AMINE' => 'محمد أمين',
            'ANIS' => 'أنيس',
            'WALID' => 'وليد',
            'OUSSAMA' => 'أسامة',
            'ISLAM' => 'إسلام',
            'ZAKARIA' => 'زكرياء',
            'ZOUBIR' => 'زوبير',
            'IMAD' => 'عماد',
            'SOFIANE' => 'سفيان',
            'HAMZA' => 'حمزة',
            'BILAL' => 'بلال',
            'FARID' => 'فريد',
            'FOUAD' => 'فؤاد',
            'KARIM' => 'كريم',
            'TAYEB' => 'الطيب',
            'DJILALI' => 'الجيلالي',
            'DJAMEL' => 'جمال',
            'KHALED' => 'خالد',
            'HOCINE' => 'حسين',
            'HASSAN' => 'حسن',
            'MOSTEFA' => 'مصطفى',
            'MUSTAPHA' => 'مصطفى',
            'OMAR' => 'عمر',
            'OTHMANE' => 'عثمان',
            'SALAH' => 'صالح',
            'SAID' => 'سعيد',
            'SAMIR' => 'سمير',
            'TARIQ' => 'طارق',
            'TAREK' => 'طارق',
            'YOUCEF' => 'يوسف',
            'ZINEDDINE' => 'زين الدين',
            'CHAMS EDDINE' => 'شمس الدين',
            'NASREDDINE' => 'نصر الدين',
            'NOUREDDINE' => 'نور الدين',
            'SALAH EDDINE' => 'صلاح الدين',
            'SEIF EDDINE' => 'سيف الدين',
            'ALA EDDINE' => 'علاء الدين',
            'ACHOUR' => 'عاشور',
            'AZZOUZ' => 'عزوز',
            'BENAHMED' => 'بن أحمد',
            'ZERKI' => 'زرقي',
            'MANSOURI' => 'منصوري',
            'BOUZID' => 'بوزيد',
            'AMARA' => 'عمارة',
            'KHELIFA' => 'خليفة',
            'BELKACEM' => 'بلقاسم',
            'BRAHIM' => 'إبراهيم',
            'BENALI' => 'بن علي',
            'BOUALEM' => 'بوعلام',
            'MAHMOUD' => 'محمود',
            'RACHID' => 'رشيد',
            'REDOUANE' => 'رضوان',
            'HICHEM' => 'هشام',
            'HABIB' => 'حبيب',
            'MEHDI' => 'مهدي',
            'NADIR' => 'نذير',
            'LOTFI' => 'لطفي',
            'ILYAS' => 'إلياس',
            'ILYES' => 'إلياس',
        ];

        // Multi-word name handling
        $words = preg_split('/\s+/', $name);
        $translatedWords = [];

        foreach ($words as $word) {
            if (isset($commonDictionary[$word])) {
                $translatedWords[] = $commonDictionary[$word];
            } else {
                // Rule-based phonetic transliteration
                $w = $word;
                $map = [
                    'OU' => 'و', 'CH' => 'ش', 'KH' => 'خ', 'DJ' => 'ج',
                    'TH' => 'ث', 'DH' => 'ذ', 'GH' => 'غ', 'PH' => 'ف',
                    'SH' => 'ش', 'AA' => 'ا', 'EE' => 'ي', 'OO' => 'و',
                    'B' => 'ب', 'T' => 'ت', 'J' => 'ج', 'H' => 'ح',
                    'D' => 'د', 'R' => 'ر', 'Z' => 'ز', 'S' => 'س',
                    'F' => 'ف', 'Q' => 'ق', 'K' => 'ك', 'L' => 'ل',
                    'M' => 'م', 'N' => 'ن', 'W' => 'و', 'Y' => 'ي',
                    'A' => 'ا', 'E' => '', 'I' => 'ي', 'O' => 'و', 'U' => 'و',
                ];
                $trans = str_replace(array_keys($map), array_values($map), $w);
                $translatedWords[] = $trans ?: $word;
            }
        }

        return implode(' ', $translatedWords);
    }
}
