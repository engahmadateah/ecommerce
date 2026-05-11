<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Forms\Components\TextInput::make('name')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required(),

            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state)),

            Forms\Components\TextInput::make('points')
                ->numeric()
                ->default(0)
                ->required(),

            Forms\Components\Select::make('level')
                ->options([
                    'bronze' => 'Bronze',
                    'silver' => 'Silver',
                    'gold'   => 'Gold',
                ])
                ->default('bronze')
                ->required(),

            Forms\Components\Select::make('role')
                ->options([
                    'user' => 'User',
                    'admin' => 'Admin',
                ])
                ->default('user')
                ->required(),

        ]);
    }
}