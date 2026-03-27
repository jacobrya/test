<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $appointments = $request->user()->clientAppointments()
            ->with(['specialist.user', 'service', 'salon', 'review'])
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(15);

        return view('client.appointments.index', compact('appointments'));
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        if ($appointment->client_id !== $request->user()->id) {
            abort(403);
        }

        if (! in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointmentDateTime = Carbon::parse($appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->start_time);
        if ($appointmentDateTime->diffInHours(now(), false) > -2) {
            return back()->with('error', 'Appointments can only be cancelled at least 2 hours in advance.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}
