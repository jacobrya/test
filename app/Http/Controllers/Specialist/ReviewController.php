<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $specialist = $request->user()->specialist;

        $reviews = $specialist
            ? $specialist->reviews()->with(['client', 'appointment.service'])->latest()->paginate(15)
            : collect();

        return view('specialist.reviews.index', compact('reviews', 'specialist'));
    }
}
