<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورقة المقابلة الرسمية - {{ $match->homeClub?->name_ar }} ضد {{ $match->awayClub?->name_ar }} ({{ $category }})</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Tajawal', sans-serif; background: #fff; color: #111; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
            @page { size: A4; margin: 8mm; }
        }
    </style>
</head>
<body class="p-6 text-slate-900 text-xs">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 p-4 bg-slate-900 text-white rounded-2xl flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-xl">📄</span>
            <div>
                <h2 class="font-bold text-sm">ورقة المقابلة الرسمية (Feuille de Match)</h2>
                <p class="text-xs text-slate-400">{{ $match->competition?->name_ar }} • الجولة {{ $match->match_day_number }} • فئة {{ $category }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2 rounded-xl text-xs font-black bg-emerald-500 hover:bg-emerald-600 text-white transition flex items-center gap-2 shadow-sm">
                <span>طباعة ورقة المقابلة</span>
                <span>🖨️</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 transition">
                إغلاق
            </button>
        </div>
    </div>

    <!-- Official Match Sheet Container -->
    <div class="max-w-5xl mx-auto border-2 border-slate-900 p-6 rounded-xl bg-white">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-3 mb-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-14 h-14 object-contain">
                <div>
                    <h3 class="font-bold text-xs text-slate-700">الاتحاد الجزائري لكرة القدم (FAF)</h3>
                    <h2 class="font-black text-sm text-slate-900">الرابطة الجهوية لكرة القدم بالبليدة</h2>
                    <p class="text-[10px] font-bold text-slate-500">Ligue Régionale de Football - Blida</p>
                </div>
            </div>

            <div class="text-center">
                <span class="inline-block px-4 py-0.5 rounded-full text-xs font-black bg-slate-900 text-white mb-1">
                    ورقة المقابلة الرسمية
                </span>
                <h1 class="text-sm font-black text-slate-900">FEUILLE DE MATCH OFFICIELLE</h1>
                <p class="text-xs font-bold text-emerald-800">فئة {{ $category }} • موسم 2026 / 2027</p>
            </div>

            <div class="text-left font-mono text-[10px] space-y-0.5">
                <div><strong>Match ID:</strong> #{{ str_pad($match->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div><strong>الجولة:</strong> {{ $match->match_day_number }}</div>
                <div><strong>التاريخ:</strong> {{ $match->scheduled_at ? $match->scheduled_at->format('Y-m-d') : date('Y-m-d') }}</div>
            </div>
        </div>

        <!-- Match Banner -->
        <div class="bg-slate-100 p-3 rounded-lg border border-slate-300 mb-4 flex items-center justify-around text-center">
            <div class="flex items-center gap-3">
                <img src="{{ $match->homeClub?->logo_url }}" class="w-10 h-10 object-contain" alt="">
                <div class="text-right">
                    <span class="text-[10px] text-slate-500 font-bold block">الفريق المضيف (Domicile)</span>
                    <strong class="text-sm font-black text-slate-900">{{ $match->homeClub?->name_ar }}</strong>
                </div>
            </div>

            <div class="px-6 py-2 bg-white rounded-lg border border-slate-300 font-mono font-black text-lg">
                {{ $match->status === 'completed' ? "{$match->home_score} - {$match->away_score}" : "ضد VS" }}
            </div>

            <div class="flex items-center gap-3">
                <div class="text-left">
                    <span class="text-[10px] text-slate-500 font-bold block">الفريق الضيف (Visiteur)</span>
                    <strong class="text-sm font-black text-slate-900">{{ $match->awayClub?->name_ar }}</strong>
                </div>
                <img src="{{ $match->awayClub?->logo_url }}" class="w-10 h-10 object-contain" alt="">
            </div>
        </div>

        <!-- Match Info Bar -->
        <div class="grid grid-cols-3 gap-2 bg-slate-50 p-2 rounded border border-slate-200 text-[11px] mb-4">
            <div><strong>البطولة:</strong> {{ $match->competition?->name_ar }}</div>
            <div><strong>الملعب:</strong> {{ $match->stadium?->name_ar ?? 'الملعب البلدي' }}</div>
            <div><strong>التوقيت:</strong> {{ $match->scheduled_at ? $match->scheduled_at->format('H:i') : '10:00' }}</div>
        </div>

        <!-- Team Roster Form (Starting 11 & Substitutes) -->
        <div class="mb-4">
            <h3 class="font-black text-xs text-slate-900 mb-2 flex items-center justify-between border-b border-slate-300 pb-1">
                <span>تعداد لاعبي نادي {{ $club->name_ar }} (المشاركون في اللقاء)</span>
                <span class="text-[10px] font-mono text-slate-500">اختر 11 أساسياً + 7 احتياطيين</span>
            </h3>

            <table class="w-full text-right text-[10px] border border-slate-400">
                <thead>
                    <tr class="bg-slate-200 text-slate-800 font-black">
                        <th class="p-1 border border-slate-400 text-center w-8">رقم</th>
                        <th class="p-1 border border-slate-400 text-center w-12">أساسي</th>
                        <th class="p-1 border border-slate-400 text-center w-12">احتياط</th>
                        <th class="p-1 border border-slate-400">اسم ولقب اللاعب</th>
                        <th class="p-1 border border-slate-400 text-center">رقم الإجازة (Licence)</th>
                        <th class="p-1 border border-slate-400 text-center">المركز</th>
                        <th class="p-1 border border-slate-400 text-center">الميلاد</th>
                        <th class="p-1 border border-slate-400 text-center w-10">قائد (C)</th>
                        <th class="p-1 border border-slate-400 text-center">إنذار / طرد</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clubPlayers->take(18) as $index => $p)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
                            <td class="p-1 border border-slate-300 text-center font-bold font-mono">{{ $index + 1 }}</td>
                            <td class="p-1 border border-slate-300 text-center">
                                <input type="checkbox" class="w-3.5 h-3.5" {{ $index < 11 ? 'checked' : '' }}>
                            </td>
                            <td class="p-1 border border-slate-300 text-center">
                                <input type="checkbox" class="w-3.5 h-3.5" {{ $index >= 11 ? 'checked' : '' }}>
                            </td>
                            <td class="p-1 border border-slate-300 font-bold">
                                {{ $p->last_name_fr }} {{ $p->first_name_fr }} ({{ $p->full_name_ar }})
                            </td>
                            <td class="p-1 border border-slate-300 text-center font-mono font-bold">
                                {{ $p->licenses->first()?->license_number ?? '26R...' }}
                            </td>
                            <td class="p-1 border border-slate-300 text-center font-bold font-mono">
                                {{ $p->primary_position }}
                            </td>
                            <td class="p-1 border border-slate-300 text-center font-mono">
                                {{ $p->birth_date?->format('Y-m-d') }}
                            </td>
                            <td class="p-1 border border-slate-300 text-center">
                                <input type="checkbox" class="w-3.5 h-3.5" {{ $index === 0 ? 'checked' : '' }}>
                            </td>
                            <td class="p-1 border border-slate-300"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Technical Staff & Referees Section -->
        <div class="grid grid-cols-2 gap-4 mb-4 text-[11px]">
            <!-- Staff -->
            <div class="border border-slate-300 rounded p-2">
                <strong class="block text-slate-800 border-b border-slate-200 pb-1 mb-2 font-black">
                    الطاقم الفني والإداري للنادي (Staff Technique & Médical)
                </strong>
                <div class="space-y-1.5 text-[10px]">
                    <div class="flex justify-between border-b border-dotted border-slate-200 pb-1">
                        <span>المدرب الرئيسي (Entraîneur):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-slate-200 pb-1">
                        <span>المدرب المساعد (Entraîneur Adjoint):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-slate-200 pb-1">
                        <span>طبيب النادي (Médecin):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                    <div class="flex justify-between">
                        <span>الكاتب العام / المرافق (Secrétaire / Dirigeant):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                </div>
            </div>

            <!-- Referees -->
            <div class="border border-slate-300 rounded p-2">
                <strong class="block text-slate-800 border-b border-slate-200 pb-1 mb-2 font-black">
                    طاقم التحكيم وتسيير اللقاء (Officiels du Match)
                </strong>
                <div class="space-y-1.5 text-[10px]">
                    <div class="flex justify-between border-b border-dotted border-slate-200 pb-1">
                        <span>الحكم الرئيسي (Arbitre Principal):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-slate-200 pb-1">
                        <span>المساعد الأول (1er Arbitre Assistant):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-slate-200 pb-1">
                        <span>المساعد الثاني (2ème Arbitre Assistant):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                    <div class="flex justify-between">
                        <span>محافظ اللقاء (Délégué du Match):</span>
                        <span class="font-bold">...................................................</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Official Signatures -->
        <div class="border-t-2 border-slate-900 pt-4 grid grid-cols-4 gap-2 text-center text-[10px]">
            <div class="p-2 border border-slate-300 rounded h-20">
                <span class="font-black block mb-8">إمضاء قائد الفريق</span>
                <span class="text-slate-400">Le Capitaine</span>
            </div>
            <div class="p-2 border border-slate-300 rounded h-20">
                <span class="font-black block mb-8">إمضاء مدرب النادي</span>
                <span class="text-slate-400">L'Entraîneur</span>
            </div>
            <div class="p-2 border border-slate-300 rounded h-20">
                <span class="font-black block mb-8">إمضاء محافظ اللقاء</span>
                <span class="text-slate-400">Le Délégué</span>
            </div>
            <div class="p-2 border border-slate-300 rounded h-20 bg-slate-50">
                <span class="font-black block mb-8 text-emerald-900">إمضاء وتأشيرة الحكم الرئيسي</span>
                <span class="text-slate-400">L'Arbitre Principal</span>
            </div>
        </div>
    </div>

</body>
</html>
