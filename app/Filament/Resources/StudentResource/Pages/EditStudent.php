<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Carga los datos del usuario relacionado
        $data['user'] = $this->record->user->only(['name', 'last_name', 'email']);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Actualiza los datos del usuario relacionado
        $this->record->user->update([
            'name' => $data['user']['name'],
            'last_name' => $data['user']['last_name'],
            'email' => $data['user']['email'],
        ]);
        // Elimina los datos de usuario para que no intente guardarlos en Students
        unset($data['user']);
        return $data;
    }
}
