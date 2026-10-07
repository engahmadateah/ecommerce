<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Options of a product (size, colour, ...). When a product has active variants
 * the customer must pick one, and the product's own stock becomes their sum.
 */
class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Variants (size / colour / ...)';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Option name')
                ->placeholder('e.g. Large / Red')
                ->required()
                ->maxLength(255),

            TextInput::make('sku')
                ->label('SKU')
                ->maxLength(64)
                ->unique(ignoreRecord: true),

            TextInput::make('price')
                ->numeric()
                ->minValue(0)
                ->helperText('Leave empty to use the product price.'),

            TextInput::make('discount_price')
                ->numeric()
                ->minValue(0)
                ->helperText('Only used when this option has its own price.'),

            TextInput::make('stock')
                ->numeric()
                ->integer()
                ->minValue(0)
                ->default(0)
                ->required(),

            TextInput::make('sort_order')
                ->numeric()
                ->integer()
                ->default(0),

            Toggle::make('is_active')
                ->label('Available')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('sku')->label('SKU')->toggleable(),
                TextColumn::make('price')->money('USD')->placeholder('Product price'),
                TextColumn::make('stock')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                IconColumn::make('is_active')->label('Available')->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
