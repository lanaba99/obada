<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'currency',
        'method',
        'status',
        'details',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'details' => 'array', // Cast JSON details to an array
    ];

    /**
     * Get the order that the payment belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
