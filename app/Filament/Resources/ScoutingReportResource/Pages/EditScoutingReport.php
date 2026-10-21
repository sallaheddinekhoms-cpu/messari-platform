<?php

namespace App\Filament\Resources\ScoutingReportResource\Pages;

use App\Filament\Resources\ScoutingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScoutingReport extends EditRecord
{
    protected static string $resource = ScoutingReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
