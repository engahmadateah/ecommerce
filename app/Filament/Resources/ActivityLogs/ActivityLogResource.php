<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/** Read-only audit trail of admin changes. */
class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Activity log';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('user.name')->label('Admin')->placeholder('—'),
                TextColumn::make('action')->badge()->color(fn (string $state) => match ($state) {
                    'created' => 'success',
                    'deleted' => 'danger',
                    default => 'info',
                }),
                TextColumn::make('subject_type')->label('Type')->searchable(),
                TextColumn::make('subject_label')->label('Item')->searchable()->limit(40),
                TextColumn::make('changes')
                    ->state(function (ActivityLog $record) {
                        return collect($record->changes ?? [])
                            ->map(fn ($pair, $field) => $field.': '.self::short($pair[0] ?? null).' → '.self::short($pair[1] ?? null))
                            ->implode("\n");
                    })
                    ->wrap()
                    ->limit(200),
            ]);
    }

    private static function short(mixed $value): string
    {
        $text = is_scalar($value) || $value === null ? (string) $value : json_encode($value);

        return mb_strlen($text) > 40 ? mb_substr($text, 0, 40).'…' : ($text === '' ? '∅' : $text);
    }

    public static function getPages(): array
    {
        return ['index' => ListActivityLogs::route('/')];
    }
}
