<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $upcoming = $request->user()->clientAppointments()
            ->with(['specialist.user', 'service', 'salon'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        return view('client.dashboard', compact('upcoming'));
    }
}
