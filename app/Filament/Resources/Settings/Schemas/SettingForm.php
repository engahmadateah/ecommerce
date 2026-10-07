<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Forms\Components\TextInput::make('site_name')
                ->label('Site Name'),

            Forms\Components\TextInput::make('facebook')
                ->label('Facebook URL'),

            Forms\Components\TextInput::make('instagram')
                ->label('Instagram URL'),

            Forms\Components\TextInput::make('twitter')
                ->label('Twitter / X URL'),

            Forms\Components\TextInput::make('tiktok')
                ->label('TikTok URL'),

            Forms\Components\TextInput::make('email')
                ->email(),

            Forms\Components\TextInput::make('phone'),

            Forms\Components\Textarea::make('address')
                ->rows(3),

            Forms\Components\Textarea::make('about')
                ->rows(5),

            Forms\Components\Textarea::make('privacy_policy')
                ->rows(6),

            Forms\Components\Textarea::make('terms')
                ->rows(6),

            Forms\Components\TextInput::make('footer_text')
                ->label('Footer Text'),

            Forms\Components\TextInput::make('shipping_fee')
                ->label('Shipping fee (USD)')
                ->numeric()->minValue(0)
                ->helperText('Flat price per order. Leave empty to use the default (config/shop.php).'),

            Forms\Components\TextInput::make('free_shipping_threshold')
                ->label('Free shipping from (USD)')
                ->numeric()->minValue(0)
                ->helperText('Orders whose goods total (after discounts) reaches this amount ship free. Empty = never free.'),

            Forms\Components\TextInput::make('tax_rate')
                ->label('Tax / VAT rate (%)')
                ->numeric()->minValue(0)->maxValue(100)
                ->helperText('0 or empty = no tax.'),

            Forms\Components\Select::make('tax_included')
                ->label('Do your prices already include tax?')
                ->options([1 => 'Yes, prices include tax (shown as "includes")', 0 => 'No, add tax at checkout'])
                ->placeholder('Use default'),

            Forms\Components\TextInput::make('tax_label')
                ->label('Tax name')
                ->maxLength(30)
                ->placeholder('VAT'),

        ]);
    }
}