<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // ID
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                // Package Name
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Description
                TextColumn::make('description')
                    ->limit(40)
                    ->toggleable(),

                // Price
                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),

                // Products Count
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->badge()
                    ->color('success'),

                // Created Date
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),

            ])

            ->actions([

                EditAction::make(),

                DeleteAction::make(),

            ])

            ->bulkActions([

                DeleteBulkAction::make(),

            ]);
    }
}