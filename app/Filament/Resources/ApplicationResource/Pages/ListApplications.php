<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();
        $user = auth()->user();

        if ($user->hasRole('student')) {
            $studentId = \App\Models\Student::where('user_id', $user->id)->value('id');

            if ($studentId) {
                return $query->where('student_id', $studentId);
            } else {
                return $query->whereRaw('1=0'); // no mostrar nada si no tiene estudiante
            }
        }

        // Usuarios con otros roles ven todo
        return $query;
    }

}
