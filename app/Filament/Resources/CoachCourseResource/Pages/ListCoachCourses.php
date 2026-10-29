<?php

namespace App\Filament\Resources\CoachCourseResource\Pages;

use App\Filament\Resources\CoachCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoachCourses extends ListRecords
{
    protected static string $resource = CoachCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة دورة تكوينية جديدة'),
        ];
    }
}
