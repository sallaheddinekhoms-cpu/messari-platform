<?php

namespace Database\Seeders;

use App\Models\CoachCourse;
use App\Models\CoachCourseMaterial;
use Illuminate\Database\Seeder;

class CoachCourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'course_type' => 'goalkeeper',
                'title' => 'دورة تكوين مدربي حراس المرمى — الدفعة الأولى 2026/2027',
                'code' => 'DTR-GK-2026-01',
                'description' => 'دورة تكوينية متخصصة في تأهيل وتطوير مدربي حراس المرمى للفئات الشبانية، تحت إشراف المديرية التقنية الجهوية لرابطة البليدة وبالتنسيق مع المديرية الفنية الوطنية (DTN).',
                'prerequisites' => 'حارس مرمى سابق في صفوف الأندية الرسمية، ومستوى دراسي السنة الأولى ثانوي على الأقل.',
                'required_documents' => "1. طلب خطي للمشاركة في التكوين.\n2. صورتان شمسيتان.\n3. نسخة من بطاقة التعريف الوطنية.\n4. نسخة من رخصة حارس مرمى سابق.\n5. شهادة طبية عامة وشهادة طبية لأمراض الصدر.\n6. شهادة السوابق العدلية (صحيفة رقم 03).\n7. شهادة الإقامة.\n8. شهادة الميلاد.\n9. شهادة مدرسية تثبت المستوى الدراسي السنة الأولى ثانوي على الأقل.",
                'start_date' => '2026-11-15',
                'end_date' => '2026-11-28',
                'registration_deadline' => '2026-11-05',
                'location' => 'المركب الرياضي مصطفى تشاكر - البليدة وقاعة المحاضرات للرابطة',
                'max_candidates' => 30,
                'fee_amount' => 15000.00,
                'is_active' => true,
                'is_registration_open' => true,
            ],
            [
                'course_type' => 'faf1',
                'title' => 'دورة إجازة التدريب الفيدرالية المستوى الأول (FAF 1) — الدفعة الثانية',
                'code' => 'DTR-FAF1-2026-02',
                'description' => 'المستوى التأسيسي لمدربي الفئات الصغرى والبراعم (U11 إلى U15)، يركز على المبادئ البيداغوجية، تنشيط التداريب الرياضية، وقواعد السلامة.',
                'prerequisites' => 'السن 19 سنة على الأقل، ممارسة كرة القدم كلاعب سابق، ومستوى دراسي 4 متوسط أو 1 ثانوي.',
                'required_documents' => "1. طلب خطي.\n2. صورتان شمسيتان.\n3. نسخة من بطاقة التعريف الوطنية.\n4. شهادة طبية عامة وصدرية.\n5. صحيفة السوابق العدلية رقم 03.\n6. شهادة الإقامة والميلاد.\n7. شهادة مدرسية.",
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-12',
                'registration_deadline' => '2026-11-20',
                'location' => 'مركز النخبة الجهوي - تيبازة',
                'max_candidates' => 35,
                'fee_amount' => 12000.00,
                'is_active' => true,
                'is_registration_open' => true,
            ],
            [
                'course_type' => 'faf2',
                'title' => 'دورة إجازة التدريب الفيدرالية المستوى الثاني (FAF 2)',
                'code' => 'DTR-FAF2-2026-01',
                'description' => 'التكوين الموجه لمدربي الفئات الشبانية للأشبال والأواسط (U17, U19) مع تعميق المفاهيم التكتيكية والإعداد البدني الخاص.',
                'prerequisites' => 'حيازة شهادة FAF 1 مع إثبات سنة واحدة على الأقل من الممارسة الميدانية كمدرب معتمد.',
                'required_documents' => "1. طلب خطي.\n2. نسخة من شهادة FAF 1.\n3. شهادة ممارسة التدريب لموسم كامل.\n4. الملف الطبي والإداري الكامل.",
                'start_date' => '2026-12-15',
                'end_date' => '2026-12-28',
                'registration_deadline' => '2026-12-01',
                'location' => 'المعهد الوطني للتكوين الرياضي - عين الدفلى',
                'max_candidates' => 30,
                'fee_amount' => 18000.00,
                'is_active' => true,
                'is_registration_open' => true,
            ],
            [
                'course_type' => 'faf3',
                'title' => 'دورة إجازة التدريب الفيدرالية المستوى الثالث (FAF 3)',
                'code' => 'DTR-FAF3-2026-01',
                'description' => 'المستوى المتقدم المؤهل للإشراف على فرق الأكابر في الأقسام الشرفية والجهوية وإدارة الإعداد البدني التخصصي.',
                'prerequisites' => 'حيازة شهادة FAF 2 مع سنتين من الممارسة الميدانية الموثقة.',
                'required_documents' => "1. ملف إداري كامل.\n2. نسخة من شهادة FAF 2.\n3. تقرير النشاط الميداني التدريبي.",
                'start_date' => '2027-01-10',
                'end_date' => '2027-01-25',
                'registration_deadline' => '2026-12-20',
                'location' => 'قاعة المحاضرات بالرابطة الجهوية - البليدة',
                'max_candidates' => 25,
                'fee_amount' => 22000.00,
                'is_active' => true,
                'is_registration_open' => true,
            ],
            [
                'course_type' => 'caf_c',
                'title' => 'تربص إجازة التدريب الإفريقية (Licence CAF C) — البليدة',
                'code' => 'DTR-CAFC-2026-01',
                'description' => 'الإجازة القارية الأولى المعتمدة من الكونفدرالية الإفريقية لكرة القدم (CAF)، بإشراف خبراء معتمدين من CAF وDTN.',
                'prerequisites' => 'شهادة FAF 3 أو لاعب دولي سابق، مستوى دراسي 3 ثانوي كامل أو جامعي.',
                'required_documents' => "1. ملف ترشح CAF معتمد.\n2. جواز السفر البيومتري.\n3. شهادات التدريب السابقة.\n4. الملف الطبي الصادر عن مركز طب رياضي معتمد.",
                'start_date' => '2027-02-01',
                'end_date' => '2027-02-18',
                'registration_deadline' => '2027-01-15',
                'location' => 'مركب مصطفى تشاكر - البليدة',
                'max_candidates' => 25,
                'fee_amount' => 35000.00,
                'is_active' => true,
                'is_registration_open' => true,
            ],
            [
                'course_type' => 'caf_b',
                'title' => 'تربص إجازة التدريب الإفريقية (Licence CAF B)',
                'code' => 'DTR-CAFB-2026-01',
                'description' => 'الإجازة القارية المتقدمة للتدريب في الأندية المحترفة والمنتخبات الوطنية للفئات الشبانية وفق معايير CAF الحديثة.',
                'prerequisites' => 'حيازة إجازة CAF C منذ سنتين على الأقل مع ممارسة تدريبية موثقة برخص رسمية.',
                'required_documents' => "1. ملف CAF رسمي.\n2. شهادة CAF C.\n3. سجل الممارسة الميدانية.",
                'start_date' => '2027-03-01',
                'end_date' => '2027-03-20',
                'registration_deadline' => '2027-02-10',
                'location' => 'المدرسة الوطنية للرياضات الأولمبية - المدية / البليدة',
                'max_candidates' => 20,
                'fee_amount' => 50000.00,
                'is_active' => true,
                'is_registration_open' => true,
            ],
        ];

        foreach ($courses as $c) {
            $course = CoachCourse::updateOrCreate(['code' => $c['code']], $c);

            // Add sample pedagogical materials for Goalkeeper course with 15-day display timer
            if ($c['course_type'] === 'goalkeeper') {
                CoachCourseMaterial::updateOrCreate(
                    [
                        'coach_course_id' => $course->id,
                        'title' => 'المحور 1: التمركز الأساسي والوضعية الحركية لحارس المرمى',
                    ],
                    [
                        'module_number' => 'المحور الأول (Théorie & Terrain)',
                        'description' => 'محاضرة علمية مفصلة حول زوايا التمركز، قراءة مسار الكرة، وسرعة رد الفعل في المواقف الدفاعية 1 ضد 1.',
                        'file_path' => 'materials/gk_module1_positioning.pdf',
                        'published_at' => now(),
                        'expires_at' => now()->addDays(15),
                        'duration_days' => 15,
                        'is_active' => true,
                    ]
                );

                CoachCourseMaterial::updateOrCreate(
                    [
                        'coach_course_id' => $course->id,
                        'title' => 'المحور 2: أسلوب بناء اللعب بالقدمين والتصدي للكرات الهوائية',
                    ],
                    [
                        'module_number' => 'المحور الثاني (Relance & Prise de Balle)',
                        'description' => 'تمارين تطبيقية وفيديوهات توضيحية حول دور حارس المرمى كعنصر أول في بناء الهجمة والتصدي للتوزيعات العرضية.',
                        'file_path' => 'materials/gk_module2_distribution.pdf',
                        'published_at' => now(),
                        'expires_at' => now()->addDays(15),
                        'duration_days' => 15,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
