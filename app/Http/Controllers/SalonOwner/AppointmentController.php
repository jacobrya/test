<?php

namespace App\Http\Controllers\SalonOwner;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $salon = $request->user()->salon;

        $query = $salon
            ? $salon->appointments()->with(['client', 'specialist.user', 'service'])
            : Appointment::where('id', 0);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($date = $request->input('date')) {
            $query->where('appointment_date', $date);
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(15);

        $specialists = $salon ? $salon->specialists()->with('user')->get() : collect();

        return view('salon_owner.appointments.index', compact('appointments', 'specialists'));
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        $salon = $request->user()->salon;
        if (! $salon || $appointment->salon_id !== $salon->id) {
            abort(403);
        }

        if (! in_array($appointment->status, ['confirmed', 'pending'])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled.');
    }
}
