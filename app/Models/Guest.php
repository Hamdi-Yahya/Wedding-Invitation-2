<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    protected $table = 'guests';

    protected $fillable = [
        'name',
        'phone_number',
        'category',
        'slug',
        'qr_code_string',
        'rsvp_status',
        'guest_count',
        'check_in_time',
        'gift_type',
    ];

    protected function casts(): array
    {
        return [
            'check_in_time' => 'datetime',
            'guest_count' => 'integer',
        ];
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }
}
