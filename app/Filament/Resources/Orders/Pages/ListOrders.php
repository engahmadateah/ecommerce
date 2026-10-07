<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Support\OrderCsv;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export to Excel (CSV)')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->schema([
                    DatePicker::make('from')->label('From (optional)'),
                    DatePicker::make('to')->label('To (optional)'),
                ])
                ->action(fn (array $data) => OrderCsv::stream(
                    filled($data['from'] ?? null) ? Carbon::parse($data['from']) : null,
                    filled($data['to'] ?? null) ? Carbon::parse($data['to']) : null,
                )),
            CreateAction::make(),
        ];
    }
}
