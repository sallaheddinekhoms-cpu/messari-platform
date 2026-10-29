@extends('masari.layout')

@section('title', 'استمارة إيداع ملف ترشح لتكوين المدربين | المديرية التقنية الجهوية - LRF Blida')

@section('content')
<div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('dtr.index') }}" class="text-xs font-bold text-slate-500 hover:text-brand-700">← العودة لدليل التكوين</a>
        <div class="flex items-center space-x-3 space-x-reverse mt-2">
            <span class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-200 flex items-center justify-center text-xl text-brand-700">
                📝
            </span>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
                    استمارة التسجيل وإيداع ملف الترشح الرقمي
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">الرابطة الجهوية لكرة القدم بالبليدة • المديرية واللجنة التقنية الجهوية (DTR)</p>
            </div>
        </div>
    </div>

    <!-- Alert / Instructions -->
    <div class="bg-brand-50 border border-brand-200 rounded-2xl p-4 mb-8 text-xs text-brand-900 flex items-start space-x-3 space-x-reverse shadow-2xs">
        <span class="text-xl">ℹ️</span>
        <div class="space-y-1">
            <p class="font-bold">تعليمات هامة لإيداع ملف الترشح:</p>
            <p class="text-brand-800 leading-relaxed">
                يرجى التأكد من مسح الوثائق الإدارية التسع المطلوبة بوضوح بصيغة (PDF أو صور JPEG/PNG واضحة). 
                سيتم إنشاء حساب فوري لك لمتابعة قرار المعاينة، الاستدعاء، والاطلاع على الدروس البيداغوجية.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 mb-8 text-xs space-y-1">
            <strong class="font-bold block">يرجى تصحيح الأخطاء التالية قبل إرسال الملف:</strong>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('dtr.apply.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- 1. نوع التكوين والدورة -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center space-x-2 space-x-reverse">
                    <span class="text-brand-700">1.</span>
                    <span>اختيار مسلك التكوين المطلوب</span>
                </h2>
                <span class="text-xs text-slate-400 font-semibold">* حقل إجباري</span>
            </div>

            <div>
                <label for="coach_course_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                    الدورة التكوينية المستهدفة:
                </label>
                <select name="coach_course_id" id="coach_course_id" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                    <option value="">-- اضغط لاختيار دورة التكوين (حراس المرمى / FAF / CAF) --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ (old('coach_course_id') == $course->id || (isset($selectedCourse) && $selectedCourse->id == $course->id)) ? 'selected' : '' }}>
                            {{ $course->title }} ({{ $course->code }}) — {{ number_format($course->fee_amount, 0) }} دج
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 mt-1">
                    تفتح الرابطة التسجيل في دورات: مدربي حراس المرمى، FAF 1، FAF 2، FAF 3، CAF C، و CAF B.
                </p>
            </div>
        </div>

        <!-- 2. المعلومات الشخصية للمترشح -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center space-x-2 space-x-reverse">
                    <span class="text-brand-700">2.</span>
                    <span>البيانات الشخصية والمدنية للمترشح</span>
                </h2>
                <span class="text-xs text-slate-400 font-semibold">تطابق بطاقة التعريف</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">الاسم (بالعربية) *</label>
                    <input type="text" name="first_name_ar" value="{{ old('first_name_ar') }}" required placeholder="مثال: يونس" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">اللقب (بالعربية) *</label>
                    <input type="text" name="last_name_ar" value="{{ old('last_name_ar') }}" required placeholder="مثال: بن طيبة" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Prénom (en Français)</label>
                    <input type="text" name="first_name_fr" value="{{ old('first_name_fr') }}" placeholder="Ex: Younes" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nom (en Français)</label>
                    <input type="text" name="last_name_fr" value="{{ old('last_name_fr') }}" placeholder="Ex: Ben Tayeb" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">تاريخ الميلاد *</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">مكان الميلاد *</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}" required placeholder="مثال: البليدة" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">رقم بطاقة التعريف الوطنية (NIN)</label>
                    <input type="text" name="national_id_number" value="{{ old('national_id_number') }}" placeholder="18 رقماً المكتوبة على البطاقة" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ولاية الإقامة *</label>
                    <select name="wilaya_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                        <option value="">-- اختر الولاية --</option>
                        @foreach($wilayas as $wilaya)
                            <option value="{{ $wilaya->id }}" {{ old('wilaya_id') == $wilaya->id ? 'selected' : '' }}>
                                {{ $wilaya->code }} - {{ $wilaya->name_ar }} ({{ $wilaya->name_fr }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">العنوان الشخصي الكامل</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="الحي، البلدية، الدائرة" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">رقم الهاتف المحمول *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="06XXXXXXXX / 05XXXXXXXX" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">البريد الإلكتروني (لتسجيل الدخول) *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="coach@example.com" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">كلمة المرور للحساب *</label>
                    <input type="password" name="password" required placeholder="6 أحرف أو أرقام على الأقل" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">تأكيد كلمة المرور *</label>
                    <input type="password" name="password_confirmation" required placeholder="أعد إدخال كلمة المرور" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
            </div>
        </div>

        <!-- 3. المستوى الدراسي والخبرة الرياضية -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center space-x-2 space-x-reverse">
                    <span class="text-brand-700">3.</span>
                    <span>المستوى التعليمي والمسار الكروي</span>
                </h2>
                <span class="text-xs text-slate-400 font-semibold">شرط 1 ثانوي على الأقل</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">المستوى الدراسي *</label>
                    <select name="education_level" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                        <option value="السنة الأولى ثانوي" {{ old('education_level') == 'السنة الأولى ثانوي' ? 'selected' : '' }}>السنة الأولى ثانوي (المستوى الأدنى المطلوب)</option>
                        <option value="السنة الثانية ثانوي" {{ old('education_level') == 'السنة الثانية ثانوي' ? 'selected' : '' }}>السنة الثانية ثانوي</option>
                        <option value="السنة الثالثة ثانوي (نهائي)" {{ old('education_level') == 'السنة الثالثة ثانوي (نهائي)' ? 'selected' : '' }}>السنة الثالثة ثانوي (نهائي)</option>
                        <option value="بكالوريا" {{ old('education_level') == 'بكالوريا' ? 'selected' : '' }}>حائز على شهادة البكالوريا</option>
                        <option value="دراسات جامعية / ليسانس" {{ old('education_level') == 'دراسات جامعية / ليسانس' ? 'selected' : '' }}>دراسات جامعية (ليسانس / ماستر / دكتوراه)</option>
                        <option value="خريج معهد الرياضة ISTS" {{ old('education_level') == 'خريج معهد الرياضة ISTS' ? 'selected' : '' }}>خريج المعهد العالي لتكنولوجيا الرياضة (ISTS)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">النادي الحالي (إن وُجد)</label>
                    <input type="text" name="current_club" value="{{ old('current_club') }}" placeholder="مثال: اتحاد البليدة / نجم البرواقية" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">المسار الكروي والصفة الرياضية السابقة *</label>
                    <input type="text" name="sporting_experience" value="{{ old('sporting_experience') }}" placeholder="مثال: حارس مرمى سابق في وداد بوفاريك (2014-2022)، رخصة صنف أكابر" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">الشهادات التدريبية السابقة (إن وُجدت)</label>
                    <input type="text" name="previous_diplomas" value="{{ old('previous_diplomas') }}" placeholder="مثال: شهادة FAF 1 دورة 2024 / شهادة إسعافات أولية" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs sm:text-sm">
                </div>
            </div>
        </div>

        <!-- 4. الوثائق الإدارية الممسوحة (الملف الإداري الرسمي) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900 flex items-center space-x-2 space-x-reverse">
                        <span class="text-brand-700">4.</span>
                        <span>رفع وثائق ملف الترشح الممسوحة ضوئياً (الوثائق الرسمية)</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">الصيغ المعتمدة: PDF, JPG, PNG بحجم أقصى 4 ميغابايت لكل وثيقة</p>
                </div>
                <span class="bg-forest-900 text-copper-300 border border-forest-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                    الملف الكامل
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Doc 1 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        1. طلب خطي للمشاركة في التكوين *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">محرر بخط اليد وموجه للمدير الفني الجهوي لرابطة البليدة</p>
                    <input type="file" name="doc_handwritten_request" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 2 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        2. صورتان شمسيتان حديثتان *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">خلفية بيضاء واضحة للمترشح</p>
                    <input type="file" name="doc_photo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 3 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        3. نسخة من بطاقة التعريف الوطنية *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">الواجهة الأمامية والخلفية للبطاقة البيومترية</p>
                    <input type="file" name="doc_id_card" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 4 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        4. نسخة من رخصة حارس مرمى / لاعب سابق *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">رخصة أو شهادة ممارسة صادرة عن الرابطة أو النادي</p>
                    <input type="file" name="doc_player_license" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 5 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        5. شهادة طبية عامة وشهادة لأمراض الصدر *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">تثبت القدرة البدنية لممارسة التدريب الرياضي الميداني</p>
                    <input type="file" name="doc_medical_certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 6 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        6. شهادة السوابق العدلية (صحيفة رقم 03) *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">صالحة لا تتجاوز 3 أشهر من تاريخ الإصدار</p>
                    <input type="file" name="doc_police_record" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 7 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        7. شهادة الإقامة *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">صادرة عن بلدية الإقامة الحالية</p>
                    <input type="file" name="doc_residence_certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 8 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        8. شهادة الميلاد *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">شهادة ميلاد أصلية حديثة</p>
                    <input type="file" name="doc_birth_certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 9 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        9. شهادة مدرسية تثبت المستوى الدراسي *
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">تثبت السنة الأولى ثانوي على الأقل (أو شهادة جامعية)</p>
                    <input type="file" name="doc_school_certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>

                <!-- Doc 10 -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-800 mb-1">
                        10. شهادات التدريب السابقة (إن وُجدت)
                    </label>
                    <p class="text-[11px] text-slate-500 mb-2">إجبارية لدورات FAF 2 و FAF 3 و CAF C/B</p>
                    <input type="file" name="doc_diplomas" accept=".pdf,image/*" class="w-full text-xs text-slate-600 file:mr-0 file:ml-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-700">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-forest-950 rounded-2xl p-6 text-white text-center border border-brand-800 shadow-lg space-y-3">
            <p class="text-xs text-slate-300 max-w-lg mx-auto">
                بالضغط على الزر أدناه، تؤكد صحة كافة المعلومات والوثائق المرفقة، وسيتم تسجيل ملف ترشحكم لدى المديرية التقنية الجهوية برقم إيداع رسمي.
            </p>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-8 rounded-xl text-sm transition shadow-md inline-flex items-center space-x-2 space-x-reverse">
                <span>تأكيد التسجيل وإرسال ملف الترشح للجنة التقنية</span>
                <span>←</span>
            </button>
        </div>
    </form>
</div>
@endsection
