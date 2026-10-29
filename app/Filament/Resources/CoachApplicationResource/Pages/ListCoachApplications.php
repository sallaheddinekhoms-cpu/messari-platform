<?php

namespace App\Filament\Resources\CoachApplicationResource\Pages;

use App\Filament\Resources\CoachApplicationResource;
use App\Models\CoachApplication;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCoachApplications extends ListRecords
{
    protected static string $resource = CoachApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إيداع ملف ترشح جديد'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('جميع الملفات')
                ->badge(CoachApplication::count()),

            'pending' => Tab::make('بانتظار التدقيق')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(CoachApplication::where('status', 'pending')->count())
                ->badgeColor('warning'),

            'approved' => Tab::make('المقبولون نهائياً')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
                ->badge(CoachApplication::where('status', 'approved')->count())
                ->badgeColor('success'),

            'reserved' => Tab::make('تحفظات وتصحيحات')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'reserved'))
                ->badge(CoachApplication::where('status', 'reserved')->count())
                ->badgeColor('danger'),

            'rejected' => Tab::make('الملفات المرفوضة')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(CoachApplication::where('status', 'rejected')->count())
                ->badgeColor('gray'),
        ];
    }
}
