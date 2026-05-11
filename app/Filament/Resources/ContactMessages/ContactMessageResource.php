<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\CreateContactMessage;
use App\Filament\Resources\ContactMessages\Pages\EditContactMessage;
use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Contact Messages';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('name')
                ->disabled(),

            TextInput::make('email')
                ->disabled(),

            Textarea::make('message')
                ->rows(8)
                ->disabled(),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            TextColumn::make('name')
                ->searchable(),

            TextColumn::make('email')
                ->searchable(),

            TextColumn::make('message')
                ->limit(50),

            TextColumn::make('created_at')
                ->dateTime(),

        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),
           
            'edit' => EditContactMessage::route('/{record}/edit'),
        ];
    }
}