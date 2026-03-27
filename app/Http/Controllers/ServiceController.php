<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::active()->latest()->paginate(12);

        return view('services.index', compact('services'));
    }

    public function show(Service $service): View
    {
        return view('services.show', compact('service'));
    }
}
