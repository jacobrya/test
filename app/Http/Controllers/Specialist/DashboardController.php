<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $specialist = $request->user()->specialist;

        $todayAppointments = $specialist
            ? $specialist->appointments()
                ->with(['client', 'service'])
                ->where('appointment_date', now()->toDateString())
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('start_time')
                ->get()
            : collect();

        $weekClients = $specialist
            ? $specialist->appointments()
                ->where('appointment_date', '>=', now()->startOfWeek()->toDateString())
                ->where('appointment_date', '<=', now()->endOfWeek()->toDateString())
                ->whereNotIn('status', ['cancelled'])
                ->distinct('client_id')
                ->count('client_id')
            : 0;

        return view('specialist.dashboard', compact('todayAppointments', 'weekClients'));
    }
}
