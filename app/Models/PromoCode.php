<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2', // Cast to decimal with 2 precision
        'min_order_amount' => 'decimal:2', // Cast to decimal with 2 precision
    ];

    /**
     * Get the orders that have used this promo code.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'promo_code_id'); // Assuming orders table has a promo_code_id
    }
}