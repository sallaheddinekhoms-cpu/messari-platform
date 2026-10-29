@extends('masari.layout')

@section('title', 'الرابطة الجهوية لكرة القدم بالبليدة | منصة مساري - مركز بطولات الفئات الشبانية')

@section('content')
<!-- Hero Section (Official Youth League Identity) -->
<section class="relative bg-gradient-to-b from-slate-900 via-forest-900 to-forest-950 text-white overflow-hidden py-16 sm:py-20 border-b border-forest-800">
    <!-- Subtle pitch grid overlay -->
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(circle at center, rgba(255,255,255,0.8) 0, rgba(255,255,255,0) 65%), linear-gradient(0deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent);"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-copper-600/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Text Content -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Accreditation badge -->
                <div class="inline-flex items-center space-x-2 space-x-reverse bg-forest-950/90 border border-brand-600/40 px-3.5 py-1.5 rounded-full text-xs font-semibold text-brand-300 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                    <span>المنظومة الرقمية الرسمية • الموسم الرياضي {{ $currentSeason?->name ?? '2026/2027' }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-5xl font-black tracking-tight leading-tight">
                    بطولات الفئات الشبانية لرابطة البليدة
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-200 to-copper-500 block mt-1">«منصة مساري — LRF BLIDA»</span>
                </h1>

                <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl font-normal">
                    المنظومة الرقمية الرسمية المعتمدة من الرابطة الجهوية لكرة القدم بالبليدة لمتابعة وإدارة بطولات الفئات الشبانية: <strong class="text-white font-bold">فئات البراعم والأصاغر (U13, U15)</strong> و<strong class="text-white font-bold">الأشبال والأواسط (U17, U20)</strong>، الجداول الرسمية، الرزنامات المحينة، وتحديث الترتيب والنتائج فورياً.
                </p>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="{{ route('competitions.index') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition shadow-md flex items-center space-x-2 space-x-reverse">
                        <span>⚽</span>
                        <span>مركز البطولات والرزنامة</span>
                        <span>←</span>
                    </a>
                    <a href="{{ route('club.login') }}" class="bg-forest-900 hover:bg-forest-800 text-brand-200 border border-brand-700/60 font-bold px-5 py-3 rounded-xl text-sm transition shadow-sm flex items-center space-x-1.5 space-x-reverse">
                        <span>🛡️</span>
                        <span>بوابة حسابات الأندية</span>
                    </a>
                    <a href="{{ route('dtr.index') }}" class="bg-forest-950 hover:bg-forest-900 text-slate-300 hover:text-white border border-forest-800 font-bold px-4 py-3 rounded-xl text-sm transition">
                        <span>اللجنة التقنية الجهوية</span>
                    </a>
                </div>

                <!-- Category Filters (Clean & Harmonious) -->
                <div class="space-y-2 pt-2">
                    <span class="text-xs text-slate-400 font-semibold block">تصفح بطولات الفئات الشبانية المعتمدة رسمياً:</span>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('competitions.index', ['category' => 'U20']) }}" class="bg-forest-900 hover:bg-forest-800 text-slate-200 border border-forest-800 px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 space-x-reverse">
                            <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                            <span>U20 أقل من 20 سنة</span>
                        </a>
                        <a href="{{ route('competitions.index', ['category' => 'U17']) }}" class="bg-forest-900 hover:bg-forest-800 text-slate-200 border border-forest-800 px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 space-x-reverse">
                            <span class="w-2 h-2 rounded-full bg-copper-500"></span>
                            <span>U17 أقل من 17 سنة</span>
                        </a>
                        <a href="{{ route('competitions.index', ['category' => 'U15']) }}" class="bg-forest-900 hover:bg-forest-800 text-slate-200 border border-forest-800 px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 space-x-reverse">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <span>U15 أقل من 15 سنة</span>
                        </a>
                        <a href="{{ route('competitions.index', ['category' => 'U13']) }}" class="bg-forest-900 hover:bg-forest-800 text-slate-200 border border-forest-800 px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 space-x-reverse">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span>U13 البراعم</span>
                        </a>
                    </div>

                    <!-- Quick Official Calendars PDF Download Strip -->
                    <div class="mt-4 pt-3 border-t border-forest-800 flex flex-wrap items-center gap-2.5 text-xs">
                        <span class="text-copper-400 font-bold flex items-center space-x-1 space-x-reverse">
                            <span>📌</span>
                            <span>تحميل الرزنامات الرسمية 2026/2027 (PDF):</span>
                        </span>
                        <a href="{{ asset('documents/CALENDRIER_JEUNES_ELITE_2026_2027.pdf') }}" target="_blank" download="CALENDRIER_JEUNES_ELITE_2026_2027.pdf" 
                           class="inline-flex items-center space-x-1.5 space-x-reverse bg-forest-900 hover:bg-forest-800 text-brand-300 px-3 py-1 rounded-lg border border-brand-500/30 transition font-bold shadow-xs">
                            <span>📄</span>
                            <span>رزنامة النخبة</span>
                            <span class="text-[10px] text-brand-400">↓</span>
                        </a>
                        <a href="{{ asset('documents/CALENDRIER_JEUNES_REGIONALE_2026_2027.pdf') }}" target="_blank" download="CALENDRIER_JEUNES_REGIONALE_2026_2027.pdf" 
                           class="inline-flex items-center space-x-1.5 space-x-reverse bg-forest-900 hover:bg-forest-800 text-copper-300 px-3 py-1 rounded-lg border border-copper-500/30 transition font-bold shadow-xs">
                            <span>📑</span>
                            <span>رزنامة المستوى الجهوي</span>
                            <span class="text-[10px] text-copper-400">↓</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hero Visual / Institutional League Showcase Card -->
            <div class="lg:col-span-5">
                <div class="messari-passport-card rounded-2xl p-6 shadow-2xl relative text-white">
                    <!-- Top Header with Official League Seal & Masari Circular Emblem -->
                    <div class="flex items-center justify-between border-b border-forest-800 pb-4 mb-4">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-11 h-11 object-contain">
                            <div>
                                <h3 class="font-bold text-xs tracking-wide">الرابطة الجهوية لكرة القدم بالبليدة</h3>
                                <p class="text-[9px] text-brand-300 font-mono uppercase">Ligue Régionale de Football • Blida</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <img src="{{ asset('images/masari-logo.jpg') }}" alt="MESSARI" class="w-10 h-10 rounded-full object-contain border-2 border-brand-500 bg-white shadow-xs" style="width: 40px; height: 40px;">
                        </div>
                    </div>

                    <!-- Institutional Presentation -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-brand-300">مركز المنافسات الشبانية</span>
                            <span class="bg-forest-900 text-copper-400 border border-brand-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                الموسم 2026 / 2027
                            </span>
                        </div>

                        <div class="bg-forest-950/80 rounded-xl p-3.5 border border-forest-800 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between py-1 border-b border-forest-900">
                                <span class="text-slate-300">قسم النخبة (Élite):</span>
                                <strong class="text-white">12 نادياً معتمداً</strong>
                            </div>
                            <div class="flex items-center justify-between py-1 border-b border-forest-900">
                                <span class="text-slate-300">المستوى الأول (الفوج أ و ب):</span>
                                <strong class="text-brand-300">بطولات الولايات</strong>
                            </div>
                            <div class="flex items-center justify-between py-1">
                                <span class="text-slate-300">المستوى الجهوي (أ، ب، ج):</span>
                                <strong class="text-copper-400">البليدة، تيبازة، المدية، الجلفة، عين الدفلى</strong>
                            </div>
                        </div>

                        <!-- Quick Navigation Buttons Inside Card -->
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <a href="{{ route('competitions.index') }}" class="text-center bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 px-3 rounded-xl text-xs transition shadow-sm">
                                جداول الترتيب والنتائج ←
                            </a>
                            <a href="{{ route('dtr.index') }}" class="text-center bg-forest-900 hover:bg-forest-800 text-brand-200 border border-brand-700/60 font-bold py-2.5 px-3 rounded-xl text-xs transition">
                                اللجنة التقنية الجهوية ←
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Metrics Ribbon (Clean Proportions) -->
<section class="bg-white border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-brand-600 font-mono">{{ $stats['total_clubs'] }}</div>
                <div class="text-xs font-bold text-slate-500 mt-1">نادياً معتمداً بالرابطة الجهوية</div>
            </div>
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-copper-600 font-mono">{{ $stats['total_competitions'] ?? 18 }}</div>
                <div class="text-xs font-bold text-slate-500 mt-1">بطولة ومجموعة شبانية رسمية</div>
            </div>
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-brand-700 font-mono">5</div>
                <div class="text-xs font-bold text-slate-500 mt-1">ولايات منضوية تحت لواء الرابطة</div>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-900 font-mono">{{ $stats['total_matches'] }}+</div>
                <div class="text-xs font-bold text-slate-500 mt-1">مباراة مبرمجة في الرزنامة</div>
            </div>
        </div>
    </div>
</section>

<!-- Standings & Fixtures Section -->
<section class="py-14 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Standings preview -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">مركز المنافسات</span>
                        <h3 class="text-base font-bold text-slate-900 mt-0.5">
                            ترتيب بطولة الأصاغر والأشبال - المجموعة الأولى
                        </h3>
                    </div>
                    <a href="{{ route('competitions.index') }}" class="text-xs font-bold text-brand-700 hover:underline">
                        عرض كافة الفئات ←
                    </a>
                </div>

                @if($defaultCompetition && $defaultCompetition->groups->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-right">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                                    <th class="py-2.5 px-3">#</th>
                                    <th class="py-2.5 px-3">النادي</th>
                                    <th class="py-2.5 px-2 text-center">لعب</th>
                                    <th class="py-2.5 px-2 text-center">فاز</th>
                                    <th class="py-2.5 px-2 text-center">تعادل</th>
                                    <th class="py-2.5 px-2 text-center">خسر</th>
                                    <th class="py-2.5 px-2 text-center">له</th>
                                    <th class="py-2.5 px-2 text-center">عليه</th>
                                    <th class="py-2.5 px-2 text-center">الفارق</th>
                                    <th class="py-2.5 px-3 text-center font-bold">النقاط</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($defaultCompetition->groups->first()->groupTeams as $index => $gt)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="py-2.5 px-3 font-bold {{ $index < 2 ? 'text-brand-700' : 'text-slate-400' }}">{{ $index + 1 }}</td>
                                        <td class="py-2.5 px-3 font-bold text-slate-800 flex items-center space-x-2 space-x-reverse">
                                            <img src="{{ $gt->clubTeam?->club?->logo_url }}" alt="{{ $gt->clubTeam?->club?->acronym }}" class="w-7 h-7 object-contain flex-shrink-0">
                                            <span>{{ $gt->clubTeam?->club?->short_name }}</span>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Right: Recent Matches -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">رزنامة الجولة</span>
                            <h3 class="text-base font-bold text-slate-900 mt-0.5">مباريات الشبان المجدولة</h3>
                        </div>
                        <span class="bg-forest-900 text-brand-300 text-[10px] font-bold px-2 py-0.5 rounded border border-forest-800">الجولة 7</span>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($recentMatches as $match)
                            <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white transition">
                                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                                    <span>{{ $match->match_date->format('Y-m-d') }} • {{ $match->stadium?->name ?? 'الملعب الجهوي' }}</span>
                                    <span class="font-bold {{ $match->status === 'finished' ? 'text-slate-500' : 'text-brand-700' }}">
                                        {{ $match->status === 'finished' ? 'انتهت' : 'مجدولة (' . substr($match->kickoff_time, 0, 5) . ')' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <img src="{{ $match->homeTeam?->club?->logo_url }}" alt="{{ $match->homeTeam?->club?->acronym }}" class="w-6 h-6 object-contain flex-shrink-0">
                                        <span class="font-bold text-xs text-slate-800">{{ $match->homeTeam?->club?->short_name }}</span>
                                    </div>
                                    <div class="bg-slate-900 text-white font-mono px-2.5 py-0.5 rounded text-xs font-bold">
                                        @if($match->status === 'finished')
                                            {{ $match->home_score }} - {{ $match->away_score }}
                                        @else
                                            VS
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <span class="font-bold text-xs text-slate-800">{{ $match->awayTeam?->club?->short_name }}</span>
                                        <img src="{{ $match->awayTeam?->club?->logo_url }}" alt="{{ $match->awayTeam?->club?->acronym }}" class="w-6 h-6 object-contain flex-shrink-0">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 text-center py-6">لا توجد مباريات مجدولة حالياً.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <a href="{{ route('competitions.index') }}" class="text-xs font-bold text-brand-700 hover:underline">
                        عرض الرزنامة الكاملة لكافة الفئات الشبانية ←
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Regional League Youth Program Overview -->
<section class="py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-br from-forest-950 via-forest-900 to-forest-950 rounded-3xl p-8 sm:p-12 text-white border border-brand-800 shadow-xl relative overflow-hidden">
        <div class="max-w-3xl space-y-4">
            <span class="bg-forest-900 text-brand-300 text-xs font-bold px-3 py-1 rounded border border-brand-700 inline-block">
                الرابطة الجهوية لكرة القدم بالبليدة (LRF Blida)
            </span>
            <h2 class="text-2xl sm:text-3xl font-black leading-tight">
                منظومة متكاملة لتنظيم وتطوير منافسات الفئات الشبانية
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                تسهر الرابطة الجهوية بالتنسيق مع اللجنة التقنية الجهوية (CTR) على تنظيم ومتابعة بطولات الفئات الشبانية عبر 5 ولايات، وتوفير كافة التسهيلات الرقمية للأندية لتحميل الرزنامات، وطباعة أوراق المباريات، ومتابعة الجداول والنتائج بصورة دورية ومحينة.
            </p>
            <div class="pt-3 flex flex-wrap gap-3">
                <a href="{{ route('competitions.index') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-sm">
                    تصفح مركز البطولات
                </a>
                <a href="{{ route('dtr.index') }}" class="bg-forest-900 hover:bg-forest-800 text-slate-200 border border-brand-700/60 font-bold px-6 py-2.5 rounded-xl text-xs sm:text-sm transition">
                    اللجنة التقنية الجهوية
                </a>
                <a href="{{ route('club.login') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold px-6 py-2.5 rounded-xl text-xs sm:text-sm transition border border-white/20">
                    بوابة الأندية
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
