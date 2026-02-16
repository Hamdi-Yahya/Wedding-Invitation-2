<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return view('dashboard.guests');
    }

    public function apiIndex()
    {
        $guests = Guest::orderBy('created_at', 'desc')->get();

        return response()->json(['success' => true, 'guests' => $guests]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'category' => 'nullable|in:VIP,Regular',
        ]);

        $validated['slug'] = generateSlug($validated['name']);
        $validated['qr_code_string'] = $this->generateUniqueQR();
        $validated['category'] = $validated['category'] ?? 'Regular';

        $guest = Guest::create($validated);

        return response()->json(['success' => true, 'guest' => $guest], 201);
    }

    public function show(int $id)
    {
        $guest = Guest::with('wishes')->findOrFail($id);

        return response()->json(['success' => true, 'guest' => $guest]);
    }

    public function update(Request $request, int $id)
    {
        $guest = Guest::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'category' => 'nullable|in:VIP,Regular',
        ]);

        $guest->update($validated);

        return response()->json(['success' => true, 'guest' => $guest]);
    }

    public function destroy(int $id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();

        return response()->json(['success' => true, 'message' => 'Tamu berhasil dihapus.']);
    }

    private function generateUniqueQR(): string
    {
        do {
            $qr = generateQRString();
        } while (Guest::where('qr_code_string', $qr)->exists());

        return $qr;
    }
}
