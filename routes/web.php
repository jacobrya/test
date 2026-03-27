<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Client\AppointmentController as ClientAppointmentController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\SalonOwner\AppointmentController as SalonOwnerAppointmentController;
use App\Http\Controllers\SalonOwner\DashboardController as SalonOwnerDashboardController;
use App\Http\Controllers\SalonOwner\SalonController as SalonOwnerSalonController;
use App\Http\Controllers\SalonOwner\ServiceController as SalonOwnerServiceController;
use App\Http\Controllers\SalonOwner\SpecialistController as SalonOwnerSpecialistController;
use App\Http\Controllers\Specialist\AppointmentController as SpecialistAppointmentController;
use App\Http\Controllers\Specialist\DashboardController as SpecialistDashboardController;
use App\Http\Controllers\Specialist\ProfileController as SpecialistProfileController;
use App\Http\Controllers\Specialist\ReviewController as SpecialistReviewController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SalonController as SuperAdminSalonController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/salons', [SalonController::class, 'index'])->name('salons.index');
Route::get('/salons/{salon}', [SalonController::class, 'show'])->name('salons.show');
Route::get('/salons/{salon}/specialists/{specialist}', [SalonController::class, 'specialist'])->name('salons.specialist');
Route::get('/salons/{salon}/services/{service}', [SalonController::class, 'service'])->name('salons.service');

// Booking (client auth)
Route::middleware(['auth', 'client'])->group(function () {
    Route::get('/book/{salon}/{specialist}/{service}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/book/{salon}/{specialist}/{service}', [BookingController::class, 'store'])->name('booking.store');
});

// Client
Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [ClientAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/cancel', [ClientAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/{appointment}/review/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/appointments/{appointment}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// Specialist
Route::middleware(['auth', 'specialist'])->prefix('specialist')->name('specialist.')->group(function () {
    Route::get('/pending', fn () => view('specialist.pending'))->name('pending');
    Route::get('/dashboard', [SpecialistDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [SpecialistAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/complete', [SpecialistAppointmentController::class, 'complete'])->name('appointments.complete');
    Route::get('/profile', [SpecialistProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [SpecialistProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [SpecialistProfileController::class, 'update'])->name('profile.update');
    Route::get('/reviews', [SpecialistReviewController::class, 'index'])->name('reviews.index');
});

// Salon Owner
Route::middleware(['auth', 'salon_owner'])->prefix('salon-owner')->name('salon-owner.')->group(function () {
    Route::get('/dashboard', [SalonOwnerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/salon/create', [SalonOwnerSalonController::class, 'create'])->name('salon.create');
    Route::post('/salon', [SalonOwnerSalonController::class, 'store'])->name('salon.store');
    Route::get('/salon/edit', [SalonOwnerSalonController::class, 'edit'])->name('salon.edit');
    Route::put('/salon', [SalonOwnerSalonController::class, 'update'])->name('salon.update');

    Route::get('/services', [SalonOwnerServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [SalonOwnerServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [SalonOwnerServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [SalonOwnerServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [SalonOwnerServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [SalonOwnerServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/specialists', [SalonOwnerSpecialistController::class, 'index'])->name('specialists.index');
    Route::post('/specialists/invite', [SalonOwnerSpecialistController::class, 'invite'])->name('specialists.invite');
    Route::patch('/specialists/{specialist}/approve', [SalonOwnerSpecialistController::class, 'approve'])->name('specialists.approve');
    Route::patch('/specialists/{specialist}/reject', [SalonOwnerSpecialistController::class, 'reject'])->name('specialists.reject');
    Route::delete('/specialists/{specialist}/remove', [SalonOwnerSpecialistController::class, 'remove'])->name('specialists.remove');

    Route::get('/appointments', [SalonOwnerAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/cancel', [SalonOwnerAppointmentController::class, 'cancel'])->name('appointments.cancel');
});

// Super Admin
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/salons', [SuperAdminSalonController::class, 'index'])->name('salons.index');
    Route::patch('/salons/{salon}/approve', [SuperAdminSalonController::class, 'approve'])->name('salons.approve');
    Route::patch('/salons/{salon}/deactivate', [SuperAdminSalonController::class, 'deactivate'])->name('salons.deactivate');
    Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [SuperAdminUserController::class, 'updateRole'])->name('users.updateRole');
});

// Generic auth profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard redirect
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'super_admin' => redirect()->route('super-admin.dashboard'),
        'salon_owner' => redirect()->route('salon-owner.dashboard'),
        'specialist' => redirect()->route('specialist.dashboard'),
        default => redirect()->route('client.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
