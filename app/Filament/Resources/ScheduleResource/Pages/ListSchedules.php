<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Tutor;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Solo muestra los horarios del tutor autenticado
    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        if ($user->hasRole('tutor')) {
            $tutorId = Tutor::where('user_id', $user->id)->value('id');
            return ScheduleResource::getEloquentQuery()->where('tutor_id', $tutorId);
        }

        return ScheduleResource::getEloquentQuery();
    }
}
