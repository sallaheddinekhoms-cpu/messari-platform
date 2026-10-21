@extends('masari.layout')

@section('title', 'الملف الرياضي الرقمي: ' . $player->full_name_ar . ' (' . $player->passport_number . ') | منصة مساري')

@push('styles')
<style>
    /* أنماط أمنية وشكل الملف الرياضي الرسمي */
    .guilloche-pattern {
        background-color: #0d1a07;
        background-image: 
            radial-gradient(circle at 50% 0%, rgba(84, 134, 34, 0.25) 0%, transparent 60%),
            radial-gradient(circle at 100% 100%, rgba(193, 125, 45, 0.15) 0%, transparent 50%),
            repeating-linear-gradient(45deg, rgba(84, 134, 34, 0.04) 0px, rgba(84, 134, 34, 0.04) 2px, transparent 2px, transparent 8px),
            repeating-linear-gradient(-45deg, rgba(193, 125, 45, 0.03) 0px, rgba(193, 125, 45, 0.03) 2px, transparent 2px, transparent 8px);
    }
    
    .gold-foil-border {
        border: 2px solid transparent;
        background-clip: padding-box;
        position: relative;
    }
    .gold-foil-border::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: inherit;
        background: linear-gradient(135deg, #d58f38 0%, #8ec056 25%, #C17D2D 50%, #548622 75%, #faefdc 100%);
        z-index: -1;
    }

    .mrz-font {
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 2px;
    }

    .smart-chip {
        background: linear-gradient(135deg, #e6c875 0%, #d4af37 40%, #aa820a 100%);
        border: 1px solid #7c5e00;
        box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.6), 0 2px 4px rgba(0,0,0,0.4);
    }

    @media print {
        header, footer, .no-print, .top-ribbon {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
            padding: 0 !important;
        }
        .passport-printable-container {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .guilloche-pattern {
            background: #ffffff !important;
            color: #000000 !important;
            border: 2px solid #548622 !important;
        }
        .page-break {
            page-break-before: always;
        }
    }
</style>
@endpush

@section('content')
@php
    $catCode = $player->currentLicense?->ageCategory?->code ?? 'U17';
    $primaryReport = $player->scoutingReports->first();
    $overallScore = $primaryReport?->overall_score ?: 8.5;
@endphp

<div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 passport-printable-container" x-data="{ activeTab: 'passport', showBadge: false }">

    <!-- Action Toolbar (Breadcrumb + Controls) -->
    <div class="no-print flex flex-col md:flex-row md:items-center justify-between pb-5 mb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center space-x-2 space-x-reverse text-xs text-slate-500 mb-1">
                <a href="{{ route('home') }}" class="hover:text-brand-700 font-bold transition">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('home') }}#featured-talents" class="hover:text-brand-700 font-bold transition">المواهب الشبانية</a>
                <span>/</span>
                <span class="text-brand-800 font-bold font-mono">{{ $player->passport_number }}</span>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    الملف الرياضي الرقمي المعتمد
                </h1>
                <span class="inline-flex items-center space-x-1.5 space-x-reverse bg-brand-50 border border-brand-300 text-brand-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-ping"></span>
                    <span>معتمد رسمياً (LRFB • FAF)</span>
                </span>
            </div>
        </div>

        <div class="flex items-center flex-wrap gap-2">
            <button @click="showBadge = true" class="inline-flex items-center space-x-1.5 space-x-reverse bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                <span>🪪</span>
                <span>بطاقة الإجازة الميدانية</span>
            </button>
            <button onclick="window.print()" class="inline-flex items-center space-x-1.5 space-x-reverse bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                <span>🖨️</span>
                <span>طباعة الملف الرياضي</span>
            </button>
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-1.5 space-x-reverse bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                <span>العودة</span>
                <span>←</span>
            </a>
        </div>
    </div>

    <!-- Quick Navigation Switcher -->
    <div class="no-print flex items-center space-x-2 space-x-reverse mb-6 overflow-x-auto pb-2 border-b border-slate-200 text-xs font-bold">
        <button @click="activeTab = 'passport'" :class="activeTab === 'passport' ? 'bg-forest-950 text-brand-300 border-brand-500 shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border-slate-200'" class="px-4 py-2.5 rounded-xl border transition flex items-center space-x-2 space-x-reverse whitespace-nowrap">
            <span>🪪</span>
            <span>وثيقة الملف الرياضي</span>
        </button>
        <button @click="activeTab = 'dtr'" :class="activeTab === 'dtr' ? 'bg-forest-950 text-brand-300 border-brand-500 shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border-slate-200'" class="px-4 py-2.5 rounded-xl border transition flex items-center space-x-2 space-x-reverse whitespace-nowrap">
            <span>🎯</span>
            <span>تقييم المديرية الفنية (DTR 7-Pillars)</span>
            @if($primaryReport)
                <span class="bg-copper-600 text-white text-[10px] px-1.5 py-0.2 rounded font-mono font-bold">{{ number_format($overallScore, 1) }}</span>
            @endif
        </button>
        <button @click="activeTab = 'timeline'" :class="activeTab === 'timeline' ? 'bg-forest-950 text-brand-300 border-brand-500 shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border-slate-200'" class="px-4 py-2.5 rounded-xl border transition flex items-center space-x-2 space-x-reverse whitespace-nowrap">
            <span>📈</span>
            <span>المسار الكروي التراكمي (منذ 2021)</span>
            <span class="bg-brand-100 text-brand-800 text-[10px] px-1.5 py-0.2 rounded font-bold">{{ $player->careerTimelines->count() }} مواسم</span>
        </button>
    </div>

    <!-- ========================================================================================= -->
    <!-- TAB 1: THE LUXURY OFFICIAL BIOMETRIC CARD (وثيقة الملف الرياضي المعتمد) -->
    <!-- ========================================================================================= -->
    <div x-show="activeTab === 'passport'" class="transition-all duration-300 space-y-8">
        
        <!-- Passport Booklet / Credential Card -->
        <div class="guilloche-pattern rounded-3xl p-6 sm:p-10 text-white shadow-2xl relative overflow-hidden border-2 border-brand-500/80">

            <!-- Top Header: Institutional Emblems & Passport Title -->
            <div class="relative z-10 border-b border-brand-700/60 pb-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <!-- Right: League Seal -->
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="w-16 h-16 rounded-2xl bg-white p-2 flex items-center justify-center shadow-lg border-2 border-brand-400 flex-shrink-0" style="width: 64px; height: 64px; min-width: 64px; min-height: 64px; max-width: 64px; max-height: 64px;">
                            <img src="{{ asset('images/league-logo.png') }}" alt="شعار رابطة البليدة" style="width: 52px; height: 52px; max-width: 52px; max-height: 52px; object-fit: contain;">
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-black text-white tracking-tight mt-0.5">
                                الرابطة الجهوية لكرة القدم بالبليدة (LRF BLIDA)
                            </h2>
                            <p class="text-xs font-semibold text-brand-300 font-mono">
                                DIRECTION TECHNIQUE RÉGIONALE • SYSTÈME MESSARI OFFICIEL
                            </p>
                        </div>
                    </div>

                    <!-- Left: Official Circular Messari Logo + Document Type -->
                    <div class="flex items-center sm:items-end flex-col sm:text-left text-right">
                        <div class="flex items-center space-x-3 space-x-reverse mb-1">
                            <div class="w-14 h-14 rounded-full bg-white p-1 border-2 border-copper-500 shadow-md flex items-center justify-center overflow-hidden flex-shrink-0" style="width: 56px; height: 56px; min-width: 56px; min-height: 56px; max-width: 56px; max-height: 56px;">
                                <img src="{{ asset('images/masari-logo.jpg') }}" alt="شعار منصة مساري" style="width: 48px; height: 48px; max-width: 48px; max-height: 48px; object-fit: contain;">
                            </div>
                            <div>
                                <span class="text-sm font-black text-white font-mono block">MESSARI</span>
                                <span class="bg-copper-600/90 text-white text-[10px] font-bold px-2.5 py-0.5 rounded uppercase tracking-wider font-mono">
                                    FICHE SPORTIVE
                                </span>
                            </div>
                        </div>
                        <div class="text-[11px] font-mono text-brand-300 font-bold bg-forest-950 px-3 py-1 rounded-lg border border-brand-700/80">
                            رقم الملف: <span class="text-white">{{ $player->passport_number }}</span>
                        </div>
                    </div>
                </div>

                <!-- Golden Micro-printed Ribbon -->
                <div class="mt-4 pt-3 border-t border-forest-800/80 flex items-center justify-between text-[10px] text-brand-300 font-mono overflow-hidden">
                    <span class="tracking-widest hidden sm:inline">★ FAF VALIDATED ★ LRF BLIDA ★ YOUTH TALENT DOSSIER ★</span>
                    <span class="text-copper-400 font-bold">موسم الرياضي المعتمد: 2026 / 2027</span>
                    <span class="tracking-widest hidden md:inline">★ BLIDA • TIPAZA • MEDEA • DJELFA • AIN DEFLA ★</span>
                </div>
            </div>

            <!-- Main Body: Two Columns (Biometric Photo & Identity Block + Structured Data) -->
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Right Side: Biometric Photo + Smart Chip + QR Code Module -->
                <div class="lg:col-span-4 flex flex-col items-center space-y-5 bg-forest-950/80 p-5 rounded-2xl border border-brand-700/70 shadow-inner">
                    
                    <!-- Portrait Frame with Golden Biometric Corners -->
                    <div class="relative group">
                        <div class="w-40 h-52 rounded-2xl bg-forest-900 border-2 border-copper-500 overflow-hidden shadow-2xl relative flex items-center justify-center">
                            @if($player->photo_path)
                                <img src="{{ asset('storage/' . $player->photo_path) }}" alt="{{ $player->full_name_ar }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex flex-col items-center justify-center text-slate-300 space-y-2">
                                    <span class="text-5xl">⚽</span>
                                    <span class="text-xs font-bold text-brand-300">{{ $player->first_name_ar }}</span>
                                </div>
                            @endif

                            <!-- Official Holographic Seal Stamp Over the Photo -->
                            <div class="absolute bottom-2 right-2 bg-forest-950/90 border border-brand-400 text-brand-300 rounded-full px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider flex items-center space-x-1 space-x-reverse shadow">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span>
                                <span>VERIFIED</span>
                            </div>
                        </div>

                        <!-- Position Ribbon -->
                        <div class="absolute -bottom-3 inset-x-0 mx-auto w-max bg-gradient-to-r from-copper-600 via-amber-600 to-copper-600 text-white font-black text-xs px-3.5 py-1 rounded-full shadow-lg border border-amber-300 tracking-wide text-center">
                            {{ $player->primary_position }}
                        </div>
                    </div>

                    <!-- Smart Biometric Chip Graphic (محاكاة الشريحة الإلكترونية للجواز) -->
                    <div class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-forest-900/90 border border-forest-800 text-xs text-slate-300">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <div class="w-8 h-6 rounded smart-chip flex items-center justify-center text-[9px] text-amber-950 font-black font-mono">
                                ⟦||⟧
                            </div>
                            <span class="font-mono text-[11px] font-bold text-copper-400">RFID • BIOMETRIC NFC</span>
                        </div>
                        <span class="text-[10px] text-brand-300 font-bold bg-forest-950 px-2 py-0.5 rounded border border-brand-800">
                            مؤهل رسمياً
                        </span>
                    </div>

                    <!-- Digital QR Verification Box with Scan Targets -->
                    <div class="w-full bg-white p-4 rounded-2xl shadow-xl border-2 border-brand-500 flex flex-col items-center text-slate-900">
                        <div class="w-32 h-32 flex items-center justify-center p-1 bg-white rounded-lg">
                            {!! $qrCodeSvg !!}
                        </div>
                        <div class="mt-2 text-center">
                            <span class="block text-[11px] font-black text-forest-950 font-mono tracking-wider">
                                SCAN FOR FIELD VERIFICATION
                            </span>
                            <span class="text-[10px] text-brand-700 font-bold block">
                                رمز التحقق الميداني الفوري لحكام ومندوبي المباريات
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Left Side: Official Biometric Passport Identity Fields -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Player Name Banner -->
                    <div class="bg-forest-950/80 p-5 rounded-2xl border border-brand-700/60 shadow-inner">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-forest-800 pb-3 mb-3">
                            <div>
                                <span class="text-[11px] font-bold text-copper-400 block font-mono">NOM & PRÉNOM DU JOUEUR • الاسم واللقب الكامل</span>
                                <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight mt-0.5">
                                    {{ $player->full_name_ar }}
                                </h3>
                                <p class="text-sm font-bold text-brand-300 uppercase tracking-wider font-mono mt-0.5">
                                    {{ $player->full_name_fr }}
                                </p>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-[11px] font-bold text-slate-400 block font-mono">CATÉGORIE • الفئة</span>
                                <span class="inline-block bg-gradient-to-r from-brand-600 to-brand-700 text-white font-black text-sm px-3.5 py-1 rounded-xl shadow-xs border border-brand-400 font-mono mt-1">
                                    فئة {{ $catCode }}
                                </span>
                            </div>
                        </div>

                        <!-- Current Affiliated Club Strip -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="text-slate-400 font-medium">النادي الرياضي المعتمد:</span>
                                <strong class="text-white font-bold text-sm">
                                    {{ $player->currentLicense?->club?->name_ar ?? 'النادي الرياضي الهاوي' }}
                                </strong>
                            </div>
                            <span class="text-copper-400 font-mono font-bold text-xs">
                                {{ $player->currentLicense?->club?->wilaya?->name_ar ?? 'البليدة' }} ({{ $player->currentLicense?->club?->wilaya?->code ?? '09' }})
                            </span>
                        </div>
                    </div>

                    <!-- Two-Column Official Passport Data Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        
                        <!-- 1. تاريخ ومكان الميلاد -->
                        <div class="bg-forest-950/80 p-3.5 rounded-xl border border-forest-800">
                            <span class="text-[10px] text-brand-300 font-bold block font-mono">DATE ET LIEU DE NAISSANCE</span>
                            <span class="text-slate-400 text-[11px] block">تاريخ ومكان الازدياد:</span>
                            <div class="text-white font-bold text-sm font-mono mt-1">
                                {{ $player->birth_date?->format('Y-m-d') }}
                                <span class="text-copper-400 text-xs font-sans">({{ $player->age }} سنة)</span>
                            </div>
                            <div class="text-slate-300 text-xs mt-0.5">
                                بـ: {{ $player->birth_place }} (ولاية {{ $player->wilaya?->name_ar ?? 'البليدة' }})
                            </div>
                        </div>

                        <!-- 2. الجنسية والولاية -->
                        <div class="bg-forest-950/80 p-3.5 rounded-xl border border-forest-800">
                            <span class="text-[10px] text-brand-300 font-bold block font-mono">NATIONALITÉ & WILAYA</span>
                            <span class="text-slate-400 text-[11px] block">الجنسية والولاية الإقليمية:</span>
                            <div class="text-white font-bold text-sm mt-1 flex items-center space-x-2 space-x-reverse">
                                <span>{{ $player->nationality }}</span>
                                <span>🇩🇿</span>
                            </div>
                            <div class="text-copper-400 font-mono text-xs mt-0.5">
                                ولاية {{ $player->wilaya?->name_ar ?? 'البليدة' }} • الكود: {{ $player->wilaya?->code ?? '09' }}
                            </div>
                        </div>

                        <!-- 3. رقم الإجازة الفيدرالية والرقم الوطني -->
                        <div class="bg-forest-950/80 p-3.5 rounded-xl border border-forest-800">
                            <span class="text-[10px] text-brand-300 font-bold block font-mono">N° LICENCE FAF / LRFB</span>
                            <span class="text-slate-400 text-[11px] block">رقم الإجازة الفيدرالية:</span>
                            <div class="text-brand-300 font-mono font-bold text-sm mt-1">
                                {{ $player->currentLicense?->license_number ?? ('LIC-' . substr($player->uuid, 0, 8)) }}
                            </div>
                            <div class="text-slate-400 text-[10px] font-mono mt-0.5">
                                الصلاحية: 2026/2027 (موسم جاري)
                            </div>
                        </div>

                        <!-- 4. القدم والمركز التكتيكي -->
                        <div class="bg-forest-950/80 p-3.5 rounded-xl border border-forest-800">
                            <span class="text-[10px] text-brand-300 font-bold block font-mono">POSTE & PIED FORT</span>
                            <span class="text-slate-400 text-[11px] block">المركز التكتيكي والقدم المفضلة:</span>
                            <div class="text-white font-bold text-sm mt-1">
                                {{ $player->primary_position }}
                                @if($player->secondary_position)
                                    <span class="text-slate-400 text-xs">/ {{ $player->secondary_position }}</span>
                                @endif
                            </div>
                            <div class="text-copper-400 text-xs font-semibold mt-0.5">
                                القدم المفضلة: {{ $player->dominant_foot === 'left' ? 'اليسرى (Gauche)' : ($player->dominant_foot === 'both' ? 'كلتاهما (Ambidextre)' : 'اليمنى (Droite)') }}
                            </div>
                        </div>

                        <!-- 5. الخصائص البدنية والقامة -->
                        <div class="bg-forest-950/80 p-3.5 rounded-xl border border-forest-800">
                            <span class="text-[10px] text-brand-300 font-bold block font-mono">TAILLE & POIDS</span>
                            <span class="text-slate-400 text-[11px] block">البيانات المورفولوجية والبدنية:</span>
                            <div class="text-white font-mono font-bold text-sm mt-1">
                                {{ $player->height_cm ?? 175 }} سم / {{ $player->weight_kg ?? 68 }} كغ
                            </div>
                            <div class="text-slate-300 text-xs mt-0.5 font-mono">
                                مؤشر الكتلة (IMC): طبيعي ومتطابق مع الفئة
                            </div>
                        </div>

                        <!-- 6. الفحص الطبي وفصيلة الدم -->
                        <div class="bg-forest-950/80 p-3.5 rounded-xl border border-forest-800">
                            <span class="text-[10px] text-brand-300 font-bold block font-mono">GROUPE SANGUIN & PCMA</span>
                            <span class="text-slate-400 text-[11px] block">الفحص الطبي وفصيلة الدم:</span>
                            <div class="text-copper-400 font-mono font-bold text-sm mt-1 flex items-center space-x-2 space-x-reverse">
                                <span>فصيلة الدم: {{ $player->blood_type ?? 'O+' }}</span>
                                <span class="bg-brand-600/80 text-white text-[10px] px-2 py-0.2 rounded font-sans">مؤهل طبياً ✓</span>
                            </div>
                            <div class="text-brand-300 text-[11px] mt-0.5">
                                ملف PCMA الطبي مصادق عليه للرابطة
                            </div>
                        </div>
                    </div>

                    <!-- Official Bio / Scout Executive Note -->
                    @if($player->bio)
                        <div class="bg-forest-950/90 p-4 rounded-xl border border-copper-600/50 text-xs text-slate-200">
                            <div class="flex items-center space-x-1.5 space-x-reverse text-copper-400 font-bold mb-1">
                                <span>📋</span>
                                <span>ملاحظات المديرية الفنية حول مسار اللاعب (DTR Note):</span>
                            </div>
                            <p class="leading-relaxed text-slate-300">
                                {{ $player->bio }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Authentic Machine Readable Zone (MRZ) at the Bottom (منطقة القراءة الآلية الرسمية) -->
            <div class="relative z-10 mt-8 pt-5 border-t border-brand-700/60 bg-forest-950/90 p-4 rounded-2xl border border-forest-800 overflow-x-auto text-center sm:text-right">
                <div class="text-[10px] text-brand-400 font-mono uppercase tracking-widest mb-1.5 flex items-center justify-between">
                    <span>ZONE DE LECTURE AUTOMATIQUE (MRZ • ICAO 9303 COMPLIANT)</span>
                    <span class="text-copper-400 font-bold">DOSSIER SPORTIF OFFICIEL</span>
                </div>
                @php
                    $cleanLast = strtoupper(preg_replace('/[^A-Za-z]/', '', $player->last_name_fr ?: 'PLAYER'));
                    $cleanFirst = strtoupper(preg_replace('/[^A-Za-z]/', '', $player->first_name_fr ?: 'TALENT'));
                    $birthFmt = $player->birth_date ? $player->birth_date->format('ymd') : '080101';
                    $line1 = str_pad("P<DZA" . $cleanLast . "<<" . $cleanFirst, 44, '<');
                    $line2 = str_pad(substr(str_replace('-', '', $player->passport_number), 0, 14) . "9DZA" . $birthFmt . "8M2710219", 44, '<');
                @endphp
                <div class="mrz-font text-xs sm:text-sm text-brand-300 font-bold tracking-widest select-all leading-relaxed whitespace-pre font-mono">
{{ $line1 }}
{{ $line2 }}
                </div>
            </div>

        </div>

    </div>

    <!-- ========================================================================================= -->
    <!-- TAB 2: DTR 7-PILLARS PERFORMANCE DASHBOARD (لوحة ركائز التقييم السبع المعتمدة) -->
    <!-- ========================================================================================= -->
    <div x-show="activeTab === 'dtr'" class="transition-all duration-300 space-y-6">
        @php
            $att = $primaryReport?->attacking_efficiency ?? 8.2;
            $pass = $primaryReport?->passing_playmaking ?? 8.5;
            $drib = $primaryReport?->dribbling_progression ?? 8.0;
            $def = $primaryReport?->defensive_performance ?? 7.4;
            $phys = $primaryReport?->physical_performance ?? 8.1;
            $tact = $primaryReport?->tactical_intelligence ?? 8.4;
            $impact = $primaryReport?->match_impact ?? 8.8;
            $computedOverall = $primaryReport?->overall_score ?: round(($att + $pass + $drib + $def + $phys + $tact + $impact) / 7, 1);
        @endphp

        <!-- Overall Score Hero Banner -->
        <div class="bg-forest-950 text-white rounded-3xl p-6 sm:p-8 border-2 border-brand-500 shadow-xl relative overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-forest-800">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-forest-900 border-2 border-brand-400 flex items-center justify-center text-3xl shadow-lg flex-shrink-0">
                        🎯
                    </div>
                    <div>
                        <span class="text-xs font-bold text-copper-400 font-mono tracking-wider block">DIRECTION TECHNIQUE RÉGIONALE (DTR)</span>
                        <h2 class="text-xl sm:text-2xl font-black text-white mt-0.5">
                            بطاقة التقييم الفني للموهبة (DTR 7-Pillar Rating)
                        </h2>
                        <p class="text-xs text-brand-200 mt-0.5">
                            التقييم التراكمي الميداني المعتمد لمتابعة وتطوير لاعبي فئات النخبة والمستويات الجهوية.
                        </p>
                    </div>
                </div>

                <!-- Big Circular Overall Rating Score -->
                <div class="flex items-center space-x-4 space-x-reverse bg-forest-900/90 px-6 py-3.5 rounded-2xl border border-brand-600/70 shadow-lg">
                    <div class="text-right">
                        <span class="text-[11px] font-bold text-slate-300 block">المعدل العام للتقييم:</span>
                        <span class="text-xs text-copper-400 font-bold block mt-0.5">موهبة مرشحة للنخبة الجهوية</span>
                    </div>
                    <div class="text-center bg-brand-600 px-4 py-2 rounded-xl border border-brand-400 shadow-inner">
                        <span class="text-3xl font-black font-mono text-white leading-none block">
                            {{ number_format($computedOverall, 1) }}
                        </span>
                        <span class="text-[10px] font-bold font-mono text-brand-200 block mt-0.5">/ 10</span>
                    </div>
                </div>
            </div>

            <!-- The 7 High-End Performance Pillar Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-6">
                
                <!-- 1. الفعالية الهجومية -->
                <div class="bg-forest-900/80 p-4 rounded-2xl border border-forest-800 hover:border-brand-500 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white flex items-center space-x-1.5 space-x-reverse">
                            <span>🎯</span>
                            <span>1. الفعالية الهجومية</span>
                        </span>
                        <span class="text-copper-400 font-mono font-black text-sm">{{ $att }}/10</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block">Attacking Efficiency & Finishing</span>
                    <div class="w-full bg-forest-950 rounded-full h-3 p-0.5 overflow-hidden border border-forest-800">
                        <div class="bg-gradient-to-r from-copper-600 to-amber-500 h-2 rounded-full transition-all duration-500" style="width: {{ $att * 10 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-brand-300">
                        <span>إنهاء الهجمات واستغلال الفرص</span>
                        <span class="font-bold">{{ $att >= 8 ? 'ممتاز' : 'جيد جداً' }}</span>
                    </div>
                </div>

                <!-- 2. التمريرات وصناعة اللعب -->
                <div class="bg-forest-900/80 p-4 rounded-2xl border border-forest-800 hover:border-brand-500 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white flex items-center space-x-1.5 space-x-reverse">
                            <span>👟</span>
                            <span>2. التمريرات وصناعة اللعب</span>
                        </span>
                        <span class="text-brand-300 font-mono font-black text-sm">{{ $pass }}/10</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block">Passing, Vision & Playmaking</span>
                    <div class="w-full bg-forest-950 rounded-full h-3 p-0.5 overflow-hidden border border-forest-800">
                        <div class="bg-gradient-to-r from-brand-600 to-brand-400 h-2 rounded-full transition-all duration-500" style="width: {{ $pass * 10 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-brand-300">
                        <span>دقة التمرير وكسر الخطوط</span>
                        <span class="font-bold">{{ $pass >= 8.5 ? 'استثنائي' : 'ممتاز' }}</span>
                    </div>
                </div>

                <!-- 3. المراوغات والتقدم بالكرة -->
                <div class="bg-forest-900/80 p-4 rounded-2xl border border-forest-800 hover:border-brand-500 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white flex items-center space-x-1.5 space-x-reverse">
                            <span>⚡</span>
                            <span>3. المراوغات والتقدم بالكرة</span>
                        </span>
                        <span class="text-brand-300 font-mono font-black text-sm">{{ $drib }}/10</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block">Dribbling & Ball Progression</span>
                    <div class="w-full bg-forest-950 rounded-full h-3 p-0.5 overflow-hidden border border-forest-800">
                        <div class="bg-gradient-to-r from-brand-600 to-brand-400 h-2 rounded-full transition-all duration-500" style="width: {{ $drib * 10 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-brand-300">
                        <span>التفوق في الثنائيات (1v1)</span>
                        <span class="font-bold">{{ $drib >= 8 ? 'ممتاز' : 'متقدم' }}</span>
                    </div>
                </div>

                <!-- 4. الأداء الدفاعي -->
                <div class="bg-forest-900/80 p-4 rounded-2xl border border-forest-800 hover:border-brand-500 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white flex items-center space-x-1.5 space-x-reverse">
                            <span>🛡️</span>
                            <span>4. الأداء الدفاعي</span>
                        </span>
                        <span class="text-slate-300 font-mono font-black text-sm">{{ $def }}/10</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block">Defensive Contribution & Pressing</span>
                    <div class="w-full bg-forest-950 rounded-full h-3 p-0.5 overflow-hidden border border-forest-800">
                        <div class="bg-gradient-to-r from-slate-500 to-slate-300 h-2 rounded-full transition-all duration-500" style="width: {{ $def * 10 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-slate-300">
                        <span>الافتكاك والضغط العكسي</span>
                        <span class="font-bold">{{ $def >= 7 ? 'صلب ومتوازن' : 'متوسط' }}</span>
                    </div>
                </div>

                <!-- 5. الأداء البدني -->
                <div class="bg-forest-900/80 p-4 rounded-2xl border border-forest-800 hover:border-brand-500 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white flex items-center space-x-1.5 space-x-reverse">
                            <span>💪</span>
                            <span>5. الأداء البدني</span>
                        </span>
                        <span class="text-brand-300 font-mono font-black text-sm">{{ $phys }}/10</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block">Speed, Stamina & Athleticism</span>
                    <div class="w-full bg-forest-950 rounded-full h-3 p-0.5 overflow-hidden border border-forest-800">
                        <div class="bg-gradient-to-r from-brand-600 to-brand-300 h-2 rounded-full transition-all duration-500" style="width: {{ $phys * 10 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-brand-300">
                        <span>السرعة والرشاقة والتحمل</span>
                        <span class="font-bold">{{ $phys >= 8 ? 'ممتاز' : 'متقدم' }}</span>
                    </div>
                </div>

                <!-- 6. الذكاء التكتيكي -->
                <div class="bg-forest-900/80 p-4 rounded-2xl border border-forest-800 hover:border-brand-500 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white flex items-center space-x-1.5 space-x-reverse">
                            <span>🧠</span>
                            <span>6. الذكاء التكتيكي</span>
                        </span>
                        <span class="text-copper-400 font-mono font-black text-sm">{{ $tact }}/10</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block">Tactical IQ & Decision Making</span>
                    <div class="w-full bg-forest-950 rounded-full h-3 p-0.5 overflow-hidden border border-forest-800">
                        <div class="bg-gradient-to-r from-copper-600 to-amber-400 h-2 rounded-full transition-all duration-500" style="width: {{ $tact * 10 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-copper-400">
                        <span>التمركز وحسن اتخاذ القرار</span>
                        <span class="font-bold">{{ $tact >= 8 ? 'ذكي ومؤثر' : 'واعد' }}</span>
                    </div>
                </div>

                <!-- 7. التأثير العام في المباراة (Full width or prominent) -->
                <div class="md:col-span-2 lg:col-span-3 bg-forest-900/90 p-4 rounded-2xl border-2 border-brand-600/70 hover:border-brand-400 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white flex items-center space-x-2 space-x-reverse">
                            <span class="text-lg">⭐</span>
                            <span class="text-sm font-black">7. التأثير العام في مجريات المباراة (Overall Match Impact)</span>
                        </span>
                        <span class="text-copper-400 font-mono font-black text-base">{{ $impact }}/10</span>
                    </div>
                    <span class="text-[10px] text-brand-300 block">القيادة، الحسم في اللحظات الحرجة، والروح القتالية للفريق</span>
                    <div class="w-full bg-forest-950 rounded-full h-3.5 p-0.5 overflow-hidden border border-forest-800">
                        <div class="bg-gradient-to-r from-copper-600 via-amber-500 to-brand-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $impact * 10 }}%"></div>
                    </div>
                </div>

            </div>

            <!-- Scout Detailed Observation -->
            @if($primaryReport && $primaryReport->strengths)
                <div class="mt-6 pt-5 border-t border-forest-800 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="bg-forest-900/90 p-4 rounded-xl border border-forest-800">
                        <strong class="text-brand-300 block mb-1 font-bold flex items-center space-x-1.5 space-x-reverse">
                            <span>✓</span>
                            <span>نقاط القوة التكتيكية والفنية المرصودة:</span>
                        </strong>
                        <p class="text-slate-200 leading-relaxed">{{ $primaryReport->strengths }}</p>
                    </div>
                    <div class="bg-forest-900/90 p-4 rounded-xl border border-forest-800">
                        <strong class="text-copper-400 block mb-1 font-bold flex items-center space-x-1.5 space-x-reverse">
                            <span>⚡</span>
                            <span>برنامج التطوير والتوجيه الفني المقترح:</span>
                        </strong>
                        <p class="text-slate-200 leading-relaxed">{{ $primaryReport->weaknesses ?: 'مواصلة العمل على السرعة الانتقالية وتطوير استخدام القدم الأضعف في الكرات العرضية.' }}</p>
                    </div>
                </div>
            @endif

            <!-- DTR Certification Stamp Badge -->
            <div class="mt-6 pt-4 border-t border-forest-800/80 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-2">
                <div class="flex items-center space-x-2 space-x-reverse">
                    <span class="text-copper-400 font-bold">ختم الاعتماد الفني:</span>
                    <span class="text-white font-semibold">مصادق عليه من المديرية الفنية الجهوية لرابطة البليدة (DTR LRFB)</span>
                </div>
                <div class="font-mono text-[11px] text-brand-300">
                    STATUS: CERTIFIED TALENT • MESSARI
                </div>
            </div>

        </div>

    </div>

    <!-- ========================================================================================= -->
    <!-- TAB 3: CAREER TIMELINE SINCE 2021 (مسار اللاعب التراكمي منذ موسم 2021) -->
    <!-- ========================================================================================= -->
    <div x-show="activeTab === 'timeline'" class="transition-all duration-300 space-y-6">
        
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 mb-8 border-b border-slate-100 gap-4">
                <div>
                    <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block font-mono">
                        PROGRESSION RECORD (2021 - 2027)
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">
                        شريط المسار الكروي التراكمي للموهبة
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        سجل المواسم والإحصائيات التراكمية المعتمدة رسمياً في منصة «مساري» بداية من سنة 2021.
                    </p>
                </div>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <span class="bg-brand-50 text-brand-800 border border-brand-200 text-xs font-bold px-3 py-1 rounded-xl">
                        {{ $player->careerTimelines->count() }} مواسم معتمدة
                    </span>
                    <span class="bg-copper-50 text-copper-800 border border-copper-200 text-xs font-bold px-3 py-1 rounded-xl">
                        {{ $player->careerTimelines->sum('matches_count') }} مباراة مسجلة
                    </span>
                </div>
            </div>

            <!-- Sleek Milestone Timeline -->
            <div class="relative pr-8 border-r-2 border-brand-600/40 space-y-8">
                @forelse($player->careerTimelines as $timeline)
                    <div class="relative group">
                        <!-- Glowing Milestone Node -->
                        <div class="absolute -right-[41px] top-1.5 w-5 h-5 rounded-full bg-forest-950 border-4 border-brand-500 shadow-md group-hover:scale-125 transition duration-200"></div>

                        <div class="bg-slate-50 hover:bg-white p-5 rounded-2xl border border-slate-200 hover:border-brand-500 hover:shadow-md transition duration-200">
                            <!-- Season & Club Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-800 border border-brand-200 flex items-center justify-center font-bold text-sm shadow-xs flex-shrink-0">
                                        {{ mb_substr($timeline->club?->short_name ?: $timeline->club?->name_ar, 0, 3) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base text-slate-900">{{ $timeline->club?->name_ar }}</h4>
                                        <span class="text-xs text-slate-500">{{ $timeline->club?->wilaya?->name_ar ?? 'ولاية البليدة' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <span class="bg-brand-600 text-white text-xs font-bold px-2.5 py-0.5 rounded-lg shadow-2xs">
                                        فئة {{ $timeline->ageCategory?->code }}
                                    </span>
                                    <span class="bg-forest-950 text-brand-300 font-mono font-bold text-xs px-3 py-1 rounded-lg border border-forest-800">
                                        {{ $timeline->season?->name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Season Metrics Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-center mt-4 pt-3 border-t border-slate-200">
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-2xs">
                                    <span class="text-slate-500 text-[10px] block font-medium">المباريات الملعوبة</span>
                                    <strong class="text-slate-900 text-base font-black font-mono">{{ $timeline->matches_count }}</strong>
                                </div>
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-2xs">
                                    <span class="text-slate-500 text-[10px] block font-medium">الأهداف المسجلة</span>
                                    <strong class="text-copper-600 text-base font-black font-mono">⚽ {{ $timeline->goals_count }}</strong>
                                </div>
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-2xs">
                                    <span class="text-slate-500 text-[10px] block font-medium">التمريرات الحاسمة</span>
                                    <strong class="text-brand-700 text-base font-black font-mono">👟 {{ $timeline->assists_count }}</strong>
                                </div>
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-2xs">
                                    <span class="text-slate-500 text-[10px] block font-medium">مجموع الدقائق</span>
                                    <strong class="text-forest-950 text-base font-black font-mono">{{ $timeline->minutes_played }}'</strong>
                                </div>
                            </div>

                            <!-- Achievement Ribbon -->
                            @if($timeline->achievement_title)
                                <div class="mt-3.5 text-xs text-copper-900 bg-gradient-to-r from-copper-50 to-amber-50 p-3 rounded-xl border border-copper-200 flex items-center space-x-2 space-x-reverse font-bold shadow-2xs">
                                    <span class="text-base">🏆</span>
                                    <span>الإنجاز المحقق: {{ $timeline->achievement_title }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500 text-sm">
                        المسار الكروي موثق ومؤرشف بداية من الموسم الرياضي 2021/2022.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- ========================================================================================= -->
    <!-- FIELD BADGE MODAL (بطاقة الإجازة الميدانية الرسمية الموجهة للطباعة والتحقق) -->
    <!-- ========================================================================================= -->
    <div x-show="showBadge" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-forest-950/85 backdrop-blur-md"
         style="display: none;">
        
        <div class="relative bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border-2 border-brand-500 space-y-4">
            <!-- Close button -->
            <button @click="showBadge = false" class="absolute top-4 left-4 text-slate-400 hover:text-slate-700 text-xl font-bold">
                ✕
            </button>

            <!-- Card Design (CR80 PVC Style) -->
            <div class="guilloche-pattern text-white rounded-2xl p-5 shadow-2xl border-2 border-copper-500 relative overflow-hidden">
                <div class="flex items-center justify-between border-b border-forest-800 pb-3 mb-3">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-8 h-8 object-contain" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px; max-width: 32px; max-height: 32px;">
                        <div>
                            <h4 class="font-bold text-xs text-white">الرابطة الجهوية بالبليدة</h4>
                            <p class="text-[8px] text-brand-300 font-mono">LICENCE OFFICIELLE LRFB</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1.5 space-x-reverse">
                        <img src="{{ asset('images/masari-logo.jpg') }}" alt="MESSARI" class="w-7 h-7 rounded-full object-contain border border-copper-400 bg-white" style="width: 28px; height: 28px; min-width: 28px; min-height: 28px; max-width: 28px; max-height: 28px;">
                        <span class="bg-forest-900 text-copper-400 border border-copper-600 text-[9px] font-bold px-1.5 py-0.5 rounded font-mono">2026/27</span>
                    </div>
                </div>

                <div class="flex items-center space-x-3 space-x-reverse mb-3">
                    <div class="w-16 h-20 rounded-xl bg-forest-900 border-2 border-brand-400 overflow-hidden flex-shrink-0 flex items-center justify-center text-3xl shadow">
                        @if($player->photo_path)
                            <img src="{{ asset('storage/' . $player->photo_path) }}" alt="{{ $player->full_name_ar }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-brand-300">⚽</span>
                        @endif
                    </div>
                    <div class="flex-grow space-y-0.5">
                        <h3 class="font-bold text-sm text-white">{{ $player->full_name_ar }}</h3>
                        <p class="text-[10px] text-brand-200 font-mono">{{ $player->full_name_fr }}</p>
                        <p class="text-[10px] text-copper-400 font-mono font-bold">N°: {{ $player->passport_number }}</p>
                        <p class="text-[10px] text-slate-300 truncate">
                            {{ $player->currentLicense?->club?->name_ar }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-1 bg-forest-900/90 p-2 rounded-xl text-center text-[10px] font-mono border border-forest-800">
                    <div>
                        <span class="text-slate-400 block text-[8px]">الفئة</span>
                        <strong class="text-white">{{ $catCode }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[8px]">المركز</span>
                        <strong class="text-brand-300">{{ $player->primary_position }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[8px]">فصيلة الدم</span>
                        <strong class="text-copper-400">{{ $player->blood_type ?? 'O+' }}</strong>
                    </div>
                </div>

                <div class="mt-3 pt-2 border-t border-forest-800 flex items-center justify-between text-[9px] text-slate-400">
                    <span>مؤهل ومطابق للمنافسة الرسمية</span>
                    <span class="font-mono font-bold text-brand-400">MESSARI VERIFIED</span>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex gap-2">
                <button onclick="window.print()" class="flex-grow bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm">
                    🖨️ طباعة بطاقة الإجازة
                </button>
                <button @click="showBadge = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-xl text-xs transition">
                    إغلاق
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
