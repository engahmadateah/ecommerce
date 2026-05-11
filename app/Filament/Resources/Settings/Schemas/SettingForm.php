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

        ]);
    }
}