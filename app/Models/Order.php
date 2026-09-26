<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [];

    /** @var array<int, string> */
    public const STATUSES = ['hazirlaniyor', 'kargoda', 'teslim_edildi', 'iptal'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    /** @return HasMany<OrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return __('shop.status.'.$this->status);
    }

    public function paymentLabel(): string
    {
        $label = __('shop.payment_methods.'.$this->payment_method);

        if ($this->payment_method === 'kredi_karti' && $this->card_last_four) {
            return $label.' •••• '.$this->card_last_four;
        }

        return $label;
    }
}
