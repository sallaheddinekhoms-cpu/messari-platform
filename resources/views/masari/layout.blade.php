<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'منصة مساري | الرابطة الجهوية لكرة القدم بالبليدة')</title>
    <meta name="description" content="منصة مساري - المنظومة الرقمية الرسمية للفئات الشبانية والملف الرياضي للمواهب الكروية التابعة بالرابطة الجهوية لكرة القدم بالبليدة (LRFB).">
    
    <!-- Official MESSARI Favicon & Browser Tab Icons -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/masari-logo.jpg') }}?v=3">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=3">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // الأخضر الكروي الرسمي المستوحى بدقة من الشعار (MESSARI Green)
                        brand: {
                            50: '#f3f8ec',
                            100: '#e4f1d4',
                            200: '#cbe3ae',
                            300: '#add483',
                            400: '#8ec056',
                            500: '#6ea532',
                            600: '#548622', // اللون المطابق لقميص وشعار مساري
                            700: '#426a1b', // الدرجة الداكنة للأزرار والتفاعل
                            800: '#325215',
                            900: '#1e330d', // الدرجة الليلية للميدان
                            950: '#111f07',
                        },
                        // النحاسي الذهبي للتتويج والاعتماد
                        copper: {
                            50: '#fdf8f0',
                            100: '#faefdc',
                            500: '#d58f38',
                            600: '#C17D2D',
                            700: '#a36421',
                        },
                        forest: {
                            800: '#1b300f',
                            900: '#13230a',
                            950: '#0c1706',
                        }
                    },
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        /* بطاقة الملف الرياضي المتناسقة مع الشعار الدائري */
        .messari-passport-card {
            background: linear-gradient(145deg, #13230a 0%, #1e330d 50%, #0c1706 100%);
            border: 1.5px solid #548622;
            box-shadow: 0 20px 25px -5px rgba(84, 134, 34, 0.2), 0 8px 10px -6px rgba(17, 31, 7, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Ribbon (Official Institutional Bar) -->
    <div class="bg-forest-900 text-slate-300 text-xs py-2 px-4 border-b border-forest-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3 space-x-reverse font-medium">
                <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-5 h-5 object-contain inline-block">
                <span class="font-bold text-slate-200">الرابطة الجهوية لكرة القدم بالبليدة (LRF Blida)</span>
                <span class="text-forest-800 hidden md:inline">|</span>
                <span class="hidden md:inline text-brand-300">منظومة «مساري» للفئات الشبانية (U13 إلى U20)</span>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <span class="hidden md:inline-flex items-center space-x-1.5 space-x-reverse text-[11px] text-brand-300 font-bold bg-forest-950 px-2.5 py-0.5 rounded border border-brand-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span>
                    <span>النظام معتمد رسمياً</span>
                </span>
                <a href="/admin" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-3.5 py-1 rounded-lg text-xs transition shadow-sm">
                    فضاء الإدارة والأندية
                </a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / League Logo (Top Right in RTL) -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3.5 space-x-reverse group">
                    <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition duration-200" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                        <img src="{{ asset('images/league-logo.png') }}" alt="شعار الرابطة الجهوية لكرة القدم بالبليدة" class="w-12 h-12 object-contain" style="max-width: 48px; max-height: 48px;">
                    </div>
                    <div class="border-r border-slate-200 pr-3.5">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <span class="text-2xl font-black tracking-tight text-slate-900">
                                مساري
                            </span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">
                            الملف الرياضي للمواهب الشبانية • رابطة البليدة
                        </p>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-1 space-x-reverse">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-800 border border-brand-200' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50' }}">
                        الرئيسية
                    </a>
                    <a href="{{ route('competitions.index') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('competitions.*') ? 'bg-brand-50 text-brand-800 border border-brand-200' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50' }}">
                        مركز البطولات والرزنامة
                    </a>
                    <a href="{{ route('media.index') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition {{ request()->routeIs('media.*') ? 'bg-brand-50 text-brand-800 border border-brand-200' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50' }} flex items-center space-x-1 space-x-reverse">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>معرض الصور والفيديوهات</span>
                    </a>
                    <a href="{{ route('home') }}#featured-talents" class="px-3 py-2 rounded-lg text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition flex items-center space-x-1.5 space-x-reverse">
                        <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                        <span>بنك المواهب الشبانية</span>
                    </a>
                    <a href="{{ route('home') }}#about-passport" class="px-3 py-2 rounded-lg text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition">
                        الملف الرياضي
                    </a>
                </nav>

                <!-- Left Section: Search Input + Official MESSARI Circular Emblem (الجهة اليسرى) -->
                <div class="flex items-center space-x-3.5 space-x-reverse">
                    <!-- Search Input Desktop -->
                    <div class="hidden lg:block w-52 xl:w-60">
                        <form action="{{ route('players.search') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="q" placeholder="ابحث برقم الملف أو اللاعب..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pr-8 pl-3 text-xs font-medium focus:ring-1 focus:ring-brand-600 focus:border-brand-600 text-slate-800 transition">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-brand-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Platform Circular Emblem on the Left Side -->
                    <div class="flex items-center pr-2 sm:pr-3 border-r border-slate-200">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 space-x-reverse group" title="شعار منصة مساري - MESSARI">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full p-0.5 border-2 border-brand-500 shadow-sm group-hover:scale-105 group-hover:border-brand-700 transition duration-200 flex items-center justify-center overflow-hidden bg-white" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px; max-width: 52px; max-height: 52px;">
                                <img src="{{ asset('images/masari-logo.jpg') }}" alt="شعار منصة مساري" class="w-full h-full object-contain" style="max-width: 100%; max-height: 100%;">
                            </div>
                            <div class="hidden xl:block text-right">
                                <span class="text-xs font-black text-slate-900 block leading-none">مساري</span>
                                <span class="text-[9px] font-mono text-brand-700 font-bold block uppercase tracking-wider mt-0.5">MESSARI</span>
                            </div>
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex md:hidden">
                        <button @click="mobileMenu = !mobileMenu" class="text-slate-700 p-2 rounded-lg hover:bg-slate-100 focus:outline-none">
                            <span class="text-xl" x-text="mobileMenu ? '✕' : '☰'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenu" class="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-3">
            <form action="{{ route('players.search') }}" method="GET" class="mb-4">
                <input type="text" name="q" placeholder="ابحث برقم الملف أو اسم اللاعب..." class="w-full bg-slate-100 border border-slate-200 rounded-lg py-2.5 px-3 text-xs focus:ring-1 focus:ring-brand-600">
            </form>
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg font-bold text-slate-800 hover:bg-slate-50">الرئيسية</a>
            <a href="{{ route('competitions.index') }}" class="block px-3 py-2 rounded-lg font-bold text-slate-800 hover:bg-slate-50">مركز البطولات والرزنامة</a>
            <a href="{{ route('media.index') }}" class="block px-3 py-2 rounded-lg font-bold text-brand-700 bg-brand-50 hover:bg-brand-100">معرض الصور والفيديوهات</a>
            <a href="{{ route('home') }}#featured-talents" class="block px-3 py-2 rounded-lg font-bold text-slate-800 hover:bg-slate-50">بنك المواهب الشبانية</a>
            <a href="/admin" class="block px-3 py-2.5 rounded-lg bg-brand-600 text-white font-bold text-center">دخول لوحة الإدارة والأندية</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Professional Institutional Footer with Matching Brand Green -->
    <footer class="bg-forest-950 text-slate-400 text-sm mt-16 border-t-2 border-brand-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1 -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3.5 space-x-reverse text-white font-bold text-lg">
                        <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-11 h-11 object-contain" style="width: 44px; height: 44px; min-width: 44px; min-height: 44px; max-width: 44px; max-height: 44px;">
                        <img src="{{ asset('images/masari-logo.jpg') }}" alt="MESSARI" class="w-11 h-11 rounded-full object-contain border-2 border-brand-500 bg-white shadow-xs" style="width: 44px; height: 44px; min-width: 44px; min-height: 44px; max-width: 44px; max-height: 44px;">
                        <div>
                            <span class="text-white font-black text-xl">مساري (MESSARI)</span>
                            <span class="text-xs text-brand-300 block font-normal">الرابطة الجهوية لكرة القدم بالبليدة • المديرية الفنية الجهوية</span>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed max-w-md text-slate-300">
                        المنصة الرسمية المعتمدة لتوثيق وتتبع المسار الرياضي التراكمي للاعبي الفئات الصغرى (U13, U15, U17, U19, U20)، وإصدار الملف الرياضي الرقمي برمز QR للتحقق الميداني الفوري.
                    </p>
                    <div class="flex flex-wrap gap-1.5 text-[11px] font-semibold text-slate-300">
                        <span class="bg-forest-900 px-2.5 py-0.5 rounded border border-forest-800">البليدة (09)</span>
                        <span class="bg-forest-900 px-2.5 py-0.5 rounded border border-forest-800">تيبازة (42)</span>
                        <span class="bg-forest-900 px-2.5 py-0.5 rounded border border-forest-800">المدية (26)</span>
                        <span class="bg-forest-900 px-2.5 py-0.5 rounded border border-forest-800">الجلفة (17)</span>
                        <span class="bg-forest-900 px-2.5 py-0.5 rounded border border-forest-800">عين الدفلى (44)</span>
                        <span class="bg-forest-900 px-2.5 py-0.5 rounded border border-forest-800">الشلف (02)</span>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h4 class="text-white font-bold mb-3 text-sm flex items-center space-x-1.5 space-x-reverse">
                        <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                        <span>البطولات والمحتوى</span>
                    </h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('competitions.index', ['division' => 'elite', 'category' => 'U20']) }}" class="hover:text-brand-300 transition">بطولة النخبة (U20, U17, U15)</a></li>
                        <li><a href="{{ route('competitions.index', ['division' => 'regional_a']) }}" class="hover:text-brand-300 transition">المستوى الجهوي • فوج (أ)</a></li>
                        <li><a href="{{ route('competitions.index', ['division' => 'regional_b']) }}" class="hover:text-brand-300 transition">المستوى الجهوي • فوج (ب)</a></li>
                        <li><a href="{{ route('competitions.index', ['division' => 'regional_c']) }}" class="hover:text-brand-300 transition">المستوى الجهوي • فوج (ج / الجلفة)</a></li>
                        <li><a href="{{ route('media.index') }}" class="hover:text-brand-300 text-amber-300 font-semibold transition flex items-center space-x-1 space-x-reverse"><span>📸</span><span>معرض الصور والفيديوهات</span></a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h4 class="text-white font-bold mb-3 text-sm flex items-center space-x-1.5 space-x-reverse">
                        <span class="w-2 h-2 rounded-full bg-copper-600"></span>
                        <span>بوابة الأندية والمسؤولين</span>
                    </h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="/admin" class="hover:text-brand-300 transition">لوحة تحكم مسؤولي الأندية (Filament)</a></li>
                        <li><a href="/admin" class="hover:text-brand-300 transition">بوابة المديرية الفنية الجهوية</a></li>
                        <li><a href="{{ route('players.search') }}" class="hover:text-brand-300 transition">التحقق الميداني من الملف الرياضي</a></li>
                        <li><a href="{{ route('competitions.index') }}" class="hover:text-brand-300 transition">جداول الترتيب ورزنامة الجولات</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-forest-800 text-xs text-center flex flex-col sm:flex-row items-center justify-between text-slate-500">
                <p>جميع الحقوق محفوظة © {{ date('Y') }} - الرابطة الجهوية لكرة القدم بالبليدة (LRF Blida) - منصة «مساري».</p>
                <div class="flex items-center space-x-2 space-x-reverse mt-2 sm:mt-0 font-mono text-[11px]">
                    <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                    <span class="w-2 h-2 rounded-full bg-copper-600"></span>
                    <span class="text-slate-400">Système Officiel du Dossier Sportif • MESSARI</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
