<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $specialist = $request->user()->specialist;

        $query = $specialist
            ? $specialist->appointments()->with(['client', 'service', 'salon'])
            : Appointment::where('id', 0); // empty query if no specialist

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(15);

        return view('specialist.appointments.index', compact('appointments'));
    }

    public function complete(Request $request, Appointment $appointment): RedirectResponse
    {
        $specialist = $request->user()->specialist;

        if (! $specialist || $appointment->specialist_id !== $specialist->id) {
            abort(403);
        }

        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed appointments can be marked as completed.');
        }

        if ($appointment->appointment_date->isFuture() && ! $appointment->appointment_date->isToday()) {
            return back()->with('error', 'Cannot complete future appointments.');
        }

        $appointment->update(['status' => 'completed']);

        return back()->with('success', 'Appointment marked as completed.');
    }
}
