<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSetting extends Model
{
    protected $table = 'event_settings';

    protected $fillable = [
        'partner_1_name',
        'partner_1_father',
        'partner_1_mother',
        'partner_2_name',
        'partner_2_father',
        'partner_2_mother',
        'tagline',
        'event_date',
        'start_time',
        'end_time',
        'venue_name',
        'venue_address',
        'map_link_url',
        'wa_template_msg',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }
}
