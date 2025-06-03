<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListFeedback extends ListRecords
{
    protected static string $resource = FeedbackResource::class;

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
                return $query->whereHas('application', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                });
            } else {
                // Si no tiene estudiante asociado, no mostrar nada
                return $query->whereRaw('1=0');
            }
        }

        // Usuarios con otros roles ven todo
        return $query;
    }

}
