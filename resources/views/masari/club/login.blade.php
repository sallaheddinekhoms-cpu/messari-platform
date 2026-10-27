@extends('masari.layout')

@section('title', 'تسجيل الدخول - فضاء الأندية الرسمية | منصة مساري')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8">
        
        <!-- Header & Logo Badge -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white shadow-md border border-slate-200 mb-4 p-2 relative">
                <img src="{{ asset('images/league-logo.png') }}" alt="LRFB" class="w-16 h-16 object-contain">
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-brand-600 rounded-full flex items-center justify-center text-white text-[11px] font-bold shadow-xs">
                    🛡️
                </div>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                فضاء الأندية الرياضية المعتمدة
            </h2>
            <p class="mt-2 text-xs font-semibold text-slate-600">
                منصة «مساري» • الرابطة الجهوية لكرة القدم بالبليدة (LRFB)
            </p>
            <div class="mt-3 inline-flex items-center space-x-2 space-x-reverse text-xs bg-brand-50 text-brand-800 px-3 py-1 rounded-full border border-brand-200 font-bold">
                <span>⚡ إدارة الفئات الثلاثة: U20 • U17 • U15</span>
            </div>
        </div>

        <!-- Login Card -->
        <div class="bg-white py-8 px-6 sm:px-8 rounded-2xl shadow-sm border border-slate-200">
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold flex items-start space-x-2 space-x-reverse">
                    <span class="text-red-500 text-base">⚠️</span>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form class="space-y-5" action="{{ route('club.login.post') }}" method="POST">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                        البريد الإلكتروني المعتمد للنادي
                    </label>
                    <div class="relative">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               placeholder="club.code@lrf-blida.dz"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition dir-ltr text-right">
                        <span class="absolute left-3 top-2.5 text-slate-400 text-sm">📧</span>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-500">
                        البريد الرسمي المسجل باسم النادي في الرابطة
                    </p>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                        كلمة المرور
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition dir-ltr text-right">
                        <span class="absolute left-3 top-2.5 text-slate-400 text-sm">🔒</span>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 space-x-reverse cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                        <span class="font-medium text-slate-600">تذكر تسجيل الدخول</span>
                    </label>
                    <a href="mailto:contact@lrf-blida.dz?subject=طلب مساعدة حساب نادي - مساري" class="font-bold text-brand-700 hover:text-brand-800 transition">
                        فقدت كلمة المرور؟
                    </a>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-sm font-black text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 shadow-sm transition flex items-center justify-center space-x-2 space-x-reverse">
                    <span>تسجيل الدخول إلى فضاء النادي</span>
                    <span>←</span>
                </button>
            </form>

            <!-- Quick Demo Credentials helper box -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-xs text-slate-500">
                <p class="font-bold text-slate-700 mb-2 flex items-center space-x-1.5 space-x-reverse">
                    <span>💡</span>
                    <span>بيانات تجريبية سريعة للأندية:</span>
                </p>
                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200 font-mono text-[11px] space-y-1">
                    <p class="text-slate-700"><span class="font-bold">أولمبي المدية (ACM):</span> <code>club.acm@lrf-blida.dz</code></p>
                    <p class="text-slate-700"><span class="font-bold">أمل الأربعاء (RCA):</span> <code>club.rca@lrf-blida.dz</code></p>
                    <p class="text-slate-700"><span class="font-bold">شباب سغوان (CBS):</span> <code>club.cbs@lrf-blida.dz</code></p>
                    <p class="text-slate-600 pt-1 border-t border-slate-200"><span class="font-bold">كلمة المرور الافتراضية:</span> <code class="text-brand-700 font-bold">Club@2026!</code></p>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition flex items-center justify-center space-x-1 space-x-reverse">
                <span>→</span>
                <span>العودة إلى الصفحة الرئيسية لمنصة مساري</span>
            </a>
        </div>
    </div>
</div>
@endsection
