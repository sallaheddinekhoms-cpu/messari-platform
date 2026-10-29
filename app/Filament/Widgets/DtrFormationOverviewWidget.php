<?php

namespace App\Filament\Widgets;

use App\Models\CoachApplication;
use App\Models\CoachCourse;
use App\Models\CoachCourseMaterial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DtrFormationOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $pendingCount = CoachApplication::where('status', 'pending')->count();
        $approvedCount = CoachApplication::where('status', 'approved')->count();
        $reservedCount = CoachApplication::where('status', 'reserved')->count();
        $openCoursesCount = CoachCourse::where('is_registration_open', true)->count();
        $activeMaterialsCount = CoachCourseMaterial::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->count();

        return [
            Stat::make('ملفات ترشح بانتظار تدقيق DTR', $pendingCount)
                ->description($pendingCount > 0 ? 'يتطلب فحص الوثائق والمصادقة' : 'لا توجد طلبات معلقة حالياً')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray')
                ->chart([$pendingCount + 5, $pendingCount + 3, $pendingCount + 8, $pendingCount]),

            Stat::make('المترشحون المقبولون نهائياً', $approvedCount)
                ->description('مستوفون للشروط ومؤهلون للتربص')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([2, 5, 10, 18, $approvedCount]),

            Stat::make('دورات التكوين المفتوحة', $openCoursesCount)
                ->description('استقبال الملفات عبر المنونة الرقمية')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('المحاضرات البيداغوجية النشطة', $activeMaterialsCount)
                ->description('تحت ضابط الإتاحة (15 يوماً)')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),
        ];
    }
}
