<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('site_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email'),

                Tables\Columns\TextColumn::make('phone'),

            ]);
    }
}