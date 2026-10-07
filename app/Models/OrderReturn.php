<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReturn extends Model
{
    public const REQUESTED = 'requested';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const REFUNDED = 'refunded';

    /** Reasons offered to the customer (translation keys). */
    public const REASONS = ['damaged', 'wrong_item', 'not_as_described', 'changed_mind', 'other'];

    protected $fillable = [
        'order_id', 'status', 'reason', 'details', 'items', 'refund_amount',
        'restocked', 'admin_note', 'refund_id', 'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'restocked' => 'boolean',
            'refunded_at' => 'datetime',
        ];
    }

    /** @return array<string,string> reason code => translated label */
    public static function reasonLabels(): array
    {
        return [
            'damaged' => __('Arrived damaged'),
            'wrong_item' => __('Wrong item received'),
            'not_as_described' => __('Not as described'),
            'changed_mind' => __('Changed my mind'),
            'other' => __('Other reason'),
        ];
    }

    public function reasonLabel(): string
    {
        return self::reasonLabels()[$this->reason] ?? $this->reason;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::REQUESTED => __('Under review'),
            self::APPROVED => __('Approved'),
            self::REJECTED => __('Rejected'),
            self::REFUNDED => __('Refunded'),
            default => $this->status,
        };
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [self::REQUESTED, self::APPROVED], true);
    }

    /** @return array<int,int> order_item_id => quantity */
    public function quantities(): array
    {
        $out = [];
        foreach ($this->items ?? [] as $row) {
            $out[(int) $row['order_item_id']] = ($out[(int) $row['order_item_id']] ?? 0) + (int) $row['quantity'];
        }

        return $out;
    }
}
