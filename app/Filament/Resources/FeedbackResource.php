<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Application;
use App\Models\Feedback;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Tutorías';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('application_id')
                ->label('Solicitud')
                ->options(function () {
                    $user = auth()->user();
                    $studentId = Student::where('user_id', $user->id)->value('id');

                    if (!$studentId) {
                        return [];
                    }

                    return Application::where('student_id', $studentId)
                        ->get()
                        ->mapWithKeys(function ($application) {
                            $date = is_string($application->created_at)
                                ? date('d/m/Y', strtotime($application->created_at))
                                : $application->created_at->format('d/m/Y');
                            return [$application->id => "{$application->subject} - {$date}"];
                        })
                        ->toArray();
                })
                ->searchable()
                ->required(),

            Forms\Components\Select::make('rating')
                ->label('Calificación')
                ->options([
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                ])
                ->required(),

            Forms\Components\Textarea::make('comment')
                ->label('Comentario')
                ->required()
                ->rows(4),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application.subject')
                    ->label('Materia')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('rating')
                    ->label('Calificación')
                    ->sortable()
                    ->colors([
                        'danger' => fn ($state): bool => $state <= 2,
                        'warning' => fn ($state): bool => $state == 3,
                        'success' => fn ($state): bool => $state >= 4,
                    ]),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Comentario')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->comment)
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}
