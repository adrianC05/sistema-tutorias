<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Application;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Tutorías';
    protected static ?string $navigationLabel = 'Solicitudes';
    protected static ?string $pluralModelLabel = 'Solicitudes de Clase';
    protected static ?string $modelLabel = 'Solicitud de Clase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Estudiante')
                    ->hidden(fn () => auth()->user()->hasRole('student'))
                    ->options(function () {
                        return Student::all()->pluck('full_name', 'id')->toArray();
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('schedule_id')
                    ->label('Horario')
                    ->options(function () {
                        return \App\Models\Schedule::all()
                            ->mapWithKeys(function ($schedule) {
                                $date = is_string($schedule->class_date) ? date('d/m/Y', strtotime($schedule->class_date)) : $schedule->class_date;
                                $time_start = is_string($schedule->start_time) ? date('H:i', strtotime($schedule->start_time)) : $schedule->start_time->format('H:i');
                                $time_end = is_string($schedule->end_time) ? date('H:i', strtotime($schedule->end_time)) : $schedule->end_time->format('H:i');
                                $time = "{$time_start} - {$time_end}";
                                $date = is_string($schedule->class_date) ? date('d/m/Y', strtotime($schedule->class_date)) : $schedule->class_date;
                                return [$schedule->id => "{$schedule->tutor->full_name} - {$date} ({$time})"];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('subject')
                    ->label('Materia')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Textarea::make('reason')
                    ->label('Motivo')
                    ->required()
                    ->rows(3),

                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Aprobada' => 'Aprobada',
                        'Rechazada' => 'Rechazada',
                    ])
                    ->default('Pendiente')
                    ->disabled(fn () => !auth()->user()->hasRole('coordinator'))
                    ->required(),

                Forms\Components\DateTimePicker::make('application_date')
                    ->label('Fecha de Solicitud')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Estudiante')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('schedule.tutor.full_name')
                    ->label('Tutor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('schedule.class_date')
                    ->label('Fecha de Clase')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Materia')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->sortable(),

                Tables\Columns\TextColumn::make('application_date')
                    ->label('Fecha Solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Aprobada' => 'Aprobada',
                        'Rechazada' => 'Rechazada',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Accion para aprobar solicitud
                Tables\Actions\Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Application $record) {
                        $record->update(['status' => 'Aprobada']);
                        // Optionally, you can add a notification here
                    })
                    ->visible(fn (Application $record) => $record->status === 'Pendiente' && auth()->user()->hasRole('coordinator')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
