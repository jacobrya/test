<?php

namespace App\Http\Controllers\SalonOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $salon = $request->user()->salon;

        if (! $salon) {
            return view('salon_owner.dashboard', [
                'salon' => null,
                'stats' => null,
            ]);
        }

        $stats = [
            'specialists' => $salon->specialists()->count(),
            'services' => $salon->services()->active()->count(),
            'today_appointments' => $salon->appointments()->where('appointment_date', now()->toDateString())->count(),
            'pending_appointments' => $salon->appointments()->where('status', 'pending')->count(),
        ];

        return view('salon_owner.dashboard', compact('salon', 'stats'));
    }
}
