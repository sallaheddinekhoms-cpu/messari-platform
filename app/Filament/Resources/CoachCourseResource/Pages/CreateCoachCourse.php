<?php

namespace App\Filament\Resources\CoachCourseResource\Pages;

use App\Filament\Resources\CoachCourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoachCourse extends CreateRecord
{
    protected static string $resource = CoachCourseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
