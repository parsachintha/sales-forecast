<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**  ONLY - replace with your real sales/invoice model. */
class Sale extends Model
{
    protected $fillable = ['rep_id', 'amount', 'sold_at'];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function rep(): BelongsTo
    {
        return $this->belongsTo(Rep::class);
    }
}
