@extends('masari.layout')

@section('title', 'فضاء المترشح والمدرب | متابعة الملف والدروس التكوينية')

@section('content')
<div class="py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Top Welcome Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4 space-x-reverse">
            <div class="w-16 h-16 rounded-2xl bg-forest-900 border-2 border-brand-500 text-white flex items-center justify-center text-2xl font-black shadow-inner">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $user->name }}</h1>
                    <span class="bg-brand-50 text-brand-700 border border-brand-200 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        مترشح / مدرب معتمد
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1 flex items-center space-x-3 space-x-reverse">
                    <span>📧 {{ $user->email }}</span>
                    <span>📱 {{ $user->phone ?? '-' }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-2 space-x-reverse">
            <a href="{{ route('dtr.apply') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-4 py-2 rounded-xl text-xs transition">
                + طلب تكوين آخر
            </a>
            <form action="{{ route('dtr.logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold px-4 py-2 rounded-xl text-xs transition">
                    خروج
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 mb-8 text-xs font-bold flex items-center space-x-2 space-x-reverse">
            <span class="text-lg">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 mb-8 text-xs font-bold flex items-center space-x-2 space-x-reverse">
            <span class="text-lg">⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-sky-50 border border-sky-200 text-sky-800 rounded-2xl p-4 mb-8 text-xs font-bold flex items-center space-x-2 space-x-reverse">
            <span class="text-lg">ℹ️</span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Applications List -->
    <div class="space-y-8">
        @forelse($applications as $app)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- App Header -->
                <div class="bg-slate-50 p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <span class="text-xs font-mono font-bold text-brand-700 bg-brand-50 border border-brand-200 px-2.5 py-0.5 rounded-lg">
                                {{ $app->application_number }}
                            </span>
                            <span class="text-xs text-slate-400">تاريخ الإيداع: {{ $app->created_at->format('Y/m/d - H:i') }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 mt-1">
                            {{ $app->course?->title }}
                        </h2>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if($app->status === 'approved')
                            <span class="inline-flex items-center space-x-1.5 space-x-reverse bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold px-3.5 py-1.5 rounded-full text-xs shadow-2xs">
                                <span>✓</span>
                                <span>مقبول ومصادق عليه للتربص</span>
                            </span>
                        @elseif($app->status === 'reserved')
                            <span class="inline-flex items-center space-x-1.5 space-x-reverse bg-amber-100 text-amber-800 border border-amber-300 font-bold px-3.5 py-1.5 rounded-full text-xs shadow-2xs">
                                <span>⚠️</span>
                                <span>تحفظ (وثائق ناقصة للمراجعة)</span>
                            </span>
                        @elseif($app->status === 'rejected')
                            <span class="inline-flex items-center space-x-1.5 space-x-reverse bg-rose-100 text-rose-800 border border-rose-300 font-bold px-3.5 py-1.5 rounded-full text-xs shadow-2xs">
                                <span>✕</span>
                                <span>ملف ترشح مرفوض</span>
                            </span>
                        @else
                            <span class="inline-flex items-center space-x-1.5 space-x-reverse bg-sky-100 text-sky-800 border border-sky-300 font-bold px-3.5 py-1.5 rounded-full text-xs shadow-2xs">
                                <span>⏳</span>
                                <span>قيد المعاينة والتدقيق التقني</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- App Body Details -->
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Notice according to status -->
                    @if($app->status === 'approved')
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-emerald-900 text-xs space-y-2">
                            <div class="flex items-center space-x-2 space-x-reverse font-bold text-sm text-emerald-800">
                                <span>🎉</span>
                                <span>تم قبول ومصادقة ملف ترشحكم رسمياً في الدورة التكوينية!</span>
                            </div>
                            <p class="leading-relaxed">
                                نرحب بكم في هذا التكوين. يمكنكم الآن الاطلاع على المحتوى البيداغوجي والدروس الميدانية والنظرية في الفضاء المخصص أدناه.
                                يرجى تحميل الدروس والمحاضرات قبل انقضاء المهلة الزمنية المحددة (15 يوماً من تاريخ النشر).
                            </p>
                            <div class="pt-2 flex flex-wrap gap-2 text-[11px] font-bold">
                                <span class="bg-white/80 px-2.5 py-1 rounded-lg border border-emerald-200">
                                    📍 مكان التربص: {{ $app->course?->location }}
                                </span>
                                <span class="bg-white/80 px-2.5 py-1 rounded-lg border border-emerald-200">
                                    📅 فترة التربص: من {{ $app->course?->start_date?->format('Y/m/d') }} إلى {{ $app->course?->end_date?->format('Y/m/d') }}
                                </span>
                            </div>
                        </div>

                    @elseif($app->status === 'reserved')
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-amber-900 text-xs space-y-3">
                            <div class="flex items-center space-x-2 space-x-reverse font-bold text-sm text-amber-800">
                                <span>⚠️</span>
                                <span>ملاحظة المديرية التقنية الجهوية (سبب التحفظ):</span>
                            </div>
                            <p class="bg-white p-3 rounded-xl border border-amber-200 font-medium text-slate-800">
                                {{ $app->review_notes ?? 'يرجى إعادة رفع الوثائق المطلوبة بصورة واضحة لاستكمال ملفك.' }}
                            </p>

                            <!-- Reservation Upload Form -->
                            <form action="{{ route('dtr.application.update-reserve', $app->id) }}" method="POST" enctype="multipart/form-data" class="pt-2 space-y-3">
                                @csrf
                                <span class="font-bold block text-slate-800">رفع الوثيقة المستدركة لتصحيح الملف:</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <select id="doc_select_{{ $app->id }}" onchange="document.querySelectorAll('.reserve-input-{{ $app->id }}').forEach(el => el.classList.add('hidden')); document.getElementById('input_' + this.value + '_{{ $app->id }}').classList.remove('hidden');" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs">
                                            <option value="doc_handwritten_request">1. طلب خطي للمشاركة</option>
                                            <option value="doc_photo">2. صورة شمسية</option>
                                            <option value="doc_id_card">3. بطاقة التعريف الوطنية</option>
                                            <option value="doc_player_license">4. رخصة حارس / لاعب سابق</option>
                                            <option value="doc_medical_certificate">5. شهادة طبية عامة وصدرية</option>
                                            <option value="doc_police_record">6. صحيفة السوابق العدلية</option>
                                            <option value="doc_residence_certificate">7. شهادة الإقامة</option>
                                            <option value="doc_birth_certificate">8. شهادة الميلاد</option>
                                            <option value="doc_school_certificate">9. شهادة مدرسية 1 ثانوي على الأقل</option>
                                            <option value="doc_diplomas">10. شهادات التدريب السابقة</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="file" name="doc_handwritten_request" id="input_doc_handwritten_request_{{ $app->id }}" class="reserve-input-{{ $app->id }} w-full text-xs">
                                        <input type="file" name="doc_photo" id="input_doc_photo_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_id_card" id="input_doc_id_card_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_player_license" id="input_doc_player_license_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_medical_certificate" id="input_doc_medical_certificate_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_police_record" id="input_doc_police_record_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_residence_certificate" id="input_doc_residence_certificate_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_birth_certificate" id="input_doc_birth_certificate_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_school_certificate" id="input_doc_school_certificate_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                        <input type="file" name="doc_diplomas" id="input_doc_diplomas_{{ $app->id }}" class="reserve-input-{{ $app->id }} hidden w-full text-xs">
                                    </div>
                                </div>
                                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-5 rounded-xl text-xs transition shadow-xs">
                                    رفع الوثيقة المستدركة وإعادة الإرسال للمعاينة ←
                                </button>
                            </form>
                        </div>

                    @elseif($app->status === 'rejected')
                        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 text-rose-900 text-xs space-y-2">
                            <strong class="font-bold text-sm block">قرار المديرية التقنية: تعذر قبول ملف الترشح</strong>
                            <p class="bg-white p-3 rounded-xl border border-rose-200">
                                السبب: {{ $app->review_notes ?? 'عدم استيفاء الشروط البيداغوجية أو الإدارية المطلوبة لهذه الدورة.' }}
                            </p>
                        </div>

                    @else
                        <div class="bg-sky-50 border border-sky-200 rounded-2xl p-4 text-sky-900 text-xs flex items-center space-x-3 space-x-reverse">
                            <span class="text-xl">⏳</span>
                            <div>
                                <strong class="font-bold block">الملف في طور المعاينة والدراسة الإدارية والتقنية:</strong>
                                <span class="text-sky-800">
                                    تم استلام وثائقك الرقمية التسع بنجاح، ويقوم أعضاء المديرية الفنية بمراجعتها والمصادقة على أهليتك. ستظهر النتيجة هنا فور صدورها.
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Pedagogical Lessons & Materials Section (For Approved Candidates) -->
                    @if($app->status === 'approved' && $app->course?->materials->isNotEmpty())
                        <div class="pt-6 border-t border-slate-200">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 flex items-center space-x-2 space-x-reverse">
                                        <span>📚</span>
                                        <span>المحتوى البيداغوجي والدروس المبرمجة</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        محاضرات ودروس نظرية وتطبيقية موجهة حصرياً للمترشحين المقبولين في الدورة.
                                    </p>
                                </div>
                                <span class="bg-brand-50 text-brand-700 text-xs font-bold px-3 py-1 rounded-full border border-brand-200">
                                    {{ $app->course->materials->count() }} محاضرات متاحة
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($app->course->materials as $mat)
                                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 hover:border-brand-500 transition flex flex-col justify-between shadow-2xs">
                                        <div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-[11px] font-mono font-bold text-copper-600 bg-copper-50 px-2 py-0.5 rounded border border-copper-200">
                                                    {{ $mat->module_number ?? 'محاضرة' }}
                                                </span>

                                                <!-- Duration / Expiration Badge -->
                                                @if($mat->isAvailable())
                                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 flex items-center space-x-1 space-x-reverse">
                                                        <span>⏱️</span>
                                                        <span>متبقي {{ $mat->remaining_days }} يوماً</span>
                                                    </span>
                                                @else
                                                    <span class="text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                                        منتهي الصلاحية
                                                    </span>
                                                @endif
                                            </div>

                                            <h4 class="font-bold text-slate-900 text-sm mb-1">
                                                {{ $mat->title }}
                                            </h4>

                                            <p class="text-xs text-slate-500 leading-relaxed mb-3">
                                                {{ $mat->description }}
                                            </p>
                                        </div>

                                        <div class="pt-3 border-t border-slate-200/80 flex items-center justify-between text-xs">
                                            <span class="text-slate-400 text-[10px]">
                                                مدة العرض: {{ $mat->duration_days }} يوماً
                                            </span>

                                            @if($mat->isAvailable())
                                                <a href="{{ route('dtr.material.download', ['materialId' => $mat->id, 'applicationId' => $app->id]) }}" class="inline-flex items-center space-x-1 space-x-reverse bg-forest-900 hover:bg-brand-600 text-white font-bold py-1.5 px-3.5 rounded-lg text-xs transition shadow-xs">
                                                    <span>📥</span>
                                                    <span>تحميل المحاضرة (PDF)</span>
                                                </a>
                                            @else
                                                <span class="text-slate-400 font-medium">غير متاح حالياً</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
                <span class="text-4xl block mb-2 text-slate-400">📋</span>
                <h3 class="text-lg font-bold text-slate-800">لا توجد طلبات ترشح مسجلة بهذا الحساب بعد</h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">يمكنكم تصفح الدورات التكوينية المفتوحة وإيداع ملف ترشحكم الآن.</p>
                <a href="{{ route('dtr.apply') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition">
                    + إيداع ملف ترشح جديد
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
