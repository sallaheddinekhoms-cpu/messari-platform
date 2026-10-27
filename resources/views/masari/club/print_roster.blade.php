<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة التأهيل الرسمية - {{ $club->name_ar }} - فئة {{ $category }} (2026/2027)</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Tajawal', sans-serif; background: #fff; color: #111; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
            @page { size: A4; margin: 10mm; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="p-6 text-slate-900 text-xs">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 p-4 bg-slate-900 text-white rounded-2xl flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-xl">🖨️</span>
            <div>
                <h2 class="font-bold text-sm">معاينة طباعة قائمة التأهيل الرسمية المعتمدة</h2>
                <p class="text-xs text-slate-400">فئة {{ $category }} • موسم 2026 / 2027 • إجمالي: {{ $players->count() }} لاعب</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2 rounded-xl text-xs font-black bg-emerald-500 hover:bg-emerald-600 text-white transition flex items-center gap-2 shadow-sm">
                <span>طباعة الوثيقة الآن</span>
                <span>🖨️</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 transition">
                إغلاق النافذة
            </button>
        </div>
    </div>

    <!-- Official Document Container -->
    <div class="max-w-5xl mx-auto border-2 border-slate-900 p-6 rounded-xl relative bg-white">
        
        <!-- Document Official Header -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-4">
            <!-- Left: FAF & League Logo -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-16 h-16 object-contain">
                <div>
                    <h3 class="font-black text-xs text-slate-800">الاتحاد الجزائري لكرة القدم (FAF)</h3>
                    <h2 class="font-black text-sm text-slate-900">الرابطة الجهوية لكرة القدم بالبليدة</h2>
                    <p class="text-[11px] font-bold text-slate-600">Ligue Régionale de Football - Blida</p>
                </div>
            </div>

            <!-- Center: Document Title -->
            <div class="text-center">
                <span class="inline-block px-4 py-1 rounded-full text-xs font-black bg-slate-900 text-white mb-1">
                    قائمة التأهيل الرسمية للاعبين
                </span>
                <h1 class="text-base font-black text-slate-900">
                    LISTE DES JOUEURS QUALIFIÉS
                </h1>
                <p class="text-xs font-bold text-emerald-800">
                    الموسم الرياضي: 2026 / 2027 • Saison 2026/2027
                </p>
            </div>

            <!-- Right: Club Logo & Info -->
            <div class="flex items-center gap-3 text-left dir-ltr">
                <div class="text-right dir-rtl">
                    <h2 class="font-black text-sm text-slate-900">{{ $club->name_ar }}</h2>
                    <p class="text-xs font-bold text-slate-700">{{ $club->name_fr }}</p>
                    <span class="text-[11px] font-mono font-bold text-slate-500">Code: {{ $club->acronym }}</span>
                </div>
                <img src="{{ $club->logo_url }}" alt="{{ $club->name_ar }}" class="w-16 h-16 object-contain">
            </div>
        </div>

        <!-- Meta Ribbon -->
        <div class="bg-slate-100 rounded-lg p-2.5 mb-6 flex items-center justify-between text-xs font-bold border border-slate-300">
            <div><strong>النادي:</strong> {{ $club->name_ar }} ({{ $club->name_fr }})</div>
            <div><strong>الفئة:</strong> <span class="bg-emerald-700 text-white px-2 py-0.5 rounded text-xs">{{ $category }}</span></div>
            <div><strong>الولاية:</strong> {{ $club->wilaya?->name_ar ?? 'البليدة' }}</div>
            <div><strong>تعداد الفئة:</strong> {{ $players->count() }} لاعب مؤهل</div>
        </div>

        <!-- Official Cards Grid (Matching FAF Connect Report) -->
        <div class="grid grid-cols-3 gap-3">
            @foreach($players as $player)
                <div class="border border-slate-400 rounded-lg p-2 flex items-center gap-2.5 bg-white">
                    <!-- Photo -->
                    <div class="w-16 h-20 rounded bg-slate-100 border border-slate-300 flex-shrink-0 overflow-hidden relative">
                        <img src="{{ $player->photo_url }}" alt="" class="w-full h-full object-cover">
                        <span class="absolute bottom-0 inset-x-0 bg-slate-900/90 text-white text-[8px] font-mono font-bold text-center py-0.5">
                            {{ $player->primary_position }}
                        </span>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0 text-[10px] space-y-0.5">
                        <div class="font-mono font-bold text-slate-900 text-[11px] bg-slate-100 px-1 py-0.5 rounded border border-slate-200">
                            {{ $player->license_number_display ?? $player->current_license_number }}
                        </div>
                        <div class="font-black text-slate-900 truncate">
                            {{ $player->last_name_fr }} {{ $player->first_name_fr }}
                        </div>
                        <div class="text-slate-600 truncate font-semibold">
                            {{ $player->last_name_ar }} {{ $player->first_name_ar }}
                        </div>
                        <div class="text-slate-500 text-[9px] flex items-center justify-between pt-0.5 border-t border-slate-200">
                            <span>الميلاد: {{ $player->birth_date?->format('d/m/Y') }}</span>
                            @if($player->blood_type)
                                <strong class="text-red-700 font-mono">{{ $player->blood_type }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Official Signatures & Seal Section -->
        <div class="mt-8 pt-6 border-t-2 border-slate-900 grid grid-cols-3 gap-4 text-center text-xs">
            <div class="p-4 border border-slate-300 rounded-xl">
                <span class="font-black block text-slate-800 mb-10">إمضاء وختم رئيس النادي</span>
                <span class="text-[10px] text-slate-400">Le Président du Club</span>
            </div>
            <div class="p-4 border border-slate-300 rounded-xl">
                <span class="font-black block text-slate-800 mb-10">تأشيرة طبيب النادي</span>
                <span class="text-[10px] text-slate-400">Médecin du Club</span>
            </div>
            <div class="p-4 border border-slate-300 rounded-xl bg-slate-50">
                <span class="font-black block text-emerald-900 mb-10">تأشيرة وختم الرابطة الجهوية (LRFB)</span>
                <span class="text-[10px] text-slate-400">Ligue Régionale de Football - Blida</span>
            </div>
        </div>

        <!-- Barcode / Digital Footnote -->
        <div class="mt-4 pt-2 border-t border-slate-200 flex items-center justify-between text-[10px] text-slate-500 font-mono">
            <span>منصة «مساري» الرقمية • الرابطة الجهوية لكرة القدم بالبليدة</span>
            <span>تاريخ الإصدار: {{ date('Y-m-d H:i') }}</span>
            <span>وثيقة رسمية معتمدة برمز التحقق الرقمي</span>
        </div>
    </div>

</body>
</html>
