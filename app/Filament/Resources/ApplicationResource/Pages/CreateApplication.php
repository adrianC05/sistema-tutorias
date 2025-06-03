<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user->hasRole('student')) {
            $studentId = Student::where('user_id', $user->id)->value('id');
            $data['student_id'] = $studentId;
        }

        if (!$user->hasRole('coordinator')) {
            $data['status'] = 'Pendiente';
        }

        return $data;
    }
}
