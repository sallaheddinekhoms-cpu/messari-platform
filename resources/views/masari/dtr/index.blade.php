@extends('masari.layout')

@section('title', 'بوابة تكوين المدربين والتربصات | المديرية التقنية الجهوية - LRF Blida')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-slate-900 via-forest-900 to-forest-950 text-white overflow-hidden py-16 sm:py-20 border-b border-forest-800">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-copper-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-5">
            <div class="inline-flex items-center space-x-2 space-x-reverse bg-forest-950/90 border border-brand-600/40 px-3.5 py-1.5 rounded-full text-xs font-semibold text-brand-300 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                <span>الرابطة الجهوية لكرة القدم بالبليدة • المديرية واللجنة التقنية الجهوية (DTR / CTR)</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                المنظومة الرقمية لتكوين المدربين
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-200 to-copper-500 block text-2xl sm:text-4xl mt-1">
                    فضاء التربصات، إيداع الملفات، والمتابعة البيداغوجية
                </span>
            </h1>

            <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                بوابة التكوين الرسمية المعتمدة لتسجيل مدربي حراس المرمى ومدربي المستويات الفيدرالية والقارية 
                (FAF 1, FAF 2, FAF 3, CAF C, CAF B)، معالجة ملفات الترشح، وتوزيع المحتوى البيداغوجي والدروس الميدانية والنظرية.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 pt-3">
                <a href="{{ route('dtr.apply') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition shadow-lg flex items-center space-x-2 space-x-reverse">
                    <span>📝</span>
                    <span>إيداع ملف ترشح جديد</span>
                    <span>←</span>
                </a>

                @if(auth()->check() && (auth()->user()->role === 'coach' || auth()->user()->role === 'admin' || auth()->user()->isDtr()))
                    <a href="{{ route('dtr.dashboard') }}" class="bg-forest-900 hover:bg-forest-800 text-brand-200 border border-brand-700 font-bold px-5 py-3 rounded-xl text-sm transition shadow-sm flex items-center space-x-1.5 space-x-reverse">
                        <span>👤</span>
                        <span>فضاء متابعة ملفي والدروس</span>
                    </a>
                @else
                    <a href="{{ route('dtr.login') }}" class="bg-forest-900 hover:bg-forest-800 text-brand-200 border border-brand-700/60 font-bold px-5 py-3 rounded-xl text-sm transition shadow-sm flex items-center space-x-1.5 space-x-reverse">
                        <span>🔑</span>
                        <span>تسجيل الدخول إلى حسابي</span>
                    </a>
                @endif

                <a href="#courses-catalog" class="bg-forest-950 hover:bg-forest-900 text-slate-300 border border-forest-800 font-bold px-4 py-3 rounded-xl text-sm transition">
                    <span>دليل مسالك التكوين الشامل ↓</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Metrics Ribbon -->
<section class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-brand-600 font-mono">{{ $stats['total_courses'] }}</div>
                <div class="text-xs font-bold text-slate-500 mt-1">مسالك تكوينية معتمدة (FAF / CAF)</div>
            </div>
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-copper-600 font-mono">{{ $stats['open_registrations'] }}</div>
                <div class="text-xs font-bold text-slate-500 mt-1">دورات وتربصات مفتوحة للتسجيل</div>
            </div>
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-brand-700 font-mono">{{ $stats['total_candidates'] }}</div>
                <div class="text-xs font-bold text-slate-500 mt-1">إجمالي المترشحين المسجلين</div>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-900 font-mono">{{ $stats['approved_candidates'] }}</div>
                <div class="text-xs font-bold text-slate-500 mt-1">مترشحين مصادق على قبولهم</div>
            </div>
        </div>
    </div>
</section>

<!-- Courses Catalog Section -->
<section id="courses-catalog" class="py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block mb-1">
                دليل الدورات والتربصات المفتوحة (موسم 2026 / 2027)
            </span>
            <h2 class="text-2xl font-black text-slate-900">
                اختر مسلك التكوين وأودع ملفك الرقمي
            </h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 max-w-md mt-2 sm:mt-0 font-medium">
            تتم معالجة الملفات والتدقيق في الوثائق الإدارية من طرف أعضاء اللجنة التقنية الجهوية قبل استدعاء المترشحين المقبولين للتربص.
        </p>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-brand-500 transition duration-200 flex flex-col justify-between shadow-xs relative group">
                <!-- Top Badge -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-mono font-bold text-slate-400">
                            {{ $course->code }}
                        </span>
                        <span class="bg-brand-50 text-brand-700 border border-brand-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                            {{ $course->course_type_label }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 leading-snug mb-2 group-hover:text-brand-700 transition">
                        {{ $course->title }}
                    </h3>

                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        {{ Str::limit($course->description, 130) }}
                    </p>

                    <!-- Details List -->
                    <div class="space-y-2 py-3 border-t border-b border-slate-100 text-xs text-slate-600">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">آخر أجل للتسجيل:</span>
                            <strong class="text-slate-800 font-mono">{{ $course->registration_deadline?->format('Y/m/d') ?? 'مفتوح حالياً' }}</strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">مكان التربص:</span>
                            <span class="text-slate-700 font-medium text-left truncate max-w-[180px]">{{ $course->location }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">حقوق المشاركة:</span>
                            <strong class="text-brand-700 font-mono">{{ number_format($course->fee_amount, 0) }} دج</strong>
                        </div>
                    </div>

                    <!-- Required Docs Preview -->
                    <div class="mt-3.5 bg-slate-50 rounded-xl p-3 border border-slate-200/80 text-[11px]">
                        <span class="font-bold text-slate-700 block mb-1">📋 شروط وملف الترشح الأساسي:</span>
                        <p class="text-slate-500 leading-normal line-clamp-2">
                            {{ $course->prerequisites }}
                        </p>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="mt-5 pt-3 border-t border-slate-100">
                    <a href="{{ route('dtr.apply', ['course_id' => $course->id]) }}" class="w-full flex items-center justify-center space-x-1.5 space-x-reverse bg-forest-900 hover:bg-brand-600 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-xs">
                        <span>إيداع ملف الترشح في هذه الدورة</span>
                        <span>←</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Pedagogical System Features -->
<section class="py-14 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block mb-1">
                الجانب البيداغوجي والإداري
            </span>
            <h3 class="text-2xl font-black text-slate-900">
                كيف تعمل المنظومة التكوينية الرقمية؟
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-brand-50 border border-brand-200 flex items-center justify-center text-2xl text-brand-700">
                    1️⃣
                </div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">التسجيل ورفع الملف</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    اختيار نوع التكوين وملء المعلومات الشخصية ورفع الوثائق التسع الممسوحة ضوئياً.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-copper-50 border border-copper-200 flex items-center justify-center text-2xl text-copper-700">
                    2️⃣
                </div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">معاينة وتدقيق DTR</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    تقوم المديرية التقنية بفحص الملف والمصادقة عليه أو إبداء تحفظ لاستدراك أي وثيقة ناقصة.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-2xl text-emerald-700">
                    3️⃣
                </div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">القبول والاستدعاء</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    ظهور قرار القبول في حساب المترشح فورياً مع استدعاء رسمي لحضور التربص الميداني.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-forest-900 border border-brand-700 flex items-center justify-center text-2xl text-brand-300">
                    4️⃣
                </div>
                <h4 class="font-bold text-sm text-slate-900 mb-1">فضاء الدروس المؤقتة</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    إرسال الدروس والمحاضرات الرقمية للمقبولين مع تحكم بالمدة الزمنية للعرض (15 يوماً).
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
