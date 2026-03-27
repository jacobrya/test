<?php

namespace App\Http\Controllers\SalonOwner;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SalonController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->salon) {
            return redirect()->route('salon-owner.salon.edit');
        }
        return view('salon_owner.salon.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->salon) {
            return redirect()->route('salon-owner.salon.edit');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Salon::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('salons', 'public');
        }

        Salon::create([
            'owner_id' => $request->user()->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'photo' => $photoPath,
            'is_active' => false,
        ]);

        return redirect()->route('salon-owner.dashboard')
            ->with('success', 'Salon created! It will be visible after admin approval.');
    }

    public function edit(Request $request): View|RedirectResponse
    {
        $salon = $request->user()->salon;
        if (! $salon) {
            return redirect()->route('salon-owner.salon.create');
        }
        return view('salon_owner.salon.edit', compact('salon'));
    }

    public function update(Request $request): RedirectResponse
    {
        $salon = $request->user()->salon;
        if (! $salon) {
            return redirect()->route('salon-owner.salon.create');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if ($salon->photo) {
                Storage::disk('public')->delete($salon->photo);
            }
            $validated['photo'] = $request->file('photo')->store('salons', 'public');
        }

        $salon->update($validated);

        return redirect()->route('salon-owner.salon.edit')
            ->with('success', 'Salon updated successfully.');
    }
}
