<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Estudiantes';
    protected static ?string $navigationLabel = 'Estudiantes';
    protected static ?string $pluralModelLabel = 'Estudiantes';
    protected static ?string $modelLabel = 'Estudiante';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')
                    ->required()
                    ->label('Nombre(s)')
                    ->maxLength(255),
                Forms\Components\TextInput::make('user.last_name')
                    ->label('Apellidos')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('user.email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->rules(function ($get, $record) {
                        return [
                            Rule::unique('users', 'email')->ignore($record?->user?->id),
                        ];
                    })
                    ->maxLength(255),

                Forms\Components\TextInput::make('user.password')
                    ->label('Contraseña')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
                Forms\Components\TextInput::make('carrier')
                    ->label('Carrera')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('user.name')->label('Usuario')->searchable(),
                Tables\Columns\TextColumn::make('carrier')->label('Carrera')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Creado'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
