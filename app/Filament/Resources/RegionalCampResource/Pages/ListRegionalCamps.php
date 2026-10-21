<?php

namespace App\Filament\Resources\RegionalCampResource\Pages;

use App\Filament\Resources\RegionalCampResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegionalCamps extends ListRecords
{
    protected static string $resource = RegionalCampResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
