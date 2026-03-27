<?php

namespace App\Http\Controllers\SalonOwner;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
        'price' => ['required', 'numeric', 'min:0'],
    ];

    private function salon(Request $request)
    {
        $salon = $request->user()->salon;
        if (! $salon) {
            abort(404, 'Create a salon first.');
        }
        return $salon;
    }

    public function index(Request $request): View
    {
        $salon = $this->salon($request);
        $services = $salon->services()->latest()->paginate(15);
        return view('salon_owner.services.index', compact('services'));
    }

    public function create(Request $request): View
    {
        $this->salon($request);
        return view('salon_owner.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $salon = $this->salon($request);
        $validated = $request->validate($this->rules);
        $salon->services()->create($validated);

        return redirect()->route('salon-owner.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Request $request, Service $service): View
    {
        $salon = $this->salon($request);
        if ($service->salon_id !== $salon->id) {
            abort(403);
        }
        return view('salon_owner.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $salon = $this->salon($request);
        if ($service->salon_id !== $salon->id) {
            abort(403);
        }
        $validated = $request->validate($this->rules);
        $service->update($validated);

        return redirect()->route('salon-owner.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        $salon = $this->salon($request);
        if ($service->salon_id !== $salon->id) {
            abort(403);
        }
        $service->update(['is_active' => false]);

        return redirect()->route('salon-owner.services.index')
            ->with('success', 'Service deactivated.');
    }
}
