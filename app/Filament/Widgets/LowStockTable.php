<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LowStockTable extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Low stock')
            ->query(fn () => Product::query()
                ->where('stock', '<=', (int) config('shop.low_stock_threshold', 5))
                ->orderBy('stock'))
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('sku')->placeholder('—'),
                TextColumn::make('stock')->badge()->color(fn (int $state) => $state <= 0 ? 'danger' : 'warning'),
            ])
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}
