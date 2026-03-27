<?php

namespace App\Http\Controllers\SalonOwner;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpecialistController extends Controller
{
    public function index(Request $request): View
    {
        $salon = $request->user()->salon;

        $pendingSpecialists = $salon
            ? $salon->specialists()->with('user')->where('is_approved', false)->get()
            : collect();

        $activeSpecialists = $salon
            ? $salon->specialists()->with('user')->where('is_approved', true)->get()
            : collect();

        return view('salon_owner.specialists.index', compact('pendingSpecialists', 'activeSpecialists'));
    }

    public function invite(Request $request): RedirectResponse
    {
        $salon = $request->user()->salon;
        if (! $salon) {
            return back()->with('error', 'Create a salon first.');
        }

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->where('role', 'specialist')->first();

        if (! $user) {
            return back()->with('error', 'No specialist found with that email.');
        }

        $specialist = $user->specialist;
        if (! $specialist) {
            $specialist = Specialist::create(['user_id' => $user->id]);
        }

        if ($specialist->salon_id) {
            return back()->with('error', 'This specialist is already assigned to a salon.');
        }

        $specialist->update(['salon_id' => $salon->id, 'is_approved' => false]);

        return back()->with('success', "Specialist {$user->name} added. Approve them to grant access.");
    }

    public function approve(Request $request, Specialist $specialist): RedirectResponse
    {
        $salon = $request->user()->salon;
        if (! $salon || $specialist->salon_id !== $salon->id) {
            abort(403);
        }

        $specialist->update(['is_approved' => true]);

        return back()->with('success', "Specialist {$specialist->user->name} approved.");
    }

    public function reject(Request $request, Specialist $specialist): RedirectResponse
    {
        $salon = $request->user()->salon;
        if (! $salon || $specialist->salon_id !== $salon->id) {
            abort(403);
        }

        $specialist->update(['salon_id' => null, 'is_approved' => false]);
        $specialist->services()->detach();

        return back()->with('success', 'Specialist rejected and removed from salon.');
    }

    public function remove(Request $request, Specialist $specialist): RedirectResponse
    {
        $salon = $request->user()->salon;
        if (! $salon || $specialist->salon_id !== $salon->id) {
            abort(403);
        }

        $specialist->update(['salon_id' => null, 'is_approved' => false]);
        $specialist->services()->detach();

        return back()->with('success', 'Specialist removed from your salon.');
    }
}
