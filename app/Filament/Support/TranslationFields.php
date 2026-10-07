<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

/** The "Translations" box in the admin forms: one name (and description) per extra language. */
class TranslationFields
{
    public static function section(bool $withDescription = true): Section
    {
        $fields = [];

        foreach (config('shop.locales') as $code => $label) {
            if ($code === config('shop.base_locale', 'en')) {
                continue;
            }

            $fields[] = TextInput::make("translations.{$code}.name")
                ->label("Name ({$label})")
                ->maxLength(255);

            if ($withDescription) {
                $fields[] = Textarea::make("translations.{$code}.description")
                    ->label("Description ({$label})")
                    ->columnSpanFull();
            }
        }

        return Section::make('Translations')
            ->description('Leave empty to show the English text in that language.')
            ->schema($fields)
            ->collapsible();
    }
}
