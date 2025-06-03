<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Tutor;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user->hasRole('tutor')) {
            $tutorId = Tutor::where('user_id', $user->id)->value('id');
            $data['tutor_id'] = $tutorId;
        }

        return $data;
    }
}
