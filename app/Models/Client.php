<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'country_id',
        'is_admin'
    ];

    protected $casts = [
        'is_admin' => 'boolean'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
