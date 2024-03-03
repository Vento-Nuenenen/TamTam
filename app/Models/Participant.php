<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participations';

    protected $fillable = [
        'scout_name',
        'first_name',
        'last_name',
        'address',
        'plz',
        'place',
        'birthday',
        'gender',
        'person_picture',
        'barcode',
        'seat_number',
        'course_passed',
        'group_id',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
