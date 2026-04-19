<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_id',
        'caller_id',
        'listener_id',
        'amount',
        'message',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }

    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function listener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'listener_id');
    }
}
