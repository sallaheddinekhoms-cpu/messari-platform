@extends('masari.layout')

@section('title', 'بيانات محمية بالاعتماد التقني | منصة مساري - LRF Blida')

@section('content')
<div class="py-16 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden text-center relative">
        <!-- Top Security Stripe -->
        <div class="bg-gradient-to-r from-forest-950 via-brand-800 to-forest-950 text-white py-4 px-6 flex items-center justify-between border-b border-brand-700">
            <div class="flex items-center space-x-2 space-x-reverse text-xs font-bold text-brand-200">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                <span>المديرية واللجنة التقنية الجهوية (DTR) • رابطة البليدة</span>
            </div>
            <span class="bg-forest-900 text-copper-300 border border-copper-500/40 text-[11px] font-mono font-bold px-2.5 py-0.5 rounded-full">
                🔒 فضاء تقني مقيّد
            </span>
        </div>

        <div class="p-8 sm:p-12">
            <!-- Shield & Lock Graphic -->
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-brand-50 border-2 border-brand-300 flex items-center justify-center text-3xl shadow-inner text-brand-700">
                🛡️
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-3">
                الملف الرياضي والتقني محمي بالاعتماد الرسمي
            </h1>

            <p class="text-slate-600 text-sm leading-relaxed max-w-xl mx-auto mb-6">
                تطبيقاً للوائح الاتحادية الجزائرية لكرة القدم (FAF) والرابطة الجهوية لكرة القدم بالبليدة (LRFB) المتعلقة بـ <strong>حماية المعطيات الشخصية والبيومترية للاعبي الفئات الصغرى</strong> (U13 إلى U20)، فإن السجلات الفنية، والتقارير الاستكشافية، وتفاصيل المسار الكروي متاحة حصرياً للأطر التقنية المعتمدة.
            </p>

            <!-- Player Basic Identification (Masked) -->
            <div class="bg-slate-50 rounded-2xl p-4 max-w-lg mx-auto border border-slate-200 mb-8 text-right">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2 mb-3">
                    <span class="text-xs font-bold text-slate-500">حالة الاعتماد في المنظومة:</span>
                    <span class="inline-flex items-center space-x-1 space-x-reverse bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        <span>✓</span>
                        <span>ملف رياضي موثق بالرابطة</span>
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">رمز الملف الرقمي:</span>
                        <strong class="font-mono text-slate-800 text-sm">
                            {{ substr($player->passport_number, 0, 10) }}****
                        </strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">الفئة العمرية:</span>
                        <strong class="text-slate-800 text-sm">
                            {{ $player->currentLicense?->ageCategory?->name_ar ?? 'فئة شبانية' }} ({{ $player->currentLicense?->ageCategory?->code ?? 'U17' }})
                        </strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">النادي المنتمي إليه:</span>
                        <strong class="text-brand-800 text-sm">
                            {{ $player->currentLicense?->club?->name_ar ?? 'النادي المعتمد' }}
                        </strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">مستوى الحماية:</span>
                        <strong class="text-amber-700 text-sm">
                            خاص بإدارة الرابطة والنادي
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Login / Access Actions -->
            <div class="max-w-md mx-auto space-y-3">
                <div class="text-xs font-bold text-slate-500 mb-3">
                    يرجى تسجيل الدخول بحسابك المخوّل للوصول إلى كامل البيانات الفنية:
                </div>

                <a href="/admin/login" class="w-full flex items-center justify-center space-x-2 space-x-reverse bg-forest-900 hover:bg-forest-950 text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-md border border-brand-700">
                    <span>👑</span>
                    <span>دخول فضاء المديرية الفنية الجهوية وإدارة الرابطة (DTR)</span>
                    <span>←</span>
                </a>

                <a href="{{ route('club.login') }}" class="w-full flex items-center justify-center space-x-2 space-x-reverse bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-md">
                    <span>🛡️</span>
                    <span>دخول بوابة الأندية الرياضية المعتمدة</span>
                    <span>←</span>
                </a>

                <div class="pt-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        ← العودة إلى الصفحة الرئيسية
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="bg-slate-50 py-3 px-6 border-t border-slate-200 text-xs text-slate-500">
            المنظومة الرقمية «مساري» • الرابطة الجهوية لكرة القدم بالبليدة • مطابقة لمعايير حماية الرياضيين الشبان
        </div>
    </div>
</div>
@endsection
