@extends('masari.layout')

@section('title', 'المركز الإعلامي • معرض الصور والفيديوهات | منصة مساري - LRF Blida')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeModal: false, modalContent: null, modalType: 'photo', modalTitle: '' }">

    <!-- Header Section -->
    <div class="mb-8 pb-4 border-b border-slate-200">
        <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block mb-1">
            المركز الإعلامي الرقمي (Media & Scouting Hub)
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
            معرض الصور وملخصات الفيديو للفئات الشبانية
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl font-medium">
            تغطيات مصورة حصرية لمباريات بطولات النخبة والمستويات الجهوية، أهداف الأسبوع، كؤوس الرابطة، وتربصات المديرية الفنية الجهوية بالبليدة.
        </p>
    </div>

    <!-- Filter Bar (Type and Category) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <!-- Type Filter -->
        <div class="flex items-center space-x-2 space-x-reverse overflow-x-auto pb-1">
            <a href="{{ route('media.index', ['type' => 'all', 'category' => $category]) }}" 
               class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition border {{ $type === 'all' ? 'bg-forest-950 text-brand-300 border-brand-600 shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-200' }}">
                <span>الكل (الصور والفيديوهات)</span>
            </a>
            <a href="{{ route('media.index', ['type' => 'video', 'category' => $category]) }}" 
               class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition border {{ $type === 'video' ? 'bg-forest-950 text-brand-300 border-brand-600 shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-200' }}">
                <span>🎥 ملخصات الفيديو والأهداف</span>
            </a>
            <a href="{{ route('media.index', ['type' => 'photo', 'category' => $category]) }}" 
               class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition border {{ $type === 'photo' ? 'bg-forest-950 text-brand-300 border-brand-600 shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-200' }}">
                <span>📸 ألبومات الصور والتكريمات</span>
            </a>
        </div>

        <!-- Category Dropdown / Tags -->
        <div class="flex items-center space-x-1.5 space-x-reverse overflow-x-auto text-xs">
            <span class="text-slate-400 font-bold pl-1">التصنيف:</span>
            <a href="{{ route('media.index', ['type' => $type, 'category' => 'all']) }}" class="px-2.5 py-1 rounded-lg {{ $category === 'all' ? 'bg-brand-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">الكل</a>
            <a href="{{ route('media.index', ['type' => $type, 'category' => 'elite']) }}" class="px-2.5 py-1 rounded-lg {{ $category === 'elite' ? 'bg-brand-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">النخبة</a>
            <a href="{{ route('media.index', ['type' => $type, 'category' => 'camps']) }}" class="px-2.5 py-1 rounded-lg {{ $category === 'camps' ? 'bg-brand-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">تربصات DTR</a>
            <a href="{{ route('media.index', ['type' => $type, 'category' => 'goals']) }}" class="px-2.5 py-1 rounded-lg {{ $category === 'goals' ? 'bg-brand-600 text-white font-bold' : 'bg-slate-100 text-slate-600' }}">أهداف الأسبوع</a>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @forelse($items as $item)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:border-brand-500 hover:shadow-md transition duration-200 flex flex-col justify-between group">
                <div>
                    <!-- Thumbnail with Type Badge -->
                    <div class="relative h-48 bg-slate-900 flex items-center justify-center overflow-hidden cursor-pointer"
                         @click="activeModal = true; modalContent = '{{ $item->media_url }}'; modalType = '{{ $item->type }}'; modalTitle = '{{ $item->title }}'">
                        
                        <img src="{{ $item->thumbnail_url ?: asset('images/masari-logo.jpg') }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 opacity-90">

                        <!-- Overlay Play or Zoom icon -->
                        <div class="absolute inset-0 bg-slate-950/30 flex items-center justify-center">
                            @if($item->type === 'video')
                                <div class="w-12 h-12 rounded-full bg-brand-600/90 text-white flex items-center justify-center text-xl shadow-lg group-hover:scale-110 transition">
                                    ▶
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-900/80 text-white flex items-center justify-center text-lg shadow-lg group-hover:scale-110 transition">
                                    🔍
                                </div>
                            @endif
                        </div>

                        <!-- Type Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-slate-900/80 text-white text-[10px] font-bold px-2 py-0.5 rounded backdrop-blur-xs">
                                {{ $item->type === 'video' ? 'فيديو' : 'ألبوم صور' }}
                            </span>
                        </div>

                        <!-- Date Badge -->
                        @if($item->event_date)
                            <div class="absolute bottom-3 right-3">
                                <span class="bg-slate-900/70 text-slate-300 text-[10px] font-mono px-2 py-0.5 rounded">
                                    {{ $item->event_date->format('Y-m-d') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Meta content -->
                    <div class="p-4 space-y-2">
                        <h3 class="font-bold text-sm text-slate-900 group-hover:text-brand-700 transition leading-snug">
                            {{ $item->title }}
                        </h3>
                        @if($item->description)
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $item->description }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="px-4 pb-4 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span class="font-semibold text-brand-700">الرابطة الجهوية بالبليدة</span>
                    <span class="font-mono">👁️ {{ number_format($item->views_count) }} مشاهدة</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl p-12 text-center border border-slate-200">
                <span class="text-3xl block mb-2 text-slate-400">📸</span>
                <h3 class="text-base font-bold text-slate-800">لا توجد وسائط مضافة في هذا التصنيف حالياً</h3>
                <p class="text-xs text-slate-500 mt-1">تتم إضافة الصور والفيديوهات بانتظام من قِبل خلية الإعلام والاتصال بالرابطة.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $items->links() }}
    </div>

    <!-- Media Lightbox Modal -->
    <div x-show="activeModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-sm"
         style="display: none;">
        
        <div class="relative bg-slate-900 rounded-2xl p-4 max-w-3xl w-full border border-forest-800 shadow-2xl space-y-3" @click.outside="activeModal = false">
            <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                <h4 class="text-sm font-bold text-white truncate" x-text="modalTitle"></h4>
                <button @click="activeModal = false" class="text-slate-400 hover:text-white text-xl font-bold px-2">
                    ✕
                </button>
            </div>

            <!-- Modal Content (Video iframe or Photo) -->
            <div class="flex items-center justify-center bg-black rounded-xl overflow-hidden min-h-[300px]">
                <template x-if="modalType === 'video'">
                    <iframe :src="modalContent" class="w-full h-80 sm:h-96" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </template>
                <template x-if="modalType === 'photo'">
                    <img :src="modalContent" :alt="modalTitle" class="max-h-[80vh] w-auto object-contain mx-auto">
                </template>
            </div>
        </div>
    </div>

</div>
@endsection
