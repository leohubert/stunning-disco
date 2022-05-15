<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'amount',

        'buyer_id',
        'buyer_type',

        'seller_id',
        'seller_type',

        'product_id',
        'responsible'
    ];

    public function seller(): MorphTo
    {
        return $this->morphTo();
    }

    public function buyer(): MorphTo
    {
        return $this->morphTo();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
