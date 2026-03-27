<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SalonController extends Controller
{
    public function index(): View
    {
        $salons = Salon::with('owner')->latest()->paginate(15);
        return view('super_admin.salons.index', compact('salons'));
    }

    public function approve(Salon $salon): RedirectResponse
    {
        $salon->update(['is_active' => true]);
        return back()->with('success', "Salon '{$salon->name}' approved.");
    }

    public function deactivate(Salon $salon): RedirectResponse
    {
        $salon->update(['is_active' => false]);
        return back()->with('success', "Salon '{$salon->name}' deactivated.");
    }
}
