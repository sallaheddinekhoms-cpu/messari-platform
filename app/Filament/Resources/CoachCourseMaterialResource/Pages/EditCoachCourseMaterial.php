<?php

namespace App\Filament\Resources\CoachCourseMaterialResource\Pages;

use App\Filament\Resources\CoachCourseMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoachCourseMaterial extends EditRecord
{
    protected static string $resource = CoachCourseMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
