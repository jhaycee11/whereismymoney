<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'amount',
        'day_of_month',
        'notes',
        'is_active',
        'last_executed_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'day_of_month' => 'integer',
        'is_active' => 'boolean',
        'last_executed_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
