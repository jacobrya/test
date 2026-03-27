<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use App\Models\Service;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalonController extends Controller
{
    public function index(Request $request): View
    {
        $query = Salon::active()->with('owner');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $salons = $query->latest()->paginate(12);

        return view('salons.index', compact('salons'));
    }

    public function show(Salon $salon): View
    {
        $salon->load(['specialists.user', 'specialists.reviews', 'services']);

        return view('salons.show', compact('salon'));
    }

    public function specialist(Salon $salon, Specialist $specialist): View
    {
        $specialist->load(['user', 'services']);
        $reviews = $specialist->reviews()->with('client')->latest()->paginate(5);

        return view('salons.specialists.show', compact('salon', 'specialist', 'reviews'));
    }

    public function service(Salon $salon, Service $service): View
    {
        $service->load(['specialists.user', 'specialists.reviews']);

        return view('salons.services.show', compact('salon', 'service'));
    }
}
