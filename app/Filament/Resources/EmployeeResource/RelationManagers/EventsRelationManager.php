<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Resources\Components\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\EventType;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('type_event')
                    ->inline()
                    ->options(EventType::class)
                    ->required(),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('start'),

                        Forms\Components\DateTimePicker::make('end'),
                    ]),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type_event')
                    ->badge(),
                Tables\Columns\TextColumn::make('start')
                    ->label('start')
                    ->date()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('end')
                    ->label('end')
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'vacation' => Tab::make()->query(fn ($query) => $query->where('type_event', 'vacation')),
            'sick leave' => Tab::make()->query(fn ($query) => $query->where('type_event', 'sickLeave')),
            'duty' => Tab::make()->query(fn ($query) => $query->where('type_event', 'duty')),
            'personal expense' => Tab::make()->query(fn ($query) => $query->where('type_event', 'personalExpense')),
        ];
    }
}
