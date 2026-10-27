@extends('masari.layout')

@section('title', 'فضاء نادي ' . $club->name_ar . ' | منصة مساري')

@section('content')
<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ 
    activeTab: '{{ $activeTab }}', 
    searchQuery: '', 
    selectedPos: 'ALL' 
}">
    
    <!-- Top Club Identification Hero Banner -->
    <div class="bg-gradient-to-l from-forest-950 via-forest-900 to-forest-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-forest-800 relative overflow-hidden mb-8">
        <!-- Background subtle pitch lines & pattern -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#6ea532_1px,transparent_1px)] [background-size:16px_16px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
            <!-- Club Info -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-right gap-5">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white/10 backdrop-blur-md p-3 border border-white/20 shadow-lg flex items-center justify-center flex-shrink-0 group">
                    <img src="{{ $club->logo_url }}" alt="{{ $club->name_ar }}" class="max-w-full max-h-full object-contain filter drop-shadow">
                </div>
                <div>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-600/30 text-brand-300 border border-brand-500/40">
                            {{ $club->acronym ?: $club->short_name }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/10 text-slate-300 border border-white/10">
                            📍 ولاية {{ $club->wilaya?->name_ar ?? 'البليدة' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            حساب نادي معتمد رسمي
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        {{ $club->name_ar }}
                    </h1>
                    <p class="text-sm font-semibold text-slate-300 mt-1">
                        {{ $club->name_fr }}
                    </p>
                    <p class="text-xs text-slate-400 mt-2 flex items-center justify-center sm:justify-start gap-2">
                        <span>📧 {{ $user->email }}</span>
                        <span>•</span>
                        <span>سنة التأسيس: {{ $club->founded_year ?? '1962' }}</span>
                    </p>
                </div>
            </div>

            <!-- Logout & Quick Actions -->
            <div class="flex items-center gap-3">
                <form action="{{ route('club.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-red-500/20 text-slate-200 hover:text-red-300 border border-white/10 hover:border-red-400/30 transition flex items-center gap-2">
                        <span>🚪</span>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Metric Counter Cards in Hero -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mt-8 pt-6 border-t border-white/10">
            <div class="bg-white/5 backdrop-blur-xs p-3.5 rounded-2xl border border-white/5 text-center sm:text-right">
                <span class="text-[11px] font-bold text-slate-400 block mb-1">إجمالي المؤهلين</span>
                <div class="text-2xl font-black text-white">{{ $counts['total'] }}</div>
                <span class="text-[10px] text-brand-400 font-semibold">لاعب رسمي معتمد</span>
            </div>
            <div class="bg-white/5 backdrop-blur-xs p-3.5 rounded-2xl border border-white/5 text-center sm:text-right cursor-pointer hover:bg-white/10 transition" @click="activeTab = 'U20'">
                <span class="text-[11px] font-bold text-slate-400 block mb-1">فئة أقل من 20 (U20)</span>
                <div class="text-2xl font-black text-brand-300">{{ $counts['U20'] }}</div>
                <span class="text-[10px] text-slate-400 font-semibold">قائمة رسمية</span>
            </div>
            <div class="bg-white/5 backdrop-blur-xs p-3.5 rounded-2xl border border-white/5 text-center sm:text-right cursor-pointer hover:bg-white/10 transition" @click="activeTab = 'U17'">
                <span class="text-[11px] font-bold text-slate-400 block mb-1">فئة أقل من 17 (U17)</span>
                <div class="text-2xl font-black text-brand-300">{{ $counts['U17'] }}</div>
                <span class="text-[10px] text-slate-400 font-semibold">قائمة رسمية</span>
            </div>
            <div class="bg-white/5 backdrop-blur-xs p-3.5 rounded-2xl border border-white/5 text-center sm:text-right cursor-pointer hover:bg-white/10 transition" @click="activeTab = 'U15'">
                <span class="text-[11px] font-bold text-slate-400 block mb-1">فئة أقل من 15 (U15)</span>
                <div class="text-2xl font-black text-brand-300">{{ $counts['U15'] }}</div>
                <span class="text-[10px] text-slate-400 font-semibold">قائمة رسمية</span>
            </div>
        </div>
    </div>

    <!-- The 3 Categories Navigation Tabs -->
    <div class="bg-white rounded-2xl p-2 shadow-xs border border-slate-200 mb-8">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <button type="button" 
                        @click="activeTab = 'U20'"
                        :class="activeTab === 'U20' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-5 py-3 rounded-xl font-black text-sm transition flex items-center gap-2">
                    <span>🏆</span>
                    <span>فئة أقل من 20 سنة (U20)</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                          :class="activeTab === 'U20' ? 'bg-brand-800 text-brand-200' : 'bg-slate-200 text-slate-700'">
                        {{ $counts['U20'] }}
                    </span>
                </button>

                <button type="button" 
                        @click="activeTab = 'U17'"
                        :class="activeTab === 'U17' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-5 py-3 rounded-xl font-black text-sm transition flex items-center gap-2">
                    <span>⚡</span>
                    <span>فئة أقل من 17 سنة (U17)</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                          :class="activeTab === 'U17' ? 'bg-brand-800 text-brand-200' : 'bg-slate-200 text-slate-700'">
                        {{ $counts['U17'] }}
                    </span>
                </button>

                <button type="button" 
                        @click="activeTab = 'U15'"
                        :class="activeTab === 'U15' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
                        class="px-5 py-3 rounded-xl font-black text-sm transition flex items-center gap-2">
                    <span>🌱</span>
                    <span>فئة أقل من 15 سنة (U15)</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                          :class="activeTab === 'U15' ? 'bg-brand-800 text-brand-200' : 'bg-slate-200 text-slate-700'">
                        {{ $counts['U15'] }}
                    </span>
                </button>
            </div>

            <!-- Quick Print Official Roster for Active Tab -->
            <div class="p-1">
                <a :href="'{{ url('club/roster/print') }}/' + activeTab" target="_blank"
                   class="px-4 py-2.5 rounded-xl text-xs font-bold bg-forest-900 hover:bg-forest-950 text-brand-300 border border-brand-800/40 shadow-xs transition flex items-center gap-2">
                    <span>🖨️</span>
                    <span>طباعة القائمة الرسمية المعتمدة (<span x-text="activeTab"></span>)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Panels for Categories (U20, U17, U15) -->
    @foreach(['U20', 'U17', 'U15'] as $cat)
    <div x-show="activeTab === '{{ $cat }}'" x-cloak class="space-y-8">
        
        <!-- Section 1: Players Roster (قائمة لاعبي الفئة) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                        <span>قائمة اللاعبين المؤهلين</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs bg-brand-50 text-brand-800 border border-brand-200 font-bold">فئة {{ $cat }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        جميع اللاعبين مؤهلون رسمياً بجوازاتهم الرياضية وأرقام إجازاتهم المسجلة برابطة البليدة
                    </p>
                </div>

                <!-- Search & Position Filters -->
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative">
                        <input type="text" x-model="searchQuery" placeholder="بحث بالاسم أو رقم الإجازة..."
                               class="px-3 py-2 pr-8 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500 w-48 sm:w-60">
                        <span class="absolute right-2.5 top-2.5 text-slate-400 text-xs">🔍</span>
                    </div>

                    <select x-model="selectedPos" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="ALL">جميع المراكز</option>
                        <option value="GK">حراسة المرمى (GK)</option>
                        <option value="DEF">الدفاع (CB/LB/RB)</option>
                        <option value="MID">وسط الميدان (CM/DM/AM)</option>
                        <option value="ATT">الهجوم (ST/RW/LW)</option>
                    </select>
                </div>
            </div>

            <!-- Players Grid with Official Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($playersByCategory[$cat] as $player)
                    <div class="p-4 rounded-2xl border border-slate-200 hover:border-brand-500 bg-slate-50/50 hover:bg-white hover:shadow-md transition flex items-center gap-4 group"
                         x-show="(searchQuery === '' || '{{ strtolower($player->first_name_fr . ' ' . $player->last_name_fr . ' ' . $player->first_name_ar . ' ' . $player->last_name_ar . ' ' . ($player->current_license_number ?? '')) }}'.includes(searchQuery.toLowerCase())) && (selectedPos === 'ALL' || (selectedPos === 'GK' && '{{ $player->primary_position }}' === 'GK') || (selectedPos === 'DEF' && ['CB','LB','RB'].includes('{{ $player->primary_position }}')) || (selectedPos === 'MID' && ['DM','CM','AM'].includes('{{ $player->primary_position }}')) || (selectedPos === 'ATT' && ['ST','RW','LW','CF'].includes('{{ $player->primary_position }}')))">
                        
                        <!-- Biometric Photo -->
                        <div class="w-16 h-20 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 flex-shrink-0 relative">
                            <img src="{{ $player->photo_url }}" alt="{{ $player->full_name_ar }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent p-0.5 text-center">
                                <span class="text-[9px] font-black text-white px-1 rounded bg-brand-600/80">{{ $player->primary_position }}</span>
                            </div>
                        </div>

                        <!-- Player Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1 mb-1">
                                <span class="font-mono text-[11px] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded border border-brand-200">
                                    {{ $player->current_license_number ?? 'N° N/A' }}
                                </span>
                                @if($player->blood_type)
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-200 px-1.5 py-0.5 rounded">
                                        {{ $player->blood_type }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-sm font-black text-slate-900 truncate group-hover:text-brand-700 transition">
                                {{ $player->full_name_ar }}
                            </h3>
                            <p class="text-xs text-slate-500 truncate font-semibold">
                                {{ $player->full_name_fr }}
                            </p>

                            <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2 pt-2 border-t border-slate-200/60">
                                <span>{{ $player->birth_date?->format('Y-m-d') }} ({{ $player->age }} سنة)</span>
                                <a href="{{ route('passport.show', $player->uuid) }}" target="_blank"
                                   class="text-brand-700 hover:text-brand-900 font-bold flex items-center gap-1 hover:underline">
                                    <span>الجواز</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 font-bold">
                        لا يوجد لاعبون مسجلون حالياً في هذه الفئة.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Section 2: Matches & Fixtures for Category (رزنامة مباريات الفئة) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
                <div>
                    <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                        <span>رزنامة ومباريات البطولة</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 font-bold">فئة {{ $cat }}</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $competitionsByCategory[$cat]?->name_ar ?? 'بطولة الفئات الشبانية - الرابطة الجهوية بالبليدة' }}
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-4">الجولة</th>
                            <th class="py-3 px-4">الفريق المضيف</th>
                            <th class="py-3 px-4 text-center">النتيجة</th>
                            <th class="py-3 px-4">الفريق الضيف</th>
                            <th class="py-3 px-4">الملعب والتاريخ</th>
                            <th class="py-3 px-4 text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($matchesByCategory[$cat] as $m)
                            @php
                                $isHome = $m->homeTeam?->club_id === $club->id;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition {{ $isHome ? 'bg-brand-50/20' : '' }}">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $m->round?->name_ar ?? ('الجولة ' . ($m->round?->round_number ?? '1')) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $m->homeTeam?->club?->logo_url }}" class="w-6 h-6 object-contain" alt="">
                                        <span class="{{ $isHome ? 'font-black text-brand-800' : 'text-slate-700 font-semibold' }}">
                                            {{ $m->homeTeam?->club?->name_ar }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-black text-sm">
                                    @if($m->status === 'completed')
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800">
                                            {{ $m->home_score }} - {{ $m->away_score }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-normal text-xs">ضد</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $m->awayTeam?->club?->logo_url }}" class="w-6 h-6 object-contain" alt="">
                                        <span class="{{ !$isHome ? 'font-black text-brand-800' : 'text-slate-700 font-semibold' }}">
                                            {{ $m->awayTeam?->club?->name_ar }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    <div>{{ $m->stadium?->name_ar ?? 'الملعب البلدي' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $m->match_date ? $m->match_date->format('Y-m-d') : 'تحدد لاحقاً' }} {{ $m->kickoff_time ?? '' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('club.match-sheet.print', $m->id) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 transition shadow-2xs">
                                        <span>📄</span>
                                        <span>ورقة المقابلة</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 font-bold">
                                    لا توجد مباريات مجدولة حالياً لهذه الفئة.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 3: Standings for Category (جدول ترتيب الفئة) -->
        @if($standingsByCategory[$cat]->count() > 0)
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200">
            <h2 class="text-xl font-black text-slate-900 mb-6 pb-4 border-b border-slate-100 flex items-center gap-2">
                <span>جدول ترتيب البطولة</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 font-bold">فئة {{ $cat }}</span>
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-2.5 px-3 text-center">الرتبة</th>
                            <th class="py-2.5 px-3">النادي</th>
                            <th class="py-2.5 px-3 text-center">لعب</th>
                            <th class="py-2.5 px-3 text-center">فاز</th>
                            <th class="py-2.5 px-3 text-center">تعادل</th>
                            <th class="py-2.5 px-3 text-center">خسر</th>
                            <th class="py-2.5 px-3 text-center">له</th>
                            <th class="py-2.5 px-3 text-center">عليه</th>
                            <th class="py-2.5 px-3 text-center">الفارق</th>
                            <th class="py-2.5 px-3 text-center font-black">النقاط</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($standingsByCategory[$cat] as $st)
                            @php
                                $isCurrentClub = $st->clubTeam?->club_id === $club->id;
                            @endphp
                            <tr class="{{ $isCurrentClub ? 'bg-brand-50/70 font-black text-brand-900 border-r-4 border-brand-600' : 'hover:bg-slate-50' }} transition">
                                <td class="py-2.5 px-3 text-center font-bold">
                                    <span class="w-6 h-6 rounded-full inline-flex items-center justify-center {{ $loop->iteration <= 2 ? 'bg-amber-100 text-amber-800 font-black' : 'text-slate-600' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $st->clubTeam?->club?->logo_url }}" class="w-5 h-5 object-contain" alt="">
                                        <span>{{ $st->clubTeam?->club?->name_ar }}</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $st->played }}</td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $st->won }}</td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $st->drawn }}</td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $st->lost }}</td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $st->goals_for }}</td>
                                <td class="py-2.5 px-3 text-center font-mono">{{ $st->goals_against }}</td>
                                <td class="py-2.5 px-3 text-center font-mono dir-ltr">{{ $st->goal_difference > 0 ? '+'.$st->goal_difference : $st->goal_difference }}</td>
                                <td class="py-2.5 px-3 text-center font-black font-mono text-sm {{ $isCurrentClub ? 'text-brand-700' : 'text-slate-900' }}">{{ $st->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
    @endforeach

</div>
@endsection
