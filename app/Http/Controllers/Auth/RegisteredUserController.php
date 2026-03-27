<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:client,specialist,salon_owner'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'specialist') {
            Specialist::create(['user_id' => $user->id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect($this->redirectByRole($user));
    }

    protected function redirectByRole(User $user): string
    {
        return match ($user->role) {
            'super_admin' => route('super-admin.dashboard', absolute: false),
            'salon_owner' => route('salon-owner.dashboard', absolute: false),
            'specialist' => route('specialist.dashboard', absolute: false),
            default => route('client.dashboard', absolute: false),
        };
    }
}
