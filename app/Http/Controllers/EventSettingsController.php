<?php

namespace App\Http\Controllers;

use App\Models\EventSetting;
use Illuminate\Http\Request;

class EventSettingsController extends Controller
{
    public function index()
    {
        $event = EventSetting::firstOrCreate(['id' => 1], [
            'partner_1_name' => 'Nama Partner 1',
            'partner_2_name' => 'Nama Partner 2',
            'event_date' => now()->addMonths(3)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '22:00',
            'venue_name' => 'Nama Venue',
            'venue_address' => 'Alamat Venue',
        ]);

        return view('dashboard.event-settings', compact('event'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'partner_1_name' => 'required|string|max:255',
            'partner_1_father' => 'nullable|string|max:255',
            'partner_1_mother' => 'nullable|string|max:255',
            'partner_2_name' => 'required|string|max:255',
            'partner_2_father' => 'nullable|string|max:255',
            'partner_2_mother' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required|string|max:10',
            'end_time' => 'required|string|max:10',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'map_link_url' => 'nullable|string',
        ]);

        $event = EventSetting::updateOrCreate(['id' => 1], $validated);

        return response()->json([
            'success' => true,
            'event' => $event,
        ]);
    }
}
