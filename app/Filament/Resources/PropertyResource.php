<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-home'; // Іконка будиночка

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        // --- LEFT COLUMN (2/3) ---
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Основна інформація')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Заголовок')
                                            ->required(),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('city')->required()->label('Місто'),
                                                TextInput::make('region')->label('Область'),
                                                TextInput::make('district')->label('Район'),
                                                TextInput::make('zip_code')->label('Індекс'),
                                                TextInput::make('street_address')->required()->label('Вулиця'),
                                                TextInput::make('street_number')->required()->label('Номер будинку'),
                                            ]),
                                        RichEditor::make('description')
                                            ->label('Опис')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Галерея')
                                    ->schema([
                                        Repeater::make('images')
                                            ->relationship()
                                            ->schema([
                                                FileUpload::make('path')
                                                    ->label('Фото')
                                                    ->disk('public')
                                                    ->directory('properties')
                                                    ->image()
                                                    ->imageEditor(),
                                            ])
                                            ->grid(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Додати фото'),
                                    ]),
                            ]),
                        // --- RIGHT COLUMN (1/3) ---
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Деталі')
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Ціна')
                                            ->numeric()
                                            ->prefix('$')
                                            ->required(),

                                        TextInput::make('area')
                                            ->label('Площа (м²)')
                                            ->numeric()
                                            ->required(),

                                        Select::make('status')
                                            ->options([
                                                'active' => 'Активно',
                                                'sold' => 'Продано',
                                                'rented' => 'Здано в оренду',
                                            ])
                                            ->default('active')
                                            ->required(),

                                        Select::make('type')
                                            ->options([
                                                'apartment' => 'Квартира',
                                                'house' => 'Будинок',
                                                'land' => 'Ділянка',
                                            ])
                                            ->required(),
                                    ]),
                                Section::make('Зручності')
                                    ->schema([
                                        CheckboxList::make('amenities')
                                            ->relationship(titleAttribute: 'name')
                                            ->bulkToggleable()
                                            ->columns(1),
                                    ]),
                            ]),
                    ]),
            ]);
    }
    // SETTING A TABLE (LABELED COLUMNS)
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.path')
                    ->label('Фото')
                    ->circular()
                    ->stacked()
                    ->limit(1),

                TextColumn::make('title')
                    ->label('Назва')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Ціна')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'apartment' => 'Квартира',
                        'house' => 'Будинок',
                        'land' => 'Ділянка',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'sold' => 'danger',
                        'rented' => 'warning',
                    }),

                TextColumn::make('created_at')
                    ->label('Додано')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
