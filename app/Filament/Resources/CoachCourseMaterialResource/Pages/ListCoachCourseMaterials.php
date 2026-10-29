<?php

namespace App\Filament\Resources\CoachCourseMaterialResource\Pages;

use App\Filament\Resources\CoachCourseMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoachCourseMaterials extends ListRecords
{
    protected static string $resource = CoachCourseMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
