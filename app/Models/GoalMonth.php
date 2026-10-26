<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalMonth extends Model
{
    protected $fillable = ['sales_goal_id', 'month', 'target_amount', 'is_fixed'];

    protected $casts = [
        'is_fixed' => 'boolean',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(SalesGoal::class, 'sales_goal_id');
    }
}
