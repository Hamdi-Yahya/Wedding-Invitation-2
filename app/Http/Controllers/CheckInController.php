<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index()
    {
        return view('dashboard.scanner');
    }

    public function processCheckIn(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'validate') {
            return $this->validateQR($request);
        }

        if ($action === 'checkin') {
            return $this->confirmCheckIn($request);
        }

        return response()->json(['success' => false, 'message' => 'Action tidak valid.'], 400);
    }

    private function validateQR(Request $request)
    {
        $request->validate([
            'qrCodeString' => 'required|string',
        ]);

        $guest = Guest::where('qr_code_string', $request->input('qrCodeString'))->first();

        if (!$guest) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'guest' => [
                'id' => $guest->id,
                'name' => $guest->name,
                'category' => $guest->category,
                'rsvp_status' => $guest->rsvp_status,
                'guest_count' => $guest->guest_count,
                'check_in_time' => $guest->check_in_time,
            ],
        ]);
    }

    private function confirmCheckIn(Request $request)
    {
        $request->validate([
            'guestId' => 'required|integer',
            'giftType' => 'nullable|in:Fisik,Amplop,None',
        ]);

        $guest = Guest::findOrFail($request->input('guestId'));

        $updateData = [
            'check_in_time' => now(),
        ];

        if ($request->input('giftType')) {
            $updateData['gift_type'] = $request->input('giftType');
        }

        $guest->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'guest' => $guest,
        ]);
    }
}
