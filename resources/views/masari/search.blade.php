@extends('masari.layout')

@section('title', 'البحث في سجلات اللاعبين | منصة مساري - LRF Blida')

@section('content')
<div class="py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-8">
        <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-brand-700">← العودة للرئيسية</a>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
            البحث في سجلات اللاعبين والمواهب
        </h1>
        <p class="text-xs text-slate-500 mt-1">المنظومة الرقمية للملف الرياضي الشاب • الرابطة الجهوية لكرة القدم بالبليدة.</p>
    </div>

    @if(!$isAuthorized)
        <!-- Access Restricted Security Box -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden text-center p-8 sm:p-12 mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-3xl text-amber-600">
                🔒
            </div>
            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full inline-block mb-3">
                خاص بالأطر التقنية والأندية المعتمدة
            </span>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2">
                البحث في قاعدة بيانات اللاعبين مقيّد بالاعتماد التقني
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto mb-6 leading-relaxed">
                حماية للمعطيات الشخصية والبيومترية للناشئين (U13 - U20)، يقتصر البحث في السجلات والملفات الرياضية على أعضاء المديرية الفنية الجهوية (DTR)، ومسؤولي الرابطة، وممثلي الأندية المخولين.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto">
                <a href="/admin/login" class="w-full sm:w-auto bg-forest-900 hover:bg-forest-950 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition border border-brand-700">
                    دخول المديرية الفنية ولجنة الرابطة (DTR) ←
                </a>
                <a href="{{ route('club.login') }}" class="w-full sm:w-auto bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition">
                    دخول بوابة الأندية المعتمدة ←
                </a>
            </div>
        </div>
    @else
        <!-- Authorized Search Input -->
        <div class="bg-white p-3 rounded-xl border border-slate-200 mb-8 shadow-xs">
            <form action="{{ route('players.search') }}" method="GET" class="flex gap-2">
                <input type="text" name="q" value="{{ $query }}" placeholder="ابحث برقم الملف الرياضي، الاسم، أو النادي..." class="flex-grow bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-xs sm:text-sm text-slate-800 focus:ring-1 focus:ring-brand-600 focus:border-brand-600">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-2 rounded-lg text-xs sm:text-sm transition shadow-sm">
                    بحث
                </button>
            </form>
        </div>

        @if(!empty($query))
            <div class="mb-4 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">نتائج البحث عن: <span class="text-brand-700">"{{ $query }}"</span> ({{ $players->count() }} لاعب)</span>
                @if(auth()->user()->isClub())
                    <span class="text-[11px] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded border border-brand-200">
                        مفلترة حسب نادي: {{ auth()->user()->club?->name_ar }}
                    </span>
                @endif
            </div>

            <!-- Results Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($players as $player)
                    @php
                        $catCode = $player->currentLicense?->ageCategory?->code ?? 'U17';
                    @endphp
                    <div class="bg-white p-4 rounded-xl border border-slate-200 hover:border-brand-500 transition flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="w-12 h-14 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center flex-shrink-0 relative shadow-2xs">
                                @if($player->photo_path)
                                    <img src="{{ $player->photo_url }}" alt="{{ $player->full_name_ar }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-brand-700 font-bold text-base">{{ mb_substr($player->first_name_ar, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center space-x-1.5 space-x-reverse">
                                    <h3 class="font-bold text-slate-900 text-sm">{{ $player->full_name_ar }}</h3>
                                    <span class="bg-copper-50 text-copper-700 border border-copper-200 text-[10px] font-bold px-1.5 py-0.5 rounded">{{ $player->primary_position }}</span>
                                </div>
                                <p class="text-xs text-slate-500 font-mono flex items-center gap-2">
                                    <span>{{ $player->full_name_fr }}</span>
                                    @if($player->currentLicense)
                                        <span class="text-brand-700 font-bold">({{ $player->currentLicense->license_number }})</span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-600 font-medium mt-0.5 flex items-center space-x-1.5 space-x-reverse">
                                    <img src="{{ $player->currentLicense?->club?->logo_url }}" alt="{{ $player->currentLicense?->club?->acronym }}" class="w-5 h-5 object-contain inline-block">
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
                        <p class="text-xs text-slate-500 mt-1">تأكد من كتابة الاسم أو رقم الملف الرياضي بشكل صحيح.</p>
                    </div>
                @endforelse
            </div>
        @endif
    @endif

</div>
@endsection
