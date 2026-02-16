<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wish;
use Illuminate\Http\Request;

class WishController extends Controller
{
    public function index()
    {
        return view('dashboard.wishes');
    }

    public function apiIndex(Request $request)
    {
        $query = Wish::with('guest')->orderBy('created_at', 'desc');

        if (!$request->has('all')) {
            $query->where('is_approved', true);
        }

        return response()->json(['success' => true, 'wishes' => $query->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $slug = generateSlug($validated['name']);
        $guest = Guest::where('name', $validated['name'])->first();

        if (!$guest) {
            $guest = Guest::create([
                'name' => sanitizeString($validated['name']),
                'slug' => $slug,
                'qr_code_string' => $this->generateUniqueQR(),
                'category' => 'Regular',
                'rsvp_status' => 'Pending',
            ]);
        }

        $sanitizedMessage = sanitizeString($validated['message']);

        $existing = Wish::where('guest_id', $guest->id)
            ->where('message', $sanitizedMessage)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'wish' => [
                    'id' => $existing->id,
                    'guestName' => $guest->name,
                    'message' => $existing->message,
                    'created_at' => $existing->created_at,
                ],
            ], 200);
        }

        $wish = Wish::create([
            'guest_id' => $guest->id,
            'message' => $sanitizedMessage,
            'is_approved' => false,
        ]);

        return response()->json([
            'success' => true,
            'wish' => [
                'id' => $wish->id,
                'guestName' => $guest->name,
                'message' => $wish->message,
                'created_at' => $wish->created_at,
            ],
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $wish = Wish::findOrFail($id);

        $validated = $request->validate([
            'is_approved' => 'required|boolean',
        ]);

        $wish->update($validated);

        return response()->json(['success' => true, 'wish' => $wish->load('guest')]);
    }

    public function destroy(int $id)
    {
        $wish = Wish::findOrFail($id);
        $wish->delete();

        return response()->json(['success' => true, 'message' => 'Ucapan berhasil dihapus.']);
    }

    public function approveAll()
    {
        Wish::where('is_approved', false)->update(['is_approved' => true]);

        return response()->json(['success' => true, 'message' => 'Semua ucapan berhasil di-approve.']);
    }

    private function generateUniqueQR(): string
    {
        do {
            $qr = generateQRString();
        } while (Guest::where('qr_code_string', $qr)->exists());

        return $qr;
    }
}
