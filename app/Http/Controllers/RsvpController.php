<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'nullable|string',
            'name' => 'required_without:slug|nullable|string|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'rsvpStatus' => 'required|in:Coming,Not Coming',
            'guestCount' => 'nullable|integer|min:1',
        ]);

        $guestCount = $validated['guestCount'] ?? 1;

        if (!empty($validated['slug'])) {
            $guest = Guest::where('slug', $validated['slug'])->first();

            if (!$guest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tamu tidak ditemukan.',
                ], 404);
            }

            $updateData = [
                'rsvp_status' => $validated['rsvpStatus'],
                'guest_count' => $guestCount,
            ];

            if (!empty($validated['phoneNumber'])) {
                $updateData['phone_number'] = $validated['phoneNumber'];
            }

            $guest->update($updateData);

            return response()->json([
                'success' => true,
                'guest' => [
                    'id' => $guest->id,
                    'name' => $guest->name,
                    'slug' => $guest->slug,
                    'rsvp_status' => $guest->rsvp_status,
                    'guest_count' => $guest->guest_count,
                ],
            ]);
        }

        $name = sanitizeString($validated['name']);
        $slug = generateSlug($name);

        $guest = Guest::where('name', $name)->first();

        if ($guest) {
            $guest->update([
                'rsvp_status' => $validated['rsvpStatus'],
                'guest_count' => $guestCount,
                'phone_number' => $validated['phoneNumber'] ?? $guest->phone_number,
            ]);
        } else {
            $guest = Guest::create([
                'name' => $name,
                'phone_number' => $validated['phoneNumber'] ?? null,
                'slug' => $slug,
                'qr_code_string' => $this->generateUniqueQR(),
                'category' => 'Regular',
                'rsvp_status' => $validated['rsvpStatus'],
                'guest_count' => $guestCount,
            ]);
        }

        return response()->json([
            'success' => true,
            'isNew' => !$guest->wasRecentlyCreated ? false : true,
            'guest' => [
                'id' => $guest->id,
                'name' => $guest->name,
                'slug' => $guest->slug,
                'rsvp_status' => $guest->rsvp_status,
                'guest_count' => $guest->guest_count,
            ],
        ]);
    }

    private function generateUniqueQR(): string
    {
        do {
            $qr = generateQRString();
        } while (Guest::where('qr_code_string', $qr)->exists());

        return $qr;
    }
}
