<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\EventSetting;
use App\Models\ThemeSetting;

class ExportController extends Controller
{
    public function index()
    {
        $guests = Guest::orderBy('name', 'asc')->get();
        $event = EventSetting::first();
        $theme = ThemeSetting::first();

        return view('dashboard.export', compact('guests', 'event', 'theme'));
    }
}
