<?php

namespace App\Filament\Resources\TutorResource\Pages;

use App\Filament\Resources\TutorResource;
use App\Models\User;
use App\Models\Tutor;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateTutor extends CreateRecord
{
    protected static string $resource = TutorResource::class;

    protected function handleRecordCreation(array $data): Tutor
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['user']['name'],
                'last_name' => $data['user']['last_name'],
                'email' => $data['user']['email'],
                'password' => $data['user']['password'],
            ]);

            // Asigna automáticamente el rol 'tutor'
            $user->assignRole('tutor');

            return Tutor::create([
                'user_id' => $user->id,
                'specialty' => $data['specialty'],
            ]);
        });
    }
}
