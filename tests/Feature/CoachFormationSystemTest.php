<?php

namespace Tests\Feature;

use App\Models\CoachApplication;
use App\Models\CoachCourse;
use App\Models\CoachCourseMaterial;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CoachFormationSystemTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'masari_db',
        ]);
    }

    public function test_dtr_portal_index_loads_with_courses(): void
    {
        $response = $this->get('/dtr');
        $response->assertStatus(200);
        $response->assertSee('المنظومة الرقمية لتكوين المدربين');
        $response->assertSee('مدربي حراس المرمى');
        $response->assertSee('FAF 1');
    }

    public function test_dtr_apply_page_loads_with_required_documents(): void
    {
        $course = CoachCourse::where('course_type', 'goalkeeper')->first();
        $response = $this->get('/dtr/apply?course_id=' . $course->id);
        $response->assertStatus(200);
        $response->assertSee('طلب خطي للمشاركة في التكوين');
        $response->assertSee('نسخة من رخصة حارس مرمى / لاعب سابق');
        $response->assertSee('شهادة السوابق العدلية (صحيفة رقم 03)');
        $response->assertSee('شهادة مدرسية تثبت المستوى الدراسي');
    }

    public function test_candidate_can_submit_application_and_login(): void
    {
        Storage::fake('public');
        $course = CoachCourse::where('course_type', 'goalkeeper')->first();

        $email = 'coach.candidate.' . time() . '@example.com';
        $postData = [
            'coach_course_id' => $course->id,
            'first_name_ar' => 'رياض',
            'last_name_ar' => 'بن عمار',
            'first_name_fr' => 'Ryad',
            'last_name_fr' => 'Benammar',
            'birth_date' => '1995-06-12',
            'birth_place' => 'البليدة',
            'phone' => '0555123456',
            'email' => $email,
            'password' => 'CoachPass@2026',
            'password_confirmation' => 'CoachPass@2026',
            'education_level' => 'السنة الأولى ثانوي',
            'sporting_experience' => 'حارس مرمى سابق في نجم بوعرفة (2015-2022)',
            'doc_handwritten_request' => UploadedFile::fake()->create('demande.pdf', 500, 'application/pdf'),
            'doc_photo' => UploadedFile::fake()->image('photo.jpg'),
            'doc_id_card' => UploadedFile::fake()->create('cni.pdf', 500, 'application/pdf'),
            'doc_player_license' => UploadedFile::fake()->create('licence.pdf', 500, 'application/pdf'),
            'doc_medical_certificate' => UploadedFile::fake()->create('medical.pdf', 500, 'application/pdf'),
            'doc_police_record' => UploadedFile::fake()->create('casier.pdf', 500, 'application/pdf'),
            'doc_residence_certificate' => UploadedFile::fake()->create('residence.pdf', 500, 'application/pdf'),
            'doc_birth_certificate' => UploadedFile::fake()->create('naissance.pdf', 500, 'application/pdf'),
            'doc_school_certificate' => UploadedFile::fake()->create('scolaire.pdf', 500, 'application/pdf'),
        ];

        $response = $this->post('/dtr/apply', $postData);
        $response->assertRedirect('/dtr/dashboard');

        // Assert Application in DB
        $app = CoachApplication::where('email', $email)->first();
        $this->assertNotNull($app);
        $this->assertEquals('pending', $app->status);
        $this->assertStringContainsString('DTR-', $app->application_number);

        // Assert Candidate user created
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals('coach', $user->role);
    }

    public function test_candidate_dashboard_displays_application_and_materials_when_approved(): void
    {
        $course = CoachCourse::where('course_type', 'goalkeeper')->first();
        $user = User::where('role', 'coach')->first() ?? User::factory()->create(['role' => 'coach']);

        $app = CoachApplication::create([
            'user_id' => $user->id,
            'coach_course_id' => $course->id,
            'application_number' => 'DTR-TEST-' . time(),
            'first_name_ar' => 'مراد',
            'last_name_ar' => 'زواوي',
            'birth_date' => '1992-04-10',
            'birth_place' => 'المدية',
            'phone' => '0661000000',
            'email' => $user->email,
            'education_level' => 'السنة الأولى ثانوي',
            'status' => 'approved',
            'review_notes' => 'مقبول رسمياً في تربص مدربي حراس المرمى',
        ]);

        $response = $this->actingAs($user)->get('/dtr/dashboard');
        $response->assertStatus(200);
        $response->assertSee('مقبول ومصادق عليه للتربص');
        $response->assertSee('المحتوى البيداغوجي والدروس المبرمجة');
        $response->assertSee('المحور الأول');
    }

    public function test_candidate_dossier_print_renders_official_form(): void
    {
        $user = User::where('role', 'coach')->first() ?? User::factory()->create(['role' => 'coach']);
        $app = CoachApplication::first();

        $response = $this->actingAs($user)->get(route('dtr.candidate.print-dossier', $app->id));
        $response->assertStatus(200);
        $response->assertSee('الجمهورية الجزائرية الديمقراطية الشعبية');
        $response->assertSee('المديرية واللجنة التقنية الجهوية (DTR)');
        $response->assertSee('استمارة ترشح ووصل إيداع ملف تكوين المدربين');
        $response->assertSee($app->application_number);
    }

    public function test_course_roster_print_renders_for_dtr_officer(): void
    {
        $dtrUser = User::where('role', 'dtr')->first();
        $course = CoachCourse::first();

        $response = $this->actingAs($dtrUser)->get(route('dtr.course.print-roster', $course->id));
        $response->assertStatus(200);
        $response->assertSee('القائمة الاسمية الرسمية للمترشحين لدورة التكوين');
        $response->assertSee($course->title);
    }

    public function test_dtr_officer_can_access_filament_panel(): void
    {
        $dtrUser = User::where('role', 'dtr')->first();
        $this->assertTrue($dtrUser->canAccessPanel(filament()->getPanel('admin')));

        $response = $this->actingAs($dtrUser)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('المديرية الفنية الجهوية');
    }
}
