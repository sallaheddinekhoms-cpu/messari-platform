<?php

namespace App\Filament\Resources\CoachApplicationResource\Pages;

use App\Filament\Resources\CoachApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoachApplication extends EditRecord
{
    protected static string $resource = CoachApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
