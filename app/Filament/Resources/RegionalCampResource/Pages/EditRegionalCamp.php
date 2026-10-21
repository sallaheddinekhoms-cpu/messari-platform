<?php

namespace App\Filament\Resources\RegionalCampResource\Pages;

use App\Filament\Resources\RegionalCampResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegionalCamp extends EditRecord
{
    protected static string $resource = RegionalCampResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
