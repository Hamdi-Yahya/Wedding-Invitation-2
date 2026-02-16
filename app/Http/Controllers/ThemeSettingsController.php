<?php

namespace App\Http\Controllers;

use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class ThemeSettingsController extends Controller
{
    public function index()
    {
        $theme = ThemeSetting::firstOrCreate(['id' => 1], [
            'theme_name' => 'Elegant Gold',
            'primary_color' => '#9B7B2C',
            'secondary_color' => '#FAF6EE',
            'font_family' => 'Playfair Display',
        ]);

        return view('dashboard.theme-settings', compact('theme'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'font_family' => 'nullable|string|max:255',
            'background_image_url' => 'nullable|string',
        ]);

        $theme = ThemeSetting::updateOrCreate(['id' => 1], $validated);

        return response()->json(['success' => true, 'theme' => $theme]);
    }
}
