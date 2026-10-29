@extends('masari.layout')

@section('title', 'تسجيل الدخول | فضاء تكوين المدربين - المديرية التقنية الجهوية')

@section('content')
<div class="py-16 max-w-md mx-auto px-4 sm:px-6">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Top Header Ribbon -->
        <div class="bg-gradient-to-r from-forest-950 via-forest-900 to-forest-950 text-white p-6 text-center border-b border-brand-800">
            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-brand-500/20 border border-brand-500 flex items-center justify-center text-2xl text-brand-300">
                🎓
            </div>
            <h1 class="text-xl font-black">فضاء المترشح والمدرب</h1>
            <p class="text-xs text-brand-300 mt-1">المديرية واللجنة التقنية الجهوية • رابطة البليدة</p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-3 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-3 text-xs">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('dtr.login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                        البريد الإلكتروني المسجل به:
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="coach@example.com" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600 font-mono">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                        كلمة المرور:
                    </label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-brand-600 focus:border-brand-600">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-1.5 space-x-reverse text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500">
                        <span>تذكرني على هذا الجهاز</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 rounded-xl text-xs sm:text-sm transition shadow-md">
                    دخول فضاء متابعة الملف والدروس ←
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center space-y-2">
                <p class="text-xs text-slate-500">ليس لديك ملف ترشح مسجل بعد؟</p>
                <a href="{{ route('dtr.apply') }}" class="inline-block bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-4 py-2 rounded-xl text-xs transition">
                    + إيداع ملف ترشح جديد في الدورات المفتوحة
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
