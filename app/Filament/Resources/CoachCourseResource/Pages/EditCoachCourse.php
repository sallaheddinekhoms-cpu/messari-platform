<?php

namespace App\Filament\Resources\CoachCourseResource\Pages;

use App\Filament\Resources\CoachCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoachCourse extends EditRecord
{
    protected static string $resource = CoachCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
