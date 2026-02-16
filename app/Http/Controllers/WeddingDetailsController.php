<?php

namespace App\Http\Controllers;

use App\Models\WeddingDetail;
use Illuminate\Http\Request;

class WeddingDetailsController extends Controller
{
    public function get()
    {
        $details = WeddingDetail::firstOrCreate(['id' => 1], [
            'ceremony_title' => 'The Ceremony',
            'reception_title' => 'The Reception',
            'dress_code_title' => 'Dress Code',
            'dress_code_style1' => 'Formal Attire',
            'dress_code_style2' => 'Smart Casual',
        ]);

        return response()->json(['success' => true, 'details' => $details]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'ceremony_title' => 'nullable|string|max:255',
            'ceremony_time' => 'nullable|string|max:20',
            'ceremony_venue' => 'nullable|string|max:255',
            'ceremony_address' => 'nullable|string|max:255',
            'reception_title' => 'nullable|string|max:255',
            'reception_time' => 'nullable|string|max:20',
            'reception_venue' => 'nullable|string|max:255',
            'reception_note' => 'nullable|string|max:255',
            'dress_code_title' => 'nullable|string|max:255',
            'dress_code_note' => 'nullable|string|max:255',
            'dress_code_style1' => 'nullable|string|max:255',
            'dress_code_style2' => 'nullable|string|max:255',
        ]);

        $details = WeddingDetail::updateOrCreate(['id' => 1], $validated);

        return response()->json(['success' => true, 'details' => $details]);
    }
}
