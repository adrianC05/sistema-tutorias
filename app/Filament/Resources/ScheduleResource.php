<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use App\Models\Tutor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Tutorías';
    protected static ?string $navigationLabel = 'Programación de Clases';
    protected static ?string $pluralModelLabel = 'Programaciones de Clases';
    protected static ?string $modelLabel = 'Programación de Clase';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Horario')
                    ->description('Define los detalles de la programación de la clase.')
                    ->schema([
                        Forms\Components\Select::make('tutor_id')
                            ->label('Tutor 🧑‍🏫')
                            ->options(function () {
                                return Tutor::all()->pluck('full_name', 'id')->toArray();
                            })
                            ->searchable()
                            ->preload(10)
                            ->required()
                            ->hidden(fn () => auth()->user()->hasRole('tutor'))
                            ->helperText('Selecciona el tutor asignado para esta clase.'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('class_date')
                                    ->label('Fecha de la Clase 🗓️')
                                    ->displayFormat('d/m/Y')
                                    ->nullable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('start_time')
                                    ->label('Hora de Inicio ⏰')
                                    ->prefixIcon('heroicon-o-clock')
                                    ->native(false)
                                    ->seconds(false)
                                    ->minutesStep(15)
                                    ->displayFormat('H:i')
                                    ->required()
                                    ->columnSpan(1),

                                Forms\Components\TimePicker::make('end_time')
                                    ->label('Hora de Fin 🏁')
                                    ->prefixIcon('heroicon-o-check-circle')
                                    ->native(false)
                                    ->seconds(false)
                                    ->minutesStep(15)
                                    ->displayFormat('H:i')
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tutor.user.name')->label('Nombre')->searchable(),
                Tables\Columns\TextColumn::make('tutor.user.last_name')->label('Apellido')->searchable(),
                Tables\Columns\TextColumn::make('class_date')->date('d/m/Y')->label('Fecha de la clase'),
                Tables\Columns\TextColumn::make('start_time')->dateTime('H:i')->label('Hora de inicio'),
                Tables\Columns\TextColumn::make('end_time')->dateTime('H:i')->label('Hora de fin'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Creado')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ActivityLogTimelineTableAction::make('Activities')
                    ->withRelations(['profile', 'address'])
                    ->timelineIcons([
                        'created' => 'heroicon-m-check-badge',
                        'updated' => 'heroicon-m-pencil-square',
                    ])
                    ->timelineIconColors([
                        'created' => 'info',
                        'updated' => 'warning',
                    ])
                    ->limit(10),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            #'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
