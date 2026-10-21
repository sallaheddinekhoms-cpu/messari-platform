@extends('masari.layout')

@section('title', 'نتائج البحث عن اللاعبين | منصة مساري - LRF Blida')

@section('content')
<div class="py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-8">
        <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-brand-700">← العودة للرئيسية</a>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
            نتائج البحث عن: <span class="text-brand-700">"{{ $query }}"</span>
        </h1>
        <p class="text-xs text-slate-500 mt-1">تم العثور على {{ $players->count() }} لاعب مطابق في سجل الملف الرياضي بالرابطة الجهوية.</p>
    </div>

    <!-- Search input -->
    <div class="bg-white p-3 rounded-xl border border-slate-200 mb-8 shadow-xs">
        <form action="{{ route('players.search') }}" method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ $query }}" placeholder="ابحث برقم الملف، الاسم، أو النادي..." class="flex-grow bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-xs sm:text-sm text-slate-800 focus:ring-1 focus:ring-brand-600 focus:border-brand-600">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-2 rounded-lg text-xs sm:text-sm transition shadow-sm">
                بحث
            </button>
        </form>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($players as $player)
            @php
                $catCode = $player->currentLicense?->ageCategory?->code ?? 'U17';
            @endphp
            <div class="bg-white p-4 rounded-xl border border-slate-200 hover:border-brand-500 transition flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="w-12 h-12 rounded-lg bg-brand-50 text-brand-700 border border-brand-200 font-bold flex items-center justify-center text-lg flex-shrink-0">
                        {{ mb_substr($player->first_name_ar, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center space-x-1.5 space-x-reverse">
                            <h3 class="font-bold text-slate-900 text-sm">{{ $player->full_name_ar }}</h3>
                            <span class="bg-copper-50 text-copper-700 border border-copper-200 text-[10px] font-bold px-1.5 py-0.5 rounded">{{ $player->primary_position }}</span>
                        </div>
                        <p class="text-xs text-slate-400 font-mono">{{ $player->passport_number }}</p>
                        <p class="text-xs text-slate-600 font-medium mt-0.5 flex items-center space-x-1.5 space-x-reverse">
                            <span>{{ $player->currentLicense?->club?->name_ar ?? 'النادي' }}</span>
                            <span class="bg-brand-50 text-brand-700 text-[9px] font-bold px-1.5 py-0.2 rounded border border-brand-200">
                                {{ $catCode }}
                            </span>
                        </p>
                    </div>
                </div>

                <a href="{{ route('passport.show', $player->uuid) }}" class="bg-slate-900 hover:bg-brand-600 text-white font-bold px-3.5 py-2 rounded-lg text-xs transition flex-shrink-0 shadow-xs">
                    معاينة الملف الرياضي ←
                </a>
            </div>
        @empty
            <div class="md:col-span-2 bg-white rounded-2xl p-12 text-center border border-slate-200">
                <span class="text-3xl block mb-2 text-slate-400">🔍</span>
                <h3 class="text-base font-bold text-slate-800">لم يتم العثور على أي لاعب مطابق</h3>
                <p class="text-xs text-slate-500 mt-1">تأكد من كتابة الاسم أو رقم الملف الرياضي بشكل صحيح (مثال: MESSARI-09-2026-001).</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
