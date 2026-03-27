<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Salon;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_salons' => Salon::count(),
            'active_salons' => Salon::active()->count(),
            'total_users' => User::count(),
            'total_appointments' => Appointment::count(),
        ];

        return view('super_admin.dashboard', compact('stats'));
    }
}
