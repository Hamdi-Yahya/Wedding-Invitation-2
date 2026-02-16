<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingDetail extends Model
{
    protected $table = 'wedding_details';

    protected $fillable = [
        'ceremony_title',
        'ceremony_time',
        'ceremony_venue',
        'ceremony_address',
        'reception_title',
        'reception_time',
        'reception_venue',
        'reception_note',
        'dress_code_title',
        'dress_code_note',
        'dress_code_style1',
        'dress_code_style2',
    ];
}
