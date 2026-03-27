<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function show(Request $request, Salon $salon, Specialist $specialist, Service $service): View
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $availableSlots = $this->getAvailableSlots($specialist, $service, $date);

        return view('booking.show', compact('salon', 'specialist', 'service', 'date', 'availableSlots'));
    }

    public function store(Request $request, Salon $salon, Specialist $specialist, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(30)->format('Y-m-d')],
            'time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $startTime = Carbon::parse($validated['time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        $endTimeStr = $endTime->format('H:i:s');
        $startTimeStr = $startTime->format('H:i:s');

        $overlap = Appointment::where('specialist_id', $specialist->id)
            ->whereDate('appointment_date', $validated['date'])
            ->whereNotIn('status', ['cancelled'])
            ->whereRaw("start_time < ? AND end_time > ?", [$endTimeStr, $startTimeStr])
            ->exists();

        if ($overlap) {
            return back()->withErrors(['time' => 'This time slot is no longer available.'])->withInput();
        }

        Appointment::create([
            'client_id' => $request->user()->id,
            'specialist_id' => $specialist->id,
            'service_id' => $service->id,
            'salon_id' => $salon->id,
            'appointment_date' => $validated['date'],
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'confirmed',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('client.appointments.index')
            ->with('success', 'Appointment booked successfully!');
    }

    private function getAvailableSlots(Specialist $specialist, Service $service, string $date): array
    {
        $booked = Appointment::where('specialist_id', $specialist->id)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get(['start_time', 'end_time']);

        $slots = [];
        $start = Carbon::parse('09:00');
        $end = Carbon::parse('19:00');
        $duration = $service->duration_minutes;

        while ($start->copy()->addMinutes($duration)->lte($end)) {
            $slotEnd = $start->copy()->addMinutes($duration);
            $isAvailable = true;

            foreach ($booked as $booking) {
                $bookedStart = Carbon::parse($booking->start_time);
                $bookedEnd = Carbon::parse($booking->end_time);

                if ($start->lt($bookedEnd) && $slotEnd->gt($bookedStart)) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = $start->format('H:i');
            }

            $start->addMinutes(30);
        }

        return $slots;
    }
}
