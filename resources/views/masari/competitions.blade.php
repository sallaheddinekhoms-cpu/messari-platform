@extends('masari.layout')

@section('title', 'مركز البطولات والرزنامات الشبانية | منصة مساري - LRF Blida')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header Section -->
    <div class="mb-8 pb-4 border-b border-slate-200">
        <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block mb-1">
            لجنة تنظيم المنافسات والرزنامات الشبانية (COC - LRFB)
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
            مركز البطولات والرزنامات الرسمية للرابطة الجهوية بالبليدة
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-3xl font-medium">
            متابعة حية وشاملة لجداول الترتيب، الرزنامة الأسبوعية للجولات، ونتائج المباريات لبطولات النخبة، المستوى الأول (أ، ب)، والمستوى الجهوي (أ، ب، ج) لفئات U20، U17، و U15.
        </p>
    </div>

    <!-- Official PDF Calendars Download Card (رزنامات الموسم الرياضي 2026/2027) -->
    <div class="mb-8 bg-gradient-to-r from-forest-950 via-forest-900 to-forest-950 rounded-2xl p-6 text-white border border-brand-500/30 shadow-md relative overflow-hidden">
        <div class="absolute -top-12 -left-12 w-48 h-48 bg-brand-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center space-x-2 space-x-reverse mb-2">
                    <span class="bg-brand-500 text-forest-950 text-[10px] font-black px-2.5 py-0.5 rounded uppercase">رسمي ومعتمد</span>
                    <span class="text-copper-400 text-xs font-mono font-bold">LIGUE RÉGIONALE DE FOOTBALL DE BLIDA • 2026/2027</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white">
                    الرزنامات الرسمية لبطولات الفئات الشبانية لموسم 2026 / 2027
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                    تحميل وثائق الرزنامة وجداول الجولات الـ 11 وتوزيع الأفواج المعتمدة من طرف لجنة تنظيم المنافسات (COC) لفئات النخبة والمستوى الجهوي (أفواج أ، ب، ج).
                </p>
            </div>

            <!-- Two Download Buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Elite Calendar PDF -->
                <a href="{{ asset('documents/CALENDRIER_JEUNES_ELITE_2026_2027.pdf') }}" target="_blank" download="CALENDRIER_JEUNES_ELITE_2026_2027.pdf"
                   class="inline-flex items-center justify-between sm:justify-center px-4 py-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-md transition space-x-2.5 space-x-reverse border border-brand-400/40">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <span class="text-lg">📥</span>
                        <div class="text-right">
                            <span class="block font-bold">رزنامة فئة النخبة (PDF)</span>
                            <span class="text-[10px] text-brand-100 font-normal">المحترف 1، القسم 2 وما بين الجهات (12 نادياً)</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>

                <!-- Regional Calendar PDF -->
                <a href="{{ asset('documents/CALENDRIER_JEUNES_REGIONALE_2026_2027.pdf') }}" target="_blank" download="CALENDRIER_JEUNES_REGIONALE_2026_2027.pdf"
                   class="inline-flex items-center justify-between sm:justify-center px-4 py-3 rounded-xl bg-copper-600 hover:bg-copper-500 text-white font-bold text-xs shadow-md transition space-x-2.5 space-x-reverse border border-copper-400/40">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <span class="text-lg">📥</span>
                        <div class="text-right">
                            <span class="block font-bold">رزنامة المستوى الجهوي (PDF)</span>
                            <span class="text-[10px] text-copper-100 font-normal">الأفواج الثلاثة: (أ)، (ب)، (ج - س) (34 نادياً)</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Division Tier Tabs (المستويات والدرجات الستة) -->
    <div class="mb-4">
        <span class="text-xs font-bold text-slate-400 block mb-2">اختر المستوى أو الدرجة:</span>
        <div class="flex items-center space-x-2 space-x-reverse overflow-x-auto pb-2">
            @foreach($divisionsList as $key => $divInfo)
                @php
                    $isDivActive = ($division === $key);
                @endphp
                <a href="{{ route('competitions.index', ['division' => $key, 'category' => $selectedCategory?->code, 'tab' => $activeTab]) }}" 
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition flex items-center space-x-1.5 space-x-reverse border {{ $isDivActive ? 'bg-forest-950 text-brand-300 border-brand-600 shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-200' }}">
                    <span class="w-2 h-2 rounded-full {{ $isDivActive ? 'bg-brand-400' : 'bg-slate-300' }}"></span>
                    <span>{{ $divInfo['name_ar'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Category Selector Pills (U20, U17, U15) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pt-2 pb-4 border-b border-slate-200">
        <div class="flex items-center space-x-2 space-x-reverse overflow-x-auto">
            <span class="text-xs font-bold text-slate-500 pl-2">الفئة:</span>
            @foreach($categories as $cat)
                @php
                    $isCatActive = $selectedCategory?->code === $cat->code;
                @endphp
                <a href="{{ route('competitions.index', ['division' => $division, 'category' => $cat->code, 'tab' => $activeTab]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition border {{ $isCatActive ? 'bg-brand-600 text-white border-brand-600 shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200' }}">
                    <span>فئة {{ $cat->code }}</span>
                    <span class="text-[11px] opacity-80">({{ $cat->name_ar }})</span>
                </a>
            @endforeach
        </div>

        <!-- Mode Toggle: Standings vs Fixtures -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 self-start sm:self-auto">
            <a href="{{ route('competitions.index', ['division' => $division, 'category' => $selectedCategory?->code, 'tab' => 'standings']) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $activeTab === 'standings' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                📊 جدول الترتيب
            </a>
            <a href="{{ route('competitions.index', ['division' => $division, 'category' => $selectedCategory?->code, 'tab' => 'fixtures']) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $activeTab === 'fixtures' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                📅 رزنامة المباريات والجولات
            </a>
        </div>
    </div>

    <!-- Competitions Content -->
    @forelse($competitions as $comp)
        <div class="space-y-6 mb-12">
            <!-- Competition Header Banner -->
            <div class="bg-forest-950 text-white p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs border border-forest-800">
                <div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <span class="bg-forest-900 text-brand-300 text-[10px] font-bold px-2.5 py-0.5 rounded uppercase border border-brand-700">
                            {{ $comp->division_name_ar ?? 'الرابطة الجهوية' }}
                        </span>
                        <span class="bg-copper-50 text-copper-700 text-[10px] font-bold px-2.5 py-0.5 rounded">
                            فئة {{ $comp->ageCategory?->code }}
                        </span>
                    </div>
                    <h2 class="text-lg font-bold mt-2">{{ $comp->name_ar }}</h2>
                    <p class="text-xs text-brand-300/80 font-mono">{{ $comp->name_fr }}</p>
                </div>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <span class="bg-forest-900 text-slate-300 text-xs px-3 py-1.5 rounded-lg font-semibold border border-forest-800">الموسم: {{ $comp->season?->name }}</span>
                    <span class="bg-brand-600 text-white text-xs px-3.5 py-1.5 rounded-lg font-bold shadow-xs">
                        {{ $comp->groups->first()?->groupTeams?->count() ?? 0 }} أندية متنافسة
                    </span>
                </div>
            </div>

            @foreach($comp->groups as $group)
                @if($activeTab === 'standings')
                    <!-- ================= TABLE: STANDINGS ================= -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <h3 class="font-bold text-sm text-slate-900 flex items-center space-x-2 space-x-reverse">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
                                <span>جدول الترتيب العام • {{ $group->name }}</span>
                            </h3>
                            <span class="text-xs text-slate-500 font-medium">محدّث تلقائياً بنظام النقاط الرسمي</span>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-right">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                                        <th class="py-2.5 px-3">الترتيب</th>
                                        <th class="py-2.5 px-3">النادي</th>
                                        <th class="py-2.5 px-2 text-center">لعب</th>
                                        <th class="py-2.5 px-2 text-center">فاز</th>
                                        <th class="py-2.5 px-2 text-center">تعادل</th>
                                        <th class="py-2.5 px-2 text-center">خسر</th>
                                        <th class="py-2.5 px-2 text-center">له</th>
                                        <th class="py-2.5 px-2 text-center">عليه</th>
                                        <th class="py-2.5 px-2 text-center">الفارق</th>
                                        <th class="py-2.5 px-3 text-center font-bold">النقاط</th>
                                        <th class="py-2.5 px-3 text-center">آخر 5 مباريات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($group->groupTeams as $index => $gt)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="py-2.5 px-3 font-bold {{ $index < 2 ? 'text-brand-700' : 'text-slate-400' }}">{{ $index + 1 }}</td>
                                            <td class="py-2.5 px-3 font-bold text-slate-800 flex items-center space-x-2 space-x-reverse">
                                                <span class="w-6 h-6 rounded bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-[10px] border border-slate-200">
                                                    {{ $gt->clubTeam?->club?->acronym }}
                                                </span>
                                                <div>
                                                    <span>{{ $gt->clubTeam?->club?->name_ar }}</span>
                                                    <span class="text-[10px] text-slate-400 block font-normal">{{ $gt->clubTeam?->club?->wilaya?->name_ar }}</span>
                                                </div>
                                            </td>
                                            <td class="py-2.5 px-2 text-center text-slate-600">{{ $gt->played }}</td>
                                            <td class="py-2.5 px-2 text-center text-slate-600">{{ $gt->won }}</td>
                                            <td class="py-2.5 px-2 text-center text-slate-600">{{ $gt->drawn }}</td>
                                            <td class="py-2.5 px-2 text-center text-slate-600">{{ $gt->lost }}</td>
                                            <td class="py-2.5 px-2 text-center text-slate-600">{{ $gt->goals_for }}</td>
                                            <td class="py-2.5 px-2 text-center text-slate-600">{{ $gt->goals_against }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono font-bold {{ $gt->goal_difference > 0 ? 'text-brand-700' : ($gt->goal_difference < 0 ? 'text-rose-600' : 'text-slate-500') }}">
                                                {{ $gt->goal_difference > 0 ? '+' . $gt->goal_difference : $gt->goal_difference }}
                                            </td>
                                            <td class="py-2.5 px-3 text-center font-black text-slate-900 bg-brand-50/70">{{ $gt->points }}</td>
                                            <td class="py-2.5 px-3 text-center">
                                                <span class="font-mono text-[10px] text-slate-600 bg-slate-100 px-2 py-0.5 rounded">{{ $gt->form ?? '—' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- ================= FIXTURES & SCHEDULES (الرزنامة) ================= -->
                    <div class="space-y-6">
                        @foreach($group->rounds as $round)
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $round->is_current ? 'bg-brand-600 animate-pulse' : 'bg-slate-400' }}"></span>
                                        <h3 class="font-bold text-sm text-slate-900">{{ $round->name_ar }}</h3>
                                        @if($round->is_current)
                                            <span class="bg-brand-50 text-brand-700 border border-brand-200 text-[10px] font-bold px-2 py-0.5 rounded">
                                                الجولة الحالية
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-slate-400 font-mono">
                                        الفترة: {{ $round->start_date ? \Carbon\Carbon::parse($round->start_date)->format('Y-m-d') : 'مجدولة' }}
                                    </span>
                                </div>

                                <!-- Matches in this round -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @forelse($round->matches as $match)
                                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white transition flex flex-col justify-between">
                                            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-2">
                                                <span>📍 {{ $match->stadium?->name ?? 'الملعب البلدي' }}</span>
                                                <span class="font-bold {{ $match->status === 'finished' ? 'text-slate-500' : 'text-brand-700' }}">
                                                    {{ $match->status === 'finished' ? 'انتهت النتيجة' : 'مجدولة (' . substr($match->kickoff_time, 0, 5) . ')' }}
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between py-1">
                                                <div class="flex items-center space-x-2 space-x-reverse w-5/12">
                                                    <span class="w-6 h-6 rounded bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold text-[10px] flex-shrink-0">
                                                        {{ $match->homeTeam?->club?->acronym }}
                                                    </span>
                                                    <span class="font-bold text-xs text-slate-900 truncate">{{ $match->homeTeam?->club?->short_name }}</span>
                                                </div>

                                                <div class="w-2/12 text-center">
                                                    @if($match->status === 'finished')
                                                        <span class="bg-slate-900 text-white font-mono font-bold px-2.5 py-1 rounded-md text-xs shadow-xs">
                                                            {{ $match->home_score }} - {{ $match->away_score }}
                                                        </span>
                                                    @else
                                                        <span class="bg-brand-50 text-brand-800 font-mono font-bold px-2 py-0.5 rounded text-[11px] border border-brand-200">
                                                            VS
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="flex items-center space-x-2 space-x-reverse justify-end w-5/12">
                                                    <span class="font-bold text-xs text-slate-900 truncate text-left">{{ $match->awayTeam?->club?->short_name }}</span>
                                                    <span class="w-6 h-6 rounded bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold text-[10px] flex-shrink-0">
                                                        {{ $match->awayTeam?->club?->acronym }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                                                <span>التاريخ: {{ $match->match_date->format('Y-m-d') }}</span>
                                                <span>{{ $match->referee_name }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 col-span-2 text-center py-4">لم تُبرمج مباريات لهذه الجولة بعد.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    @empty
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <span class="text-3xl block mb-2 text-slate-400">⚽</span>
            <h3 class="text-base font-bold text-slate-800">لا توجد منافسات مسجلة لهذه الفئة حالياً</h3>
            <p class="text-xs text-slate-500 mt-1">يرجى اختيار مستوى آخر أو فئة أخرى من القائمة العلوية.</p>
        </div>
    @endforelse

    <!-- Breakdown of Official Groups and Regulatory Guidelines (دليل وتوزيع الأفواج المعتمدة 2026/2027) -->
    <div class="mt-12 pt-8 border-t border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">التوزيع الرسمي للأندية المشاركة</span>
                <h3 class="text-xl font-black text-slate-900 mt-1">
                    أفواج بطولات الفئات الشبانية المعتمدة رسمياً (موسم 2026 / 2027)
                </h3>
            </div>
            <span class="bg-forest-950 text-brand-300 text-xs font-bold px-3 py-1.5 rounded-lg border border-forest-800">
                المجموع: 46 نادياً معتمداً
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Group 1: Elite -->
            <div class="bg-white rounded-2xl p-5 border border-brand-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-brand-100">
                        <span class="text-xs font-black text-brand-700">بطولة النخبة (Élite)</span>
                        <span class="bg-brand-50 text-brand-700 text-[10px] font-bold px-2 py-0.5 rounded-full">12 فريقاً</span>
                    </div>
                    <ul class="space-y-1.5 text-xs text-slate-700 font-medium">
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>جمعية الشلف (القسم الأول)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>وفاق المدية (أكاديمية)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>اتحاد البليدة (القسم الثاني)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>نجم القليعة (القسم الثاني)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>أمل الأربعاء (القسم الثاني)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>أولمبيك واد الفضة (ما بين الجهات)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>مستقبل واد سلي (ما بين الجهات)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>صفاء الخميس (ما بين الجهات)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>شباب الزاوية (ما بين الجهات)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>وداد مفتاح (ما بين الجهات)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>آمال المدية (ما بين الجهات)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span><span>مولودية أولاد يعيش (ما بين الجهات)</span></li>
                    </ul>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <a href="{{ asset('documents/CALENDRIER_JEUNES_ELITE_2026_2027.pdf') }}" target="_blank" download="CALENDRIER_JEUNES_ELITE_2026_2027.pdf" class="text-[11px] font-bold text-brand-700 hover:underline">تحميل وثيقة النخبة (PDF) ↓</a>
                </div>
            </div>

            <!-- Group 2: Regional A -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <span class="text-xs font-black text-slate-900">المستوى الجهوي • فوج (أ)</span>
                        <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full">12 فريقاً</span>
                    </div>
                    <ul class="space-y-1.5 text-xs text-slate-600 font-medium">
                        <li>• رائد شلالة العذاورة</li>
                        <li>• فريق بوركيكة</li>
                        <li>• اتحاد سيدي غيلاس</li>
                        <li>• اتحاد شلالة العذاورة</li>
                        <li>• سبورتينغ قرواو</li>
                        <li>• هلال قرواو</li>
                        <li>• شباب البرواقية</li>
                        <li>• مولودية سيدي نعمان</li>
                        <li>• شباب مالحة</li>
                        <li>• شباب سغوان</li>
                        <li>• شباب عين بوسيف</li>
                        <li>• اتحاد الشفة</li>
                    </ul>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <span class="text-[11px] text-slate-400 font-semibold">ولايات: البليدة، تيبازة، المدية</span>
                </div>
            </div>

            <!-- Group 3: Regional B -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <span class="text-xs font-black text-slate-900">المستوى الجهوي • فوج (ب)</span>
                        <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full">12 فريقاً</span>
                    </div>
                    <ul class="space-y-1.5 text-xs text-slate-600 font-medium">
                        <li>• شباب العطاف</li>
                        <li>• أمل الفرم</li>
                        <li>• هلال الروينة</li>
                        <li>• نادي بواسماعيل</li>
                        <li>• شباب بوقادير</li>
                        <li>• اتحاد بومدفع</li>
                        <li>• شباب العامرة</li>
                        <li>• نجم بقعة سحنون</li>
                        <li>• اتحاد مليانة</li>
                        <li>• شباب تاوقريت</li>
                        <li>• شباب تيبازة</li>
                        <li>• شباب سنجاس</li>
                    </ul>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <span class="text-[11px] text-slate-400 font-semibold">ولايات: الشلف، عين الدفلى، تيبازة</span>
                </div>
            </div>

            <!-- Group 4: Regional C (Djelfa) -->
            <div class="bg-white rounded-2xl p-5 border border-copper-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-copper-100">
                        <span class="text-xs font-black text-copper-700">المستوى الجهوي • فوج (ج / س)</span>
                        <span class="bg-copper-50 text-copper-700 text-[10px] font-bold px-2 py-0.5 rounded-full">10 فرق</span>
                    </div>
                    <ul class="space-y-1.5 text-xs text-slate-700 font-medium">
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>أمل حد الصحاري</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>أمل مسعد</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>اتحاد أولاد نايل</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>وداد عين وسارة</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>فريق كرة القدم الجلفة (FCJ)</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>شباب سيدي نايل</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>جيل البيرين</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>ترجي سيدي لعجال</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>مولودية حاسي بحبح</span></li>
                        <li class="flex items-center space-x-1.5 space-x-reverse"><span class="w-1.5 h-1.5 rounded-full bg-copper-600"></span><span>وداد سيدي لعجال</span></li>
                    </ul>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <a href="{{ asset('documents/CALENDRIER_JEUNES_REGIONALE_2026_2027.pdf') }}" target="_blank" download="CALENDRIER_JEUNES_REGIONALE_2026_2027.pdf" class="text-[11px] font-bold text-copper-700 hover:underline">تحميل وثيقة الجهوي (PDF) ↓</a>
                </div>
            </div>
        </div>

        <!-- Official Administrative Guidelines Footer Box -->
        <div class="mt-6 bg-slate-50 border border-slate-200 rounded-2xl p-5 text-xs text-slate-600 leading-relaxed">
            <div class="flex items-center space-x-2 space-x-reverse font-bold text-slate-800 mb-2">
                <span>📌</span>
                <span>الشروط التنظيمية الإلزامية للفئات الشبانية (المادة 10 من التنظيم الرسمي للمنافسات):</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                <div>
                    <span class="font-bold text-slate-900 block">• فئة أقل من 20 سنة (U20):</span>
                    <span class="text-slate-500">مواليد 2007، 2008، 2009 (35 لاعباً كحد أقصى منهم 3 حراس مرمى). إلزامياً: على الأقل 10 لاعبين مواليد 2009.</span>
                </div>
                <div>
                    <span class="font-bold text-slate-900 block">• فئة أقل من 17 سنة (U17):</span>
                    <span class="text-slate-500">مواليد 2010 و 2011 (35 لاعباً كحد أقصى منهم 3 حراس مرمى). إلزامياً: على الأقل 10 لاعبين مواليد 2011.</span>
                </div>
                <div>
                    <span class="font-bold text-slate-900 block">• فئة أقل من 15 سنة (U15):</span>
                    <span class="text-slate-500">مواليد 2012 و 2013 (حد أدنى 15 لاعباً و 3 حراس مرمى). كحد أقصى 35 لاعباً.</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                <span>LIGUE RÉGIONALE DE FOOTBALL DE BLIDA — COMMISSION D'ORGANISATION DES COMPÉTITIONS (COC)</span>
                <span>مدير لجنة تنظيم المنافسات: السيد محي الدين سيد أحمد</span>
            </div>
        </div>
    </div>

</div>
@endsection
