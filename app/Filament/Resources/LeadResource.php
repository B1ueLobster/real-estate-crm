<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    // Icon for the menu (person or group)
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Клієнти (Ліди)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([ // Main grid with 3 columns
                        // --- LEFT COLUMN (2/3) ---
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Контакти')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('first_name')
                                                    ->label('Ім\'я')
                                                    ->required(),
                                                TextInput::make('last_name')
                                                    ->label('Прізвище'),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('email')
                                                    ->email()
                                                    ->label('Email'),
                                                TextInput::make('phone')
                                                    ->tel()
                                                    ->label('Телефон'),
                                            ]),
                                    ]),

                                Section::make('Нотатки')
                                    ->schema([ // Notes section
                                        Textarea::make('notes')
                                            ->label('Внутрішні коментарі')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        // --- RIGHT COLUMN (1/3) ---
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Статус та Етап')
                                    ->schema([
                                        Select::make('status')
                                            ->options([
                                                'new' => 'Новий',
                                                'contacted' => 'Був контакт',
                                                'qualified' => 'Зацікавлений',
                                                'lost' => 'Відмова',
                                                'customer' => 'Купив',
                                            ])
                                            ->default('new')
                                            ->required(),

                                        Select::make('source')
                                            ->label('Джерело')
                                            ->options([
                                                'website' => 'Сайт',
                                                'referral' => 'Рекомендація',
                                                'social' => 'Соцмережі',
                                                'other' => 'Інше',
                                            ]),

                                        // Assign to agent
                                        Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->label('Відповідальний Агент:')
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('Клієнт')
                    // Combine first and last name into one column for display
                    ->formatStateUsing(function ($record) {
                        return $record->full_name;
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Телефон')
                    ->icon('heroicon-m-phone')
                    ->copyable(), // Click to copy the number

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',       // Синій
                        'contacted' => 'warning', // Жовтий
                        'qualified' => 'success', // Зелений
                        'lost' => 'danger',    // Червоний (Red)
                        'customer' => 'success', // Зелений (Green)
                        default => 'gray', // Сірий (Gray)
                    }), // Status badge with color coding

                TextColumn::make('source')
                    ->label('Джерело')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // We will add status filters later
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
