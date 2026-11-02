<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyGoal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'products' => 'array',
        'assigned_rep_ids' => 'array',
    ];

    public function marketingPlan()
    {
        return $this->belongsTo(MarketingPlan::class);
    }
}
