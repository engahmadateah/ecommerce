<?php

namespace App\Filament\Resources\Returns;

use App\Exceptions\ReturnException;
use App\Filament\Resources\Returns\Pages\ListReturns;
use App\Models\OrderReturn;
use App\Services\Returns\ReturnService;
use App\Support\Money;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/** Customer return requests: approve, reject, or refund to the card. */
class ReturnResource extends Resource
{
    protected static ?string $model = OrderReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUturnLeft;

    protected static ?string $modelLabel = 'return';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $open = OrderReturn::query()->where('status', OrderReturn::REQUESTED)->count();

        return $open > 0 ? (string) $open : null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('order_id')->label('Order')->prefix('#')->sortable()->searchable(),
                TextColumn::make('order.total_price')->label('Order total')->money('USD'),
                TextColumn::make('reason')
                    ->formatStateUsing(fn (string $state) => OrderReturn::reasonLabels()[$state] ?? $state),
                TextColumn::make('details')->limit(40)->tooltip(fn ($record) => $record->details)->toggleable(),
                TextColumn::make('items_summary')
                    ->label('Items')
                    ->state(function (OrderReturn $record) {
                        $items = $record->order->items->keyBy('id');

                        return collect($record->quantities())
                            ->map(fn ($qty, $id) => ($items[$id]->name ?? 'Item')." × {$qty}")
                            ->implode(', ');
                    })
                    ->wrap(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'requested' => 'warning',
                        'approved' => 'info',
                        'refunded' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('refund_amount')->money('USD')->placeholder('—'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                self::approveAction(),
                self::refundAction(),
                self::rejectAction(),
            ]);
    }

    private static function approveAction(): Action
    {
        return Action::make('approve')
            ->icon(Heroicon::OutlinedCheck)
            ->color('info')
            ->visible(fn (OrderReturn $record) => $record->status === OrderReturn::REQUESTED)
            ->requiresConfirmation()
            ->action(fn (OrderReturn $record) => self::run(fn () => app(ReturnService::class)->approve($record), 'Return approved'));
    }

    private static function rejectAction(): Action
    {
        return Action::make('reject')
            ->icon(Heroicon::OutlinedXMark)
            ->color('danger')
            ->visible(fn (OrderReturn $record) => $record->isOpen())
            ->schema([
                Textarea::make('note')->label('Message to the customer')->maxLength(500),
            ])
            ->action(fn (OrderReturn $record, array $data) => self::run(
                fn () => app(ReturnService::class)->reject($record, $data['note'] ?? null),
                'Return rejected',
            ));
    }

    private static function refundAction(): Action
    {
        return Action::make('refund')
            ->icon(Heroicon::OutlinedBanknotes)
            ->color('success')
            ->visible(fn (OrderReturn $record) => $record->isOpen())
            ->modalDescription('The money goes back to the customer\'s card through Stripe. This cannot be undone.')
            ->fillForm(fn (OrderReturn $record) => [
                'amount' => Money::fromCents(app(ReturnService::class)->suggestedRefundCents($record)),
                'restock' => true,
            ])
            ->schema([
                TextInput::make('amount')->label('Refund amount (USD)')->numeric()->minValue(0.01)->required(),
                Toggle::make('restock')->label('Put the items back in stock'),
                Textarea::make('note')->label('Message to the customer')->maxLength(500),
            ])
            ->action(fn (OrderReturn $record, array $data) => self::run(
                fn () => app(ReturnService::class)->refund(
                    $record,
                    Money::toCents($data['amount']),
                    (bool) ($data['restock'] ?? false),
                    $data['note'] ?? null,
                ),
                'Refund sent',
            ));
    }

    /** Runs a return action and shows the result as a notification instead of an error page. */
    private static function run(callable $do, string $success): void
    {
        try {
            $do();
            Notification::make()->title($success)->success()->send();
        } catch (ReturnException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        } catch (\Throwable $e) {
            report($e);
            Notification::make()->title('The payment provider refused the request. Nothing was changed.')->body($e->getMessage())->danger()->send();
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReturns::route('/'),
        ];
    }
}
