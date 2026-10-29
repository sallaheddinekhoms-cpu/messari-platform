<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. دورات وتربصات التكوين المفتوحة من طرف المديرية التقنية الجهوية
        Schema::create('coach_courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_type'); // goalkeeper, faf1, faf2, faf3, caf_c, caf_b, other
            $table->string('title');
            $table->string('code')->unique(); // e.g. DTR-GK-2026-01
            $table->text('description')->nullable();
            $table->text('prerequisites')->nullable(); // الشروط والمستوى المطلوب
            $table->text('required_documents')->nullable(); // الوثائق المطلوبة
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->string('location')->nullable(); // مكان التربص النظري والميداني
            $table->unsignedSmallInteger('max_candidates')->default(30);
            $table->decimal('fee_amount', 10, 2)->nullable(); // حقوق المشاركة
            $table->boolean('is_active')->default(true);
            $table->boolean('is_registration_open')->default(true);
            $table->timestamps();
        });

        // 2. ملفات وطلبات الترشح
        Schema::create('coach_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('coach_course_id')->constrained('coach_courses')->cascadeOnDelete();
            $table->string('application_number')->unique(); // DTR-GK-2026-0001
            
            // المعلومات الشخصية والمدنية
            $table->string('first_name_ar');
            $table->string('last_name_ar');
            $table->string('first_name_fr')->nullable();
            $table->string('last_name_fr')->nullable();
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('national_id_number')->nullable(); // رقم التعريف الوطني NIN
            $table->foreignId('wilaya_id')->nullable()->constrained('wilayas')->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('phone');
            $table->string('email');
            
            // المستوى والمسار الرياضي
            $table->string('education_level'); // أولى ثانوي، بكالوريا، جامعي...
            $table->string('sporting_experience')->nullable(); // حارس سابق / لاعب
            $table->string('current_club')->nullable(); // النادي الحالي إن وُجد
            $table->string('previous_diplomas')->nullable(); // شهادات سابقة

            // الوثائق الممسوحة ضوئياً (الملف الإداري الرقمي)
            $table->string('doc_handwritten_request')->nullable(); // 1. طلب خطي للمشاركة
            $table->string('doc_photo')->nullable(); // 2. صورة شمسية
            $table->string('doc_id_card')->nullable(); // 3. نسخة بطاقة التعريف
            $table->string('doc_player_license')->nullable(); // 4. رخصة حارس/لاعب سابق
            $table->string('doc_medical_certificate')->nullable(); // 5. شهادة طبية عامة وأمراض الصدر
            $table->string('doc_police_record')->nullable(); // 6. صحيفة السوابق العدلية رقم 03
            $table->string('doc_residence_certificate')->nullable(); // 7. شهادة الإقامة
            $table->string('doc_birth_certificate')->nullable(); // 8. شهادة الميلاد
            $table->string('doc_school_certificate')->nullable(); // 9. شهادة مدرسية أولى ثانوي على الأقل
            $table->string('doc_diplomas')->nullable(); // 10. شهادات التدريب السابقة

            // التدقيق والمصادقة الإدارية من طرف المديرية التقنية الجهوية
            $table->enum('status', ['pending', 'approved', 'reserved', 'rejected'])->default('pending');
            $table->text('review_notes')->nullable(); // سبب التحفظ أو الرفض أو ملاحظة القبول
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });

        // 3. المحتوى البيداغوجي والدروس التكوينية (محدد بالمدة مثل 15 يوماً)
        Schema::create('coach_course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_course_id')->constrained('coach_courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('module_number')->nullable(); // المحور الأول، المحور الثاني...
            $table->string('file_path')->nullable(); // PDF / Presentation
            $table->string('video_url')->nullable(); // رابط فيديو تعليمي
            $table->timestamp('published_at')->useCurrent();
            $table->timestamp('expires_at')->nullable(); // تاريخ انتهاء الصلاحية
            $table->unsignedSmallInteger('duration_days')->default(15); // مدة العرض بالأيام
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. سجل تتبع اطلاع وتحميل المترشحين للدروس
        Schema::create('coach_material_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_course_material_id')->constrained('coach_course_materials')->cascadeOnDelete();
            $table->foreignId('coach_application_id')->constrained('coach_applications')->cascadeOnDelete();
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_material_views');
        Schema::dropIfExists('coach_course_materials');
        Schema::dropIfExists('coach_applications');
        Schema::dropIfExists('coach_courses');
    }
};
