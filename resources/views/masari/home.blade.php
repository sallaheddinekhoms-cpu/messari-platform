@extends('masari.layout')

@section('title', 'منصة مساري | الرابطة الجهوية لكرة القدم بالبليدة - المنظومة الرقمية للفئات الشبانية')

@section('content')
<!-- Hero Section (Harmonized with MESSARI Emblem & Pitch Identity) -->
<section class="relative bg-gradient-to-b from-slate-900 via-forest-900 to-forest-950 text-white overflow-hidden py-16 sm:py-20 border-b border-forest-800">
    <!-- Subtle architectural pitch grid -->
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(circle at center, rgba(255,255,255,0.8) 0, rgba(255,255,255,0) 65%), linear-gradient(0deg, transparent 24%, rgba(255, 255, 255, .05) 25%, rgba(255, 255, 255, .05) 26%, transparent 27%, transparent 74%, rgba(255, 255, 255, .05) 75%, rgba(255, 255, 255, .05) 76%, transparent 77%, transparent);"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-copper-600/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Text Content -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Accreditation badge with logo harmony -->
                <div class="inline-flex items-center space-x-2 space-x-reverse bg-forest-950/90 border border-brand-600/40 px-3.5 py-1.5 rounded-full text-xs font-semibold text-brand-300 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                    <span>المنظومة الرقمية الرسمية • الموسم الرياضي {{ $currentSeason?->name ?? '2026/2027' }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-5xl font-black tracking-tight leading-tight">
                    الملف الرياضي الرقمي لمواهب الفئات الشبانية
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-brand-200 to-copper-500 block mt-1">«مساري — MESSARI»</span>
                </h1>

                <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl font-normal">
                    المنظومة الرسمية المعتمدة من الرابطة الجهوية لكرة القدم بالبليدة لمتابعة وتوثيق المسار الرياضي التراكمي للاعب الشاب من <strong class="text-white font-bold">فئات البراعم والأصاغر (U13, U15)</strong> إلى <strong class="text-white font-bold">الأشبال والأواسط (U17, U19, U20)</strong>، بالتنسيق الميداني مع المديرية الفنية الجهوية (DTR).
                </p>

                <!-- Search box in Hero with high precision -->
                <div class="bg-white p-2 rounded-xl shadow-xl flex flex-col sm:flex-row gap-2 max-w-xl border border-slate-200">
                    <form action="{{ route('players.search') }}" method="GET" class="flex-grow flex items-center px-3">
                        <svg class="w-4 h-4 text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="q" placeholder="ابحث باسم اللاعب، رقم الملف (MESSARI)، أو النادي..." class="w-full text-slate-800 placeholder-slate-400 border-none focus:outline-none text-xs sm:text-sm font-medium">
                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-2.5 rounded-lg text-xs sm:text-sm transition flex-shrink-0 shadow-sm">
                            استعلام الملف الرياضي
                        </button>
                    </form>
                </div>

                <!-- Category Filters (Clean & Harmonious) -->
                <div class="space-y-2 pt-1">
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
                            <span>رزنامات 2026/2027 الرسمية (PDF):</span>
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

            <!-- Hero Visual / Official Biometric Passport Card Mockup -->
            <div class="lg:col-span-5">
                <div class="messari-passport-card rounded-2xl p-6 shadow-2xl relative text-white">
                    <!-- Top Header with Official League Seal & Masari Circular Emblem -->
                    <div class="flex items-center justify-between border-b border-forest-800 pb-4 mb-4">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-10 h-10 object-contain">
                            <div>
                                <h3 class="font-bold text-xs tracking-wide">الرابطة الجهوية لكرة القدم بالبليدة</h3>
                                <p class="text-[9px] text-brand-300 font-mono uppercase">Ligue Régionale de Football • Passeport Sportif</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <img src="{{ asset('images/masari-logo.jpg') }}" alt="MESSARI" class="w-9 h-9 rounded-full object-contain border-2 border-brand-500 bg-white shadow-xs" style="width: 36px; height: 36px; min-width: 36px; min-height: 36px; max-width: 36px; max-height: 36px;">
                            <span class="bg-forest-900 text-brand-300 border border-brand-700 text-[10px] font-bold px-2 py-0.5 rounded">
                                إجازة معتمدة
                            </span>
                        </div>
                    </div>

                    @php
                        $demoPlayer = $featuredPlayers->first();
                    @endphp

                    @if($demoPlayer)
                        <div class="flex items-start space-x-4 space-x-reverse">
                            <div class="w-22 h-26 rounded-xl bg-forest-950 border border-brand-600/70 p-1 flex-shrink-0 shadow relative">
                                <div class="w-full h-full rounded-lg overflow-hidden flex items-center justify-center text-3xl font-black bg-forest-900">
                                    @if($demoPlayer->photo_path)
                                        <img src="{{ asset('storage/' . $demoPlayer->photo_path) }}" alt="{{ $demoPlayer->full_name_ar }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-brand-300">⚽</span>
                                    @endif
                                </div>
                                <span class="absolute -bottom-2 -left-2 bg-brand-600 text-white font-bold text-[10px] px-2 py-0.5 rounded shadow-sm">
                                    {{ $demoPlayer->primary_position }}
                                </span>
                            </div>

                            <div class="flex-grow space-y-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-base text-white leading-tight">{{ $demoPlayer->full_name_ar }}</h4>
                                    <span class="text-[10px] font-mono text-brand-200 bg-forest-900 border border-brand-700 px-2 py-0.5 rounded">
                                        {{ $demoPlayer->currentLicense?->ageCategory?->code ?? 'U17' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-300 font-mono">{{ $demoPlayer->full_name_fr }}</p>
                                <p class="text-[11px] text-copper-500 font-mono font-bold">{{ $demoPlayer->passport_number }}</p>
                                <p class="text-xs font-medium text-slate-300">
                                    النادي: <strong class="text-white">{{ $demoPlayer->currentLicense?->club?->name_ar ?? 'اتحاد البليدة' }}</strong>
                                </p>
                            </div>
                        </div>

                        <!-- Harmonious Stats Strip -->
                        <div class="grid grid-cols-4 gap-2 mt-5 bg-forest-950/80 p-3 rounded-xl text-center border border-forest-800">
                            <div class="border-l border-forest-800">
                                <div class="text-[9px] text-slate-400 font-bold">الأهداف</div>
                                <div class="text-lg font-bold text-copper-500">09</div>
                            </div>
                            <div class="border-l border-forest-800">
                                <div class="text-[9px] text-slate-400 font-bold">التمريرات</div>
                                <div class="text-lg font-bold text-brand-300">04</div>
                            </div>
                            <div class="border-l border-forest-800">
                                <div class="text-[9px] text-slate-400 font-bold">المباريات</div>
                                <div class="text-lg font-bold text-white">06</div>
                            </div>
                            <div>
                                <div class="text-[9px] text-slate-400 font-bold">الدقائق</div>
                                <div class="text-lg font-bold text-brand-400 font-mono">520'</div>
                            </div>
                        </div>

                        <!-- Verification Callout -->
                        <div class="mt-4 pt-3 border-t border-forest-800 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-1.5 space-x-reverse text-slate-300 text-[11px]">
                                <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                                <span>مؤهل ومطابق طبياً برمز QR</span>
                            </div>
                            <a href="{{ route('passport.show', $demoPlayer->uuid) }}" class="inline-flex items-center space-x-1 space-x-reverse bg-brand-600 hover:bg-brand-700 text-white font-bold px-3.5 py-1.5 rounded-lg text-xs transition shadow-sm">
                                <span>معاينة الملف الرياضي</span>
                                <span>←</span>
                            </a>
                        </div>
                    @endif
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
                <div class="text-3xl font-black text-brand-600 font-mono">{{ number_format($stats['total_players']) }}+</div>
                <div class="text-xs font-bold text-slate-500 mt-1">لاعب مؤهل بجواز رياضي رقمي</div>
            </div>
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-copper-600 font-mono">{{ $stats['total_clubs'] }}</div>
                <div class="text-xs font-bold text-slate-500 mt-1">نادياً معتمداً عبر 6 ولايات</div>
            </div>
            <div class="border-l border-slate-200 last:border-none">
                <div class="text-3xl font-black text-brand-700 font-mono">6</div>
                <div class="text-xs font-bold text-slate-500 mt-1">فئات شبانية مدمجة (U20 إلى U13)</div>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-900 font-mono">{{ $stats['total_matches'] }}+</div>
                <div class="text-xs font-bold text-slate-500 mt-1">مباراة مبرمجة في الرزنامة</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Talents Section (Clean Editorial Grid with Brand Green & Copper Badges) -->
<section id="featured-talents" class="py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 pb-4 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block mb-1">
                بنك المواهب والمديرية الفنية الجهوية (DTR Hub)
            </span>
            <h2 class="text-2xl font-black text-slate-900">
                مواهب الأسبوع المعتمدة بالرابطة الجهوية بالبليدة
            </h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 max-w-md mt-2 sm:mt-0 font-medium">
            لاعبون ناشئون برزوا في جولات بطولات الفئات الصغرى وتمت معاينتهم من المديرية الفنية الجهوية.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredPlayers as $player)
            @php
                $catCode = $player->currentLicense?->ageCategory?->code ?? 'U17';
            @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200 hover:border-brand-500 transition duration-200 flex flex-col justify-between shadow-xs group">
                <div>
                    <!-- Card Top -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-slate-400 font-mono text-[10px] font-bold">
                            {{ $player->passport_number }}
                        </span>
                        <span class="bg-copper-50 text-copper-700 border border-copper-200 text-xs font-bold px-2 py-0.5 rounded">
                            {{ $player->primary_position }}
                        </span>
                    </div>

                    <!-- Player Info -->
                    <div class="flex items-center space-x-3 space-x-reverse mb-4">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 border border-brand-200 flex items-center justify-center font-bold text-lg flex-shrink-0 group-hover:scale-105 transition">
                            {{ mb_substr($player->first_name_ar, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm leading-tight group-hover:text-brand-700 transition">
                                {{ $player->full_name_ar }}
                            </h3>
                            <p class="text-xs text-slate-400 font-mono">{{ $player->full_name_fr }}</p>
                            <span class="inline-block mt-1 text-[11px] font-semibold text-brand-800">
                                {{ $player->currentLicense?->club?->name_ar ?? 'النادي' }}
                            </span>
                        </div>
                    </div>

                    <!-- Metrics -->
                    <div class="space-y-1.5 py-3 border-t border-b border-slate-100 text-xs text-slate-600">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">الفئة العمرية:</span>
                            <span class="bg-slate-100 text-slate-800 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200">
                                {{ $catCode }} ({{ $player->age }} سنة)
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">القدم المفضلة:</span>
                            <strong class="text-slate-800">{{ $player->dominant_foot === 'left' ? 'اليسرى' : ($player->dominant_foot === 'both' ? 'كلتاهما' : 'اليمنى') }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">الولاية:</span>
                            <strong class="text-slate-800">{{ $player->wilaya?->name_ar ?? 'البليدة' }}</strong>
                        </div>
                    </div>

                    <!-- DTR Rating Badge -->
                    @if($player->scoutingReports->isNotEmpty())
                        @php $rep = $player->scoutingReports->first(); @endphp
                        <div class="mt-2.5 flex items-center justify-between bg-forest-950 text-white px-3 py-1.5 rounded-xl border border-brand-600/70 shadow-2xs">
                            <span class="text-[11px] font-bold text-brand-300 flex items-center space-x-1 space-x-reverse">
                                <span>🎯</span>
                                <span>تقييم DTR:</span>
                            </span>
                            <span class="text-xs font-black font-mono text-copper-400 bg-forest-900 px-2 py-0.5 rounded border border-forest-800">
                                ⭐ {{ number_format($rep->overall_score ?: 8.5, 1) }} / 10
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="mt-4 pt-2">
                    <a href="{{ route('passport.show', $player->uuid) }}" class="w-full flex items-center justify-center space-x-1 space-x-reverse bg-slate-900 hover:bg-brand-600 text-white font-bold py-2 rounded-xl text-xs transition">
                        <span>معاينة الملف الرياضي</span>
                        <span>←</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Standings & Fixtures Section -->
<section class="py-14 bg-slate-50 border-t border-b border-slate-200">
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
                                            <span class="w-6 h-6 rounded bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-[10px] border border-slate-200">
                                                {{ $gt->clubTeam?->club?->acronym }}
                                            </span>
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
                                    <span class="font-bold text-xs text-slate-800">{{ $match->homeTeam?->club?->short_name }}</span>
                                    <div class="bg-slate-900 text-white font-mono px-2.5 py-0.5 rounded text-xs font-bold">
                                        @if($match->status === 'finished')
                                            {{ $match->home_score }} - {{ $match->away_score }}
                                        @else
                                            VS
                                        @endif
                                    </div>
                                    <span class="font-bold text-xs text-slate-800">{{ $match->awayTeam?->club?->short_name }}</span>
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

<!-- About Digital Passport -->
<section id="about-passport" class="py-14 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-forest-950 rounded-2xl p-8 sm:p-12 text-white border-2 border-brand-600 shadow-md">
        <div class="max-w-2xl space-y-4">
            <span class="bg-forest-900 text-brand-300 text-xs font-bold px-3 py-1 rounded border border-brand-700">
                الملف الرياضي الرقمي للاعب الشاب (Youth Sport File)
            </span>
            <h2 class="text-2xl sm:text-3xl font-bold leading-tight">
                بطاقة هوية رياضية رقمية موحدة لكل موهبة كروية صاعدة
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                توثيق كامل المسار الكروي منذ أول إجازة في فئة البراعم U13، متضمناً الدقائق الملعوبة، الأهداف، التمريرات، والشهادة الطبية المؤهلة، مع رمز QR للتحقق الميداني السريع من طرف الحكام ومندوبي المباريات.
            </p>
            <div class="pt-3 flex flex-wrap gap-3">
                <a href="{{ route('players.search') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-2.5 rounded-lg text-xs sm:text-sm transition shadow-sm">
                    التحقق من إجازة لاعب
                </a>
                <a href="/admin" class="bg-forest-900 hover:bg-forest-800 text-slate-200 font-bold px-6 py-2.5 rounded-lg text-xs sm:text-sm transition border border-forest-800">
                    بوابة مسؤولي النوادي
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
