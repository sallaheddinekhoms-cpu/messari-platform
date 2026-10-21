<?php

namespace App\Filament\Resources\ScoutingReportResource\Pages;

use App\Filament\Resources\ScoutingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScoutingReports extends ListRecords
{
    protected static string $resource = ScoutingReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
