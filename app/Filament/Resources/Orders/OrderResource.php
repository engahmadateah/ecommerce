<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;







class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
    
            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->placeholder('Guest (no account)'),

            TextInput::make('guest_email')
                ->label('Guest e-mail')
                ->email()
                ->disabled(),
    
            TextInput::make('total_price')
                ->numeric()
                ->disabled(),
    
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'shipped' => 'Shipped',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'refunded' => 'Refunded',
                ])
                ->required()
                ->default('pending')
                ->helperText('"paid" and "cancelled" are normally set automatically by the payment flow, not chosen here.'),
    
        ]);
    }

    
    public static function table(Table $table): Table
    {
        return $table->columns([
    
            TextColumn::make('id')
                ->sortable(),
    
            TextColumn::make('user.name')
                ->label('Customer')
                ->placeholder('Guest')
                ->searchable(),

            TextColumn::make('guest_email')
                ->label('Guest e-mail')
                ->searchable()
                ->toggleable(),
    
            TextColumn::make('total_price')
                ->money('USD'),
    
            BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'paid',
                    'info' => 'shipped',
                    'primary' => 'completed',
                    'danger' => 'cancelled',
                    'gray' => 'refunded',
                ]),
    
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
    
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
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getRecordTitleAttribute(): string
{
    return 'id';
}
}
