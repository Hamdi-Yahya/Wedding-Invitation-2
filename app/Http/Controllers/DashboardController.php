<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wish;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGuests = Guest::count();
        $totalComing = Guest::where('rsvp_status', 'Coming')->count();
        $totalNotComing = Guest::where('rsvp_status', 'Not Coming')->count();
        $totalPending = Guest::where('rsvp_status', 'Pending')->count();
        $totalCheckedIn = Guest::whereNotNull('check_in_time')->count();
        $pendingWishes = Wish::where('is_approved', false)->count();

        $recentCheckIns = Guest::whereNotNull('check_in_time')
            ->orderBy('check_in_time', 'desc')
            ->take(5)
            ->get(['name', 'category', 'check_in_time']);

        return view('dashboard.index', compact(
            'totalGuests',
            'totalComing',
            'totalNotComing',
            'totalPending',
            'totalCheckedIn',
            'pendingWishes',
            'recentCheckIns'
        ));
    }
}
