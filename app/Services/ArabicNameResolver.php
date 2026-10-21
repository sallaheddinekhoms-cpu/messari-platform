<?php

namespace App\Services;

class ArabicNameResolver
{
    /**
     * Dictionary of Algerian First Names (French -> Arabic)
     */
    protected static array $firstNames = [
        // Mohamed variations & compounds
        'MOHAMED' => 'محمد',
        'MOHAMMED' => 'محمد',
        'MOHAMED AMINE' => 'محمد الأمين',
        'MOHAMMED AMINE' => 'محمد الأمين',
        'MOHAMED EL AMINE' => 'محمد الأمين',
        'MOHAMMED EL AMINE' => 'محمد الأمين',
        'MOHAMED ELAMINE' => 'محمد الأمين',
        'MOHAMED LAMINE' => 'محمد الأمين',
        'MOHAMMED LAMINE' => 'محمد الأمين',
        'MOHAMED ISLAM' => 'محمد إسلام',
        'MOHAMMED ISLAM' => 'محمد إسلام',
        'MOHAMED WASSIM' => 'محمد وسيم',
        'MOHAMED EL MEHDI' => 'محمد المهدي',
        'MOHAMED YACINE' => 'محمد ياسين',
        'MOHAMED ALI' => 'محمد علي',
        'MOHAMED RAYANE' => 'محمد ريان',
        'MOHAMED REDA' => 'محمد رضا',
        'MOHAMED ANIS' => 'محمد أنيس',
        'MOHAMED IYAD' => 'محمد إياد',
        'MOHAMED ZAKARIA' => 'محمد زكرياء',
        'MOHAMED AKRAM' => 'محمد أكرم',
        'MOHAMED AYMEN' => 'محمد أيمن',
        'MOHAMED SOUHIL' => 'محمد سهيل',
        'MOHAMED CHERIF' => 'محمد الشريف',
        'MOHAMED SAID' => 'محمد سعيد',
        'MOHAMED TAHAR' => 'محمد الطاهر',
        'MOHAMED FOUAD' => 'محمد فؤاد',
        'MOHAMED EL HADI' => 'محمد الهادي',
        'MOHAMED FADI' => 'محمد فادي',
        'MOHAMED NADIR' => 'محمد نذير',
        'MOHAMED YAZID' => 'محمد يزيد',
        'MOHAMED FAROUK' => 'محمد فاروق',
        'MOHAMED ADEL' => 'محمد عادل',
        'MOHAMED MEHDI' => 'محمد مهدي',
        'MOHAMED WALID' => 'محمد وليد',
        'MOHAMED ZINEDDINE' => 'محمد زين الدين',
        'MOHAMED ABDELILLAH' => 'محمد عبد الإله',

        // Sid compounds
        'SID AHMED' => 'سيد أحمد',
        'SID ALI' => 'سيد علي',
        'SIDI MOHAMED' => 'سيدي محمد',

        // A names
        'AHMED' => 'أحمد',
        'ALI' => 'علي',
        'AYOUB' => 'أيوب',
        'ADEM' => 'آدم',
        'ADAM' => 'آدم',
        'ANES' => 'أنس',
        'ANAS' => 'أنس',
        'ANIS' => 'أنيس',
        'AKRAM' => 'أكرم',
        'AMINE' => 'أمين',
        'EL AMINE' => 'الأمين',
        'ELAMINE' => 'الأمين',
        'LAMINE' => 'الأمين',
        'AYMEN' => 'أيمن',
        'AIMEN' => 'أيمن',
        'AMJED' => 'أمجد',
        'AMDJED' => 'أمجد',
        'ADEL' => 'عادل',
        'AZIZ' => 'عزيز',
        'ALA' => 'علاء',
        'ALAA' => 'علاء',
        'ALLAL' => 'علال',
        'AMAR' => 'عمار',
        'AMARA' => 'عمارة',
        'ABID' => 'عابد',
        'ASSEM' => 'عاصم',
        'AISSA' => 'عيسى',
        'AYHAM' => 'أيهم',
        'ASSIL' => 'أصيل',
        'AFAF' => 'عفاف',
        'ACHRAF' => 'أشرف',
        'ACHRAF YOUCEF' => 'أشرف يوسف',
        'AZZEDDINE' => 'عز الدين',
        'ALAEDDINE' => 'علاء الدين',
        'ALA EDDINE' => 'علاء الدين',
        'ABDELHADI' => 'عبد الهادي',
        'ABDELBAKI' => 'عبد الباقي',
        'ABDERRAHMANE' => 'عبد الرحمن',
        'ABDERAHMANE' => 'عبد الرحمن',
        'ABDERRAHMENE' => 'عبد الرحمن',
        'ABDELKADER' => 'عبد القادر',
        'ABDELKRIM' => 'عبد الكريم',
        'ABDELLAH' => 'عبد الله',
        'ABDALLAH' => 'عبد الله',
        'ABDELMALIK' => 'عبد المالك',
        'ABDELMALEK' => 'عبد المالك',
        'ABDERRAHIM' => 'عبد الرحيم',
        'ABDERRAOUF' => 'عبد الرؤوف',
        'ABDELDJALIL' => 'عبد الجليل',
        'ABDELJALIL' => 'عبد الجليل',
        'ABDELHAMID' => 'عبد الحميد',
        'ABDELHAK' => 'عبد الحق',
        'ABDELHAKIM' => 'عبد الحكيم',
        'ABDELMADJID' => 'عبد المجيد',
        'ABDENOUR' => 'عبد النور',
        'ABDENNOUR' => 'عبد النور',
        'ABDERREZAK' => 'عبد الرزاق',
        'ABDERREZZAK' => 'عبد الرزاق',
        'ABDESSAMED' => 'عبد الصمد',
        'ABDESSLEM' => 'عبد السلام',
        'ABDESLAM' => 'عبد السلام',
        'ABDESSAMIA' => 'عبد السميع',
        'ABDELBASSET' => 'عبد الباسط',
        'ABDELFETTAH' => 'عبد الفتاح',
        'ABDELWAHEB' => 'عبد الوهاب',
        'ABDELOUAHAB' => 'عبد الوهاب',
        'ABDELOUAHED' => 'عبد الواحد',
        'ABDELMOUMENE' => 'عبد المؤمن',
        'ABD EL MOUMENE' => 'عبد المؤمن',
        'ABDELAZIZ' => 'عبد العزيز',
        'ABDELGHANI' => 'عبد الغني',
        'ABDELGHAFOUR' => 'عبد الغفور',
        'ABDELILLAH' => 'عبد الإله',
        'ABDELILAH' => 'عبد الإله',
        'ABDENNASSER' => 'عبد الناصر',

        // B names
        'BILAL' => 'بلال',
        'BELKACEM' => 'بلقاسم',
        'BRAHIM' => 'إبراهيم',
        'BOUALEM' => 'بوعلام',
        'BOUBEKEUR' => 'أبو بكر',
        'BOUMEDIENE' => 'بومدين',
        'BADIS' => 'باديس',
        'BADREDDINE' => 'بدر الدين',
        'BADR EDDINE' => 'بدر الدين',
        'BACHIR' => 'بشير',
        'BILLEL' => 'بلال',
        'BASSEM' => 'باسم',
        'BASSIM' => 'باسم',

        // C / Ch names
        'CHEMS EDDINE' => 'شمس الدين',
        'CHAMSEDDINE' => 'شمس الدين',
        'CHEIKH' => 'الشيخ',
        'CHERIF' => 'شريف',
        'CHAKIB' => 'شكيب',
        'CHAWKI' => 'شوقي',
        'CHEFIK' => 'شفيق',

        // D / Dj names
        'DJILALI' => 'جيلالي',
        'DJAMEL' => 'جمال',
        'DJABER' => 'جابر',
        'DJAFAR' => 'جعفر',
        'DJAMEL EDDINE' => 'جمال الدين',
        'DJOUADI' => 'جوادي',
        'DJAOUED' => 'جواد',
        'DJIHAD' => 'جهاد',
        'DHIYA EDDINE' => 'ضياء الدين',

        // F names
        'FOUAD' => 'فؤاد',
        'FARID' => 'فريد',
        'FATEH' => 'فاتح',
        'FARES' => 'فارس',
        'FAROUK' => 'فاروق',
        'FAYCAL' => 'فيصل',
        'FAOUZI' => 'فوزي',
        'FADI' => 'فادي',
        'FAHIM' => 'فهيم',

        // H names
        'HAMZA' => 'حمزة',
        'HOCINE' => 'حسين',
        'HOUSSAM' => 'حسام',
        'HOUSSEM' => 'حسام',
        'HASSAN' => 'حسن',
        'HASSANE' => 'حسان',
        'HACENE' => 'أحسن',
        'HAITHEM' => 'هيثم',
        'HAITEM' => 'هيثم',
        'HABIB' => 'حبيب',
        'HICHEM' => 'هشام',
        'HAKIM' => 'حكيم',
        'HALIM' => 'حليم',
        'HAROUN' => 'هارون',
        'HOUARI' => 'هواري',
        'HAMID' => 'حميد',
        'HAFID' => 'حفيظ',

        // I names
        'ISLAM' => 'إسلام',
        'ISHAK' => 'إسحاق',
        'ISHAQ' => 'إسحاق',
        'IYAD' => 'إياد',
        'IBRAHIM' => 'إبراهيم',
        'ISMAIL' => 'إسماعيل',
        'ILYAS' => 'إلياس',
        'ILYES' => 'إلياس',
        'IDRIS' => 'إدريس',
        'IDRISS' => 'إدريس',
        'IMAD' => 'عماد',
        'IMAD EDDINE' => 'عماد الدين',
        'IMADEDDINE' => 'عماد الدين',
        'ISSAM' => 'عصام',
        'IKHLAS' => 'إخلاص',

        // K names
        'KHALIL' => 'خليل',
        'KHALED' => 'خالد',
        'KARIM' => 'كريم',
        'KAMEL' => 'كامل',
        'KHEIREDDINE' => 'خير الدين',
        'KADDOUR' => 'قدور',
        'KOUIDER' => 'قويدر',

        // L names
        'LOTFI' => 'لطفي',
        'LOUNIS' => 'لونيس',
        'LOQMANE' => 'لقمان',
        'LOUAY' => 'لؤي',
        'LARBI' => 'العربي',

        // M names
        'MEHDI' => 'مهدي',
        'MAHMOUD' => 'محمود',
        'MAROUANE' => 'مروان',
        'MEROUANE' => 'مروان',
        'MOUNIR' => 'منير',
        'MOURAD' => 'مراد',
        'MOUSTAPHA' => 'مصطفى',
        'MOSTEFA' => 'مصطفى',
        'MUSTAPHA' => 'مصطفى',
        'MAAMAR' => 'معمر',
        'MOKHTAR' => 'مختار',
        'MANSOUR' => 'منصور',
        'MADJID' => 'مجيد',
        'MALIK' => 'مالك',
        'MESSAOUD' => 'مسعود',
        'MAHFOUD' => 'محفوظ',
        'MILOUD' => 'ميلود',
        'MOUSSAB' => 'مصعب',
        'MOUADH' => 'معاذ',
        'MONCEF' => 'منصف',

        // N names
        'NADIR' => 'نذير',
        'NABIL' => 'نبيل',
        'NAIM' => 'نعيم',
        'NASREDDINE' => 'نصر الدين',
        'NASR EDDINE' => 'نصر الدين',
        'NOUREDDINE' => 'نور الدين',
        'NOUR EDDINE' => 'نور الدين',
        'NAZIM' => 'ناظم',
        'NAOUFEL' => 'نوفل',
        'NACER' => 'ناصر',
        'NEDJM EDDINE' => 'نجم الدين',

        // O names
        'OUSSAMA' => 'أسامة',
        'OMAR' => 'عمر',
        'OMAR ELFAROUK' => 'عمر الفاروق',
        'OMAR EL FAROUK' => 'عمر الفاروق',
        'OTHMANE' => 'عثمان',
        'OKBA' => 'عقبة',

        // R names
        'RAYANE' => 'ريان',
        'RYAD' => 'رياض',
        'RIAD' => 'رياض',
        'RAFIK' => 'رفيق',
        'REDA' => 'رضا',
        'REDOUANE' => 'رضوان',
        'RACHID' => 'رشيد',
        'RAMI' => 'رامي',
        'RAMZI' => 'رمزي',
        'ROCHDI' => 'رشدي',

        // S names
        'SOFIANE' => 'سفيان',
        'SAMIR' => 'سمير',
        'SALAH' => 'صالح',
        'SALAH EDDINE' => 'صلاح الدين',
        'SALAHEDDINE' => 'صلاح الدين',
        'SAID' => 'سعيد',
        'SAMI' => 'سامي',
        'SABRI' => 'صبري',
        'SABRI AYOUB' => 'صبري أيوب',
        'SEIF EDDINE' => 'سيف الدين',
        'SEIFEDDINE' => 'سيف الدين',
        'SEYF EL ISLAM' => 'سيف الإسلام',
        'SLIMANE' => 'سليمان',
        'SOUHIL' => 'سهيل',
        'SOHEIB' => 'صهيب',
        'SOHAIB' => 'صهيب',
        'SEDDIK' => 'صديق',

        // T names
        'TAREK' => 'طارق',
        'TARIQ' => 'طارق',
        'TOUFIK' => 'توفيق',
        'TAYEB' => 'الطيب',
        'TAHA' => 'طه',
        'TEDJANI' => 'تيجاني',
        'TAKIEDDINE' => 'تقي الدين',

        // W names
        'WALID' => 'وليد',
        'WASSIM' => 'وسيم',
        'WAIL' => 'وائل',

        // Y names
        'YOUCEF' => 'يوسف',
        'YOUNES' => 'يونس',
        'YACINE' => 'ياسين',
        'YASSINE' => 'ياسين',
        'YASSER' => 'ياسر',
        'YAHIA' => 'يحيى',
        'YAHIA YACINE' => 'يحيى ياسين',
        'YAZID' => 'يزيد',

        // Z names
        'ZAKARIA' => 'زكرياء',
        'ZAKARYA' => 'زكرياء',
        'ZAKARIA ABDELBASSET' => 'زكرياء عبد الباسط',
        'ZINEDDINE' => 'زين الدين',
        'ZIN EDDINE' => 'زين الدين',
        'ZOUBIR' => 'زوبير',
        'ZIAD' => 'زياد',
    ];

    /**
     * Dictionary of Algerian Surnames (Family names)
     */
    protected static array $lastNames = [
        'BRAHIMI' => 'براهمي',
        'RAHMANI' => 'رحماني',
        'MOKHTARI' => 'مختاري',
        'BOUBEKEUR' => 'أبو بكر',
        'LARBI' => 'العربي',
        'TOUIL' => 'طويل',
        'ZOUAOUI' => 'زواوي',
        'TOUMI' => 'تومي',
        'MANSOUR' => 'منصور',
        'MANSOURI' => 'منصوري',
        'SLIMANI' => 'سليماني',
        'CHERGUI' => 'شرقي',
        'HAMIDI' => 'حميدي',
        'ABED' => 'عابد',
        'AMARA' => 'عمارة',
        'ZAOUI' => 'زاوي',
        'BENSAID' => 'بن سعيد',
        'SAIDI' => 'سعيدي',
        'YAHIAOUI' => 'يحياوي',
        'MOUSSAOUI' => 'موساوي',
        'ARAB' => 'عرب',
        'BOURAS' => 'بوراس',
        'GHEZAL' => 'غزال',
        'CHERIFI' => 'شريفي',
        'OUALI' => 'وعلي',
        'GACEM' => 'قاسم',
        'TALEB' => 'طالب',
        'BEN BOUZID' => 'بن بوزيد',
        'BOUZID' => 'بوزيد',
        'MAHMOUDI' => 'محمودي',
        'SAIDANI' => 'صيداني',
        'KHELIFA' => 'خليفة',
        'TAIBI' => 'طايبي',
        'BOUKHALFA' => 'بوخالفة',
        'YAGOUB' => 'يعقوب',
        'HAMDANI' => 'حمداني',
        'AZZOUZ' => 'عزوز',
        'DAHMANI' => 'دحماني',
        'BENAMEUR' => 'بن عامر',
        'MERZOUG' => 'مرزوق',
        'MARZOUG' => 'مرزوق',
        'ACHOUR' => 'عاشور',
        'ZERKI' => 'زرقي',
        'ZERGANE' => 'زرقان',
        'SEDIKI' => 'صديقي',
        'SEDIK' => 'صديق',
        'KECHIDA' => 'كشيدة',
        'KANACHE' => 'قناش',
        'OUZOUGZOU' => 'أوزوقزو',
        'DRICI' => 'دريسي',
        'TERRA' => 'ترا',
        'FERHOUL' => 'فرحول',
        'MILIANI' => 'ملياني',
        'AISSAT' => 'عيسات',
        'AISSA' => 'عيسى',
        'BENBELAICHE' => 'بن بلعيش',
        'BEN ATTIA' => 'بن عطية',
        'BRAKNI' => 'براكني',
        'DRAHMANI' => 'درحماني',
        'MELOUKI' => 'ملوكي',
        'CHAATANE' => 'شعبان',
        'BENAROUS' => 'بن عروس',
        'ABDI' => 'عبدي',
        'AMMAD' => 'عماد',
        'BENMEZIANE' => 'بن مزيان',
        'BERBER' => 'بربر',
        'BISKRI' => 'بسكرة',
        'BOULESNAM' => 'بولصنام',
        'BOURGHAYA' => 'بورغاية',
        'FERGANI' => 'فرقاني',
        'KERBOUCI' => 'كربوسي',
        'KORCHI' => 'قرشي',
        'TEDJANI' => 'تيجاني',
        'NOURINE MAAMAR' => 'نورين معمر',
        'BEN MAILOUD' => 'بن ميلود',
        'KOUL' => 'كول',
        'MORSLI' => 'مرسلي',
        'GUENDOUZ' => 'قندوز',
        'KADRI' => 'قادري',
        'KOUIDER' => 'قويدر',
        'BELHADJ' => 'بلحاج',
        'MEDDAH' => 'مداح',
        'BEKKOUCHE' => 'بكوش',
        'DJABALLAH' => 'جاب الله',
        'MEZIANE' => 'مزيان',
        'MEZIANI' => 'مزياني',
        'BOUTALEB' => 'بوطالب',
        'SAHRAOUI' => 'صحراوي',
        'BENYOUCEF' => 'بن يوسف',
        'BOUDIAF' => 'بوضياف',
        'BENALIA' => 'بن علية',
        'BOUZIANE' => 'بوزيان',
        'ALLAOUI' => 'علاوي',
        'BEZZAZ' => 'بزاز',
        'BOUCHAMA' => 'بوشامة',
        'CHAIB' => 'شايب',
        'HAMZAOUI' => 'حمزاوي',
        'HADDAD' => 'حداد',
        'BENCHERIF' => 'بن شريف',
        'BOUCHACHI' => 'بوشاشي',
        'KHELIFI' => 'خليفي',
        'MAATALLAH' => 'معطى الله',
        'SADOK' => 'صادق',
        'TALBI' => 'طالبي',
        'TOUATI' => 'تواتي',
        'BENHAMADA' => 'بن حمادة',
        'ALLICHE' => 'عليش',
        'TEBANI' => 'تيباني',
        'KADAI' => 'قداي',
        'ANNOUN' => 'عنون',
        'KRIM' => 'كريم',
        'KELLAL' => 'قلال',
        'BELABBES' => 'بلعباس',
        'CHAREF' => 'شارف',
        'CHOUBIL' => 'شوبيل',
        'MANCER' => 'منصر',
        'DJERIBA' => 'جريبة',
        'BERROUILA' => 'برويلة',
        'BOUKHADRA' => 'بوخضرة',
        'BOUZIDAOUI' => 'بوزيداوي',
        'DEKANI' => 'دقاني',
        'FKAIR' => 'فقير',
        'LARIBI' => 'لعريبي',
        'MAKHLOUFI' => 'مخلوفي',
        'KARA' => 'قارة',
        'SBA' => 'سبع',
    ];

    /**
     * Resolve First Name (French -> Arabic)
     */
    public static function resolveFirstName(string $french): string
    {
        $clean = strtoupper(trim(preg_replace('/\s+/', ' ', $french)));
        if (empty($clean) || $clean === '00' || $clean === '0') {
            return '—';
        }

        // Direct exact match
        if (isset(static::$firstNames[$clean])) {
            return static::$firstNames[$clean];
        }

        // Compound name pre-normalization
        $clean = preg_replace('/^ABD\s+EL\s+/i', 'ABDEL', $clean);
        $clean = preg_replace('/\s+ABD\s+EL\s+/i', ' ABDEL', $clean);
        $clean = preg_replace('/^ABD\s+AL\s+/i', 'ABDEL', $clean);
        $clean = preg_replace('/\s+ABD\s+AL\s+/i', ' ABDEL', $clean);

        if (isset(static::$firstNames[$clean])) {
            return static::$firstNames[$clean];
        }

        // Check if multi-word name
        $parts = explode(' ', $clean);
        if (count($parts) > 1) {
            $resolvedParts = [];
            foreach ($parts as $p) {
                $resolvedParts[] = static::resolveSingleToken($p, true);
            }
            return implode(' ', $resolvedParts);
        }

        return static::resolveSingleToken($clean, true);
    }

    /**
     * Resolve Surname / Last Name (French -> Arabic)
     */
    public static function resolveLastName(string $french): string
    {
        $clean = strtoupper(trim(preg_replace('/\s+/', ' ', $french)));
        if (empty($clean) || $clean === '00' || $clean === '0') {
            return '—';
        }

        // Direct exact match
        if (isset(static::$lastNames[$clean])) {
            return static::$lastNames[$clean];
        }

        // Check prefixes like BEN, BOU, BEL, AIT, EL
        if (str_starts_with($clean, 'BEN ') || (str_starts_with($clean, 'BEN') && strlen($clean) > 3)) {
            $rest = str_starts_with($clean, 'BEN ') ? substr($clean, 4) : substr($clean, 3);
            return 'بن ' . static::resolveLastName($rest);
        }

        if (str_starts_with($clean, 'BOU ') || (str_starts_with($clean, 'BOU') && strlen($clean) > 3)) {
            $rest = str_starts_with($clean, 'BOU ') ? substr($clean, 4) : substr($clean, 3);
            return 'بو ' . static::resolveLastName($rest);
        }

        if (str_starts_with($clean, 'BEL ') || (str_starts_with($clean, 'BEL') && strlen($clean) > 3)) {
            $rest = str_starts_with($clean, 'BEL ') ? substr($clean, 4) : substr($clean, 3);
            return 'بلـ' . static::resolveLastName($rest);
        }

        if (str_starts_with($clean, 'AIT ') || str_starts_with($clean, 'AIT')) {
            $rest = str_starts_with($clean, 'AIT ') ? substr($clean, 4) : substr($clean, 3);
            return 'آيت ' . static::resolveLastName($rest);
        }

        if (str_starts_with($clean, 'EL ') || str_starts_with($clean, 'AL ')) {
            $rest = substr($clean, 3);
            return 'الـ' . static::resolveLastName($rest);
        }

        // Multi-word surname
        $parts = explode(' ', $clean);
        if (count($parts) > 1) {
            $resolvedParts = [];
            foreach ($parts as $p) {
                $resolvedParts[] = static::resolveSingleToken($p, false);
            }
            return implode(' ', $resolvedParts);
        }

        return static::resolveSingleToken($clean, false);
    }

    /**
     * Advanced Algerian phonetic transliteration for unmapped tokens
     */
    protected static function resolveSingleToken(string $token, bool $isFirstName): string
    {
        $token = strtoupper(trim($token));

        // Check in either dictionary first
        if ($isFirstName && isset(static::$firstNames[$token])) {
            return static::$firstNames[$token];
        }
        if (isset(static::$lastNames[$token])) {
            return static::$lastNames[$token];
        }
        if (isset(static::$firstNames[$token])) {
            return static::$firstNames[$token];
        }

        // Handle specific prefixes like ABDEL...
        if (str_starts_with($token, 'ABDEL') || str_starts_with($token, 'ABDER') || str_starts_with($token, 'ABDES') || str_starts_with($token, 'ABDEN')) {
            $sub = substr($token, 5);
            return 'عبد الـ' . static::resolveSingleToken($sub, false);
        }

        // Handle suffix ...EDDINE / ...DINE
        if (str_ends_with($token, 'EDDINE')) {
            $prefix = substr($token, 0, -6);
            return static::resolveSingleToken($prefix, $isFirstName) . ' الدين';
        }
        if (str_ends_with($token, 'DINE')) {
            $prefix = substr($token, 0, -4);
            return static::resolveSingleToken($prefix, $isFirstName) . ' الدين';
        }

        // Rule-based phonetic transformation for Algerian French orthography
        $w = $token;

        // Specific digraphs & trigraphs
        $patterns = [
            'DJA' => 'جا', 'DJE' => 'ج', 'DJI' => 'جي', 'DJO' => 'جو', 'DJU' => 'جو', 'DJ' => 'ج',
            'TCH' => 'تش',
            'CH' => 'ش',
            'KH' => 'خ',
            'GH' => 'غ',
            'TH' => 'ث',
            'DH' => 'ذ',
            'OU' => 'و',
            'AA' => 'ا',
            'EE' => 'ي',
            'OO' => 'و',
            'EI' => 'ي',
            'AI' => 'اي',
            'AY' => 'أي',
            'PH' => 'ف',
            'SH' => 'ش',
            'SS' => 'س',
            'TT' => 'ت',
            'BB' => 'ب',
            'MM' => 'م',
            'NN' => 'ن',
            'RR' => 'ر',
            'LL' => 'ل',
            'FF' => 'ف',
            'GG' => 'ق',
            'CC' => 'ك',
            'PP' => 'ب',
            'ZZ' => 'ز',
        ];
        $w = str_replace(array_keys($patterns), array_values($patterns), $w);

        // Soft C before E, I
        $w = preg_replace('/C(?=[E|I|Y|ي])/i', 'س', $w);
        $w = str_replace('C', 'ك', $w);

        // Algerian G is usually Qaf (ق)
        $w = str_replace(['G', 'Q'], 'ق', $w);

        // Single letters
        $single = [
            'B' => 'ب', 'T' => 'ت', 'J' => 'ج', 'H' => 'ح',
            'D' => 'د', 'R' => 'ر', 'Z' => 'ز', 'S' => 'س',
            'F' => 'ف', 'K' => 'ك', 'L' => 'ل', 'M' => 'م',
            'N' => 'ن', 'W' => 'و', 'Y' => 'ي', 'X' => 'كس',
            'V' => 'ف', 'P' => 'ب',
            'A' => 'ا', 'E' => '', 'I' => 'ي', 'O' => 'و', 'U' => 'و',
        ];
        $w = str_replace(array_keys($single), array_values($single), $w);

        // Clean any stray non-Arabic characters
        $w = preg_replace('/[A-Z0-9]/i', '', $w);
        $w = preg_replace('/\s+/', ' ', $w);

        // Polish starting Alif
        if (str_starts_with($w, 'ا') && strlen($w) > 2 && $isFirstName) {
            $w = 'أ' . substr($w, 2); // 2 bytes for UTF-8 'ا'
        }

        return trim($w) ?: $token;
    }
}
