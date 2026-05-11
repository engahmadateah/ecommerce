<?php

namespace App\Filament\Resources\Shippings;

use App\Filament\Resources\Shippings\Pages\CreateShipping;
use App\Filament\Resources\Shippings\Pages\EditShipping;
use App\Filament\Resources\Shippings\Pages\ListShippings;
use App\Filament\Resources\Shippings\Schemas\ShippingForm;
use App\Filament\Resources\Shippings\Tables\ShippingsTable;
use App\Models\Shipping;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
    
            Select::make('order_id')
                ->relationship('order', 'id')
                ->searchable()
                ->required(),
    
            TextInput::make('full_name')->required(),
            TextInput::make('phone')->required(),
    
            TextInput::make('address')->required(),
            TextInput::make('city')->required(),
            TextInput::make('country')->required(),
    
            TextInput::make('carrier')
                ->placeholder('DHL, Aramex...'),
    
            TextInput::make('tracking_number'),
    
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'packed' => 'Packed',
                    'shipped' => 'Shipped',
                    'in_transit' => 'In Transit',
                    'delivered' => 'Delivered',
                ])
                ->default('pending')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
    
            TextColumn::make('order.id')
                ->label('Order'),
    
            TextColumn::make('full_name'),
    
            TextColumn::make('city'),
    
            TextColumn::make('carrier'),
    
            TextColumn::make('tracking_number'),
    
            BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'primary' => 'packed',
                    'info' => 'shipped',
                    'secondary' => 'in_transit',
                    'success' => 'delivered',
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
            'index' => ListShippings::route('/'),
            'create' => CreateShipping::route('/create'),
            'edit' => EditShipping::route('/{record}/edit'),
        ];
    }
}
