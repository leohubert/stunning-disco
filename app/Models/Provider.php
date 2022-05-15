<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Provider extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'country_id'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
