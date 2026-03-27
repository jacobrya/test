<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(Request $request, Appointment $appointment): View
    {
        if ($appointment->client_id !== $request->user()->id) {
            abort(403);
        }

        if ($appointment->status !== 'completed' || $appointment->review) {
            abort(404);
        }

        $appointment->load(['specialist.user', 'service']);

        return view('client.reviews.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        if ($appointment->client_id !== $request->user()->id) {
            abort(403);
        }

        if ($appointment->status !== 'completed' || $appointment->review) {
            abort(404);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'client_id' => $request->user()->id,
            'specialist_id' => $appointment->specialist_id,
            'appointment_id' => $appointment->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('client.appointments.index')
            ->with('success', 'Review submitted. Thank you!');
    }
}
