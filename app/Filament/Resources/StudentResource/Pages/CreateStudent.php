<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['user']['name'],
                'last_name' => $data['user']['last_name'],
                'email' => $data['user']['email'],
                'password' => $data['user']['password'],
            ]);

            // Asigna automáticamente el rol 'student'
            $user->assignRole('student');

            return Student::create([
                'user_id' => $user->id,
                'carrier' => $data['carrier'],
            ]);
        });
    }
}
