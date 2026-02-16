<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $table = 'theme_settings';

    protected $fillable = [
        'theme_name',
        'primary_color',
        'secondary_color',
        'font_family',
        'background_image_url',
    ];
}
