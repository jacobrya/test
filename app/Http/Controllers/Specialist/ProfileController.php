<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private function resolveSpecialist(Request $request): Specialist
    {
        return $request->user()->specialist
            ?? Specialist::create(['user_id' => $request->user()->id]);
    }

    public function show(Request $request): View
    {
        $specialist = $this->resolveSpecialist($request);
        $specialist->load(['services', 'salon', 'reviews.client']);

        return view('specialist.profile.show', compact('specialist'));
    }

    public function edit(Request $request): View
    {
        $specialist = $this->resolveSpecialist($request);
        $specialist->load('services');

        $salonServices = $specialist->salon
            ? $specialist->salon->services()->active()->orderBy('name')->get()
            : collect();

        return view('specialist.profile.edit', compact('specialist', 'salonServices'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bio' => ['nullable', 'string'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'services' => ['nullable', 'array'],
            'services.*' => ['exists:services,id'],
        ]);

        $specialist = $this->resolveSpecialist($request);

        if ($request->hasFile('photo')) {
            if ($specialist->photo) {
                Storage::disk('public')->delete($specialist->photo);
            }
            $validated['photo'] = $request->file('photo')->store('specialists', 'public');
        }

        $specialist->update([
            'bio' => $validated['bio'],
            'experience_years' => $validated['experience_years'],
            'photo' => $validated['photo'] ?? $specialist->photo,
        ]);

        if (isset($validated['services'])) {
            $specialist->services()->sync($validated['services']);
        } else {
            $specialist->services()->detach();
        }

        return redirect()->route('specialist.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
