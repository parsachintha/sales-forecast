<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesGoal extends Model
{
    protected $fillable = ['rep_id', 'title', 'year', 'baseline_amount', 'target_amount'];

    public function rep(): BelongsTo
    {
        return $this->belongsTo(Rep::class);
    }

    public function months(): HasMany
    {
        return $this->hasMany(GoalMonth::class)->orderBy('month');
    }
}
