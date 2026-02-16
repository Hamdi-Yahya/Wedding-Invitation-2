<?php

namespace App\Http\Controllers;

use App\Models\EventSetting;
use App\Models\GalleryImage;
use App\Models\Guest;
use App\Models\ThemeSetting;
use App\Models\WeddingDetail;
use App\Models\Wish;

class InvitationController extends Controller
{
    public function index()
    {
        $event = EventSetting::first();
        $theme = ThemeSetting::first();
        $gallery = GalleryImage::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $details = WeddingDetail::first();
        $wishes = Wish::where('is_approved', true)
            ->with('guest')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invitation.index', compact('event', 'theme', 'gallery', 'details', 'wishes'));
    }

    public function personalInvite(string $slug)
    {
        $guest = Guest::where('slug', $slug)->firstOrFail();

        $event = EventSetting::first();
        $theme = ThemeSetting::first();
        $gallery = GalleryImage::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $details = WeddingDetail::first();
        $wishes = Wish::where('is_approved', true)
            ->with('guest')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invitation.personal', compact('guest', 'event', 'theme', 'gallery', 'details', 'wishes'));
    }
}
