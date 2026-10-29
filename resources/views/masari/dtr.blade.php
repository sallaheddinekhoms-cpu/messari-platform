@extends('masari.layout')

@section('title', 'اللجنة التقنية الجهوية | الرابطة الجهوية لكرة القدم بالبليدة')

@section('content')
<!-- Hero Header -->
<section class="relative bg-gradient-to-b from-slate-900 via-forest-900 to-forest-950 text-white py-14 sm:py-18 border-b border-forest-800 overflow-hidden">
    <div class="absolute -top-32 -right-32 w-80 h-80 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-4">
            <div class="inline-flex items-center space-x-2 space-x-reverse bg-forest-950/90 border border-brand-600/40 px-3.5 py-1.5 rounded-full text-xs font-semibold text-brand-300 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                <span>الرابطة الجهوية لكرة القدم بالبليدة (LRF Blida)</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                اللجنة التقنية الجهوية
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-200 to-copper-500 block text-2xl sm:text-3xl mt-1">
                    Commission Technique Régionale (CTR / DTR)
                </span>
            </h1>

            <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                الهيئة التقنية الرسمية المكلفة بالإشراف على التكوين، وتطوير كرة القدم القاعدية، والمتابعة الميدانية لبطولات الفئات الشبانية (U13 إلى U20) عبر ولايات البليدة، تيبازة، المدية، الجلفة، وعين الدفلى.
            </p>
        </div>
    </div>
</section>

<!-- Content Sections -->
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:border-brand-500 transition">
            <div class="w-12 h-12 rounded-xl bg-brand-50 border border-brand-200 flex items-center justify-center text-2xl text-brand-700 mb-4">
                📋
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">متابعة وتأطير بطولات الشبان</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                الإشراف الفني المباشر على جولات بطولات الفئات الشبانية (قسم النخبة، المستوى الأول أ/ب، والمستوى الجهوي)، ومتابعة تقارير المندوبين والملاحظات التقنية للجولات.
            </p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:border-brand-500 transition">
            <div class="w-12 h-12 rounded-xl bg-copper-50 border border-copper-200 flex items-center justify-center text-2xl text-copper-700 mb-4">
                🎓
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">تكوين ورسكلة المدربين</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                تنظيم الدورات التكوينية لمدربي الفئات الشبانية لنيل إجازات التدريب الفيدرالية والإفريقية (FAF 1, FAF 2, FAF 3 / CAF C)، وورشات العمل الدورية.
            </p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:border-brand-500 transition">
            <div class="w-12 h-12 rounded-xl bg-forest-900 border border-brand-700 flex items-center justify-center text-2xl text-brand-300 mb-4">
                🇩🇿
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">المنتخبات الجهوية والانتقاء</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                تنظيم التجمعات والتربصات الدورية لانتقاء وتشكيل المنتخبات الجهوية لرابطة البليدة تحضيراً للمهرجانات الوطنية والدورات ما بين الرابطات بالتنسيق مع الاتحادية.
            </p>
        </div>
    </div>

    <!-- Institutional Callout -->
    <div class="bg-gradient-to-r from-forest-950 via-forest-900 to-forest-950 rounded-3xl p-8 sm:p-10 border border-brand-800 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-right">
            <span class="bg-forest-900 text-copper-300 text-xs font-bold px-3 py-1 rounded-full border border-copper-500/40 inline-block">
                فضاء العمل التقني المعتمد
            </span>
            <h3 class="text-xl sm:text-2xl font-black">
                المنظومة الرقمية للجنة التقنية الجهوية
            </h3>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                بوابة التنسيق والمتابعة المخصصة لأعضاء اللجنة التقنية، الكشافين، وممثلي المديرية الفنية المعتمدين بالرابطة.
            </p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('competitions.index') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition border border-white/20">
                مركز البطولات والرزنامة
            </a>
            <a href="/admin/login" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition shadow-md">
                فضاء الدخول التقني ←
            </a>
        </div>
    </div>
</div>
@endsection
