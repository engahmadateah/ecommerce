<?php

namespace App\Models\Concerns;

/**
 * Per-language name/description kept in one `translations` column:
 *   {"ar": {"name": "...", "description": "..."}}
 *
 * The normal `name` / `description` columns stay the shop's base language (English),
 * so orders, invoices, e-mails and Stripe keep using them. Pages shown to visitors
 * use `localized_name` / `localized_description`, which fall back to the base text.
 */
trait HasTranslations
{
    public function initializeHasTranslations(): void
    {
        $this->mergeFillable(['translations']);
        $this->mergeCasts(['translations' => 'array']);
    }

    /** Saved with real Arabic letters (not م codes) so the shop search can find them. */
    public function setTranslationsAttribute($value): void
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        $clean = [];

        foreach ((array) $value as $locale => $fields) {
            $fields = array_filter((array) $fields, fn ($text) => filled($text));

            if ($fields !== []) {
                $clean[$locale] = $fields;
            }
        }

        $this->attributes['translations'] = $clean === [] ? null : json_encode($clean, JSON_UNESCAPED_UNICODE);
    }

    public function translated(string $field, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale !== config('shop.base_locale', 'en')) {
            $text = data_get($this->translations, "{$locale}.{$field}");

            if (filled($text)) {
                return $text;
            }
        }

        return $this->getAttribute($field);
    }

    public function getLocalizedNameAttribute(): ?string
    {
        return $this->translated('name');
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return $this->translated('description');
    }
}
