<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Models\Coupon;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('code')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('title')
                ->required()
                ->maxLength(255),

            Select::make('type')
                ->options([
                    'fixed' => 'Fixed ($)',
                    'percent' => 'Percent (%)',
                ])
                ->required(),

            TextInput::make('value')
                ->numeric()
                ->required(),

            Select::make('required_level')
                ->label('Minimum user level')
                ->options([
                    'bronze' => 'Bronze',
                    'silver' => 'Silver',
                    'gold'   => 'Gold',
                ])
                ->default('bronze')
                ->required()
                ->helperText('The user must have this level or higher to use the coupon.'),

            DateTimePicker::make('expires_at'),

            TextInput::make('usage_limit')
                ->numeric(),

            TextInput::make('used')
                ->numeric()
                ->default(0)
                ->disabled(),

            Textarea::make('description')
                ->rows(3),

            Textarea::make('how_to_use')
                ->rows(4),

            FileUpload::make('image')
                ->image()
                ->disk('public')
                ->directory('coupons')
                ->visibility('public'),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            TextColumn::make('code')
                ->searchable(),

            TextColumn::make('title')
                ->searchable(),

            TextColumn::make('type'),

            TextColumn::make('value'),

            TextColumn::make('required_level')
                ->badge()
                ->colors([
                    'warning' => 'bronze',
                    'secondary' => 'silver',
                    'success' => 'gold',
                ]),

            TextColumn::make('used')
                ->label('Used'),

            TextColumn::make('usage_limit')
                ->label('Limit'),

            TextColumn::make('expires_at')
                ->dateTime(),

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
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}