<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Points extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'points',
        'is_addition',
    ];

    public function kid(): BelongsTo
    {
        return $this->belongsTo(Kid::class);
    }
}
