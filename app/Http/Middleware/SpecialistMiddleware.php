<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SpecialistMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isSpecialist()) {
            abort(403, 'Unauthorized.');
        }

        $specialist = $request->user()->specialist;

        if (! $specialist || ! $specialist->is_approved) {
            if ($request->routeIs('specialist.pending')) {
                return $next($request);
            }
            return redirect()->route('specialist.pending')
                ->with('warning', 'Your account is pending approval from salon owner.');
        }

        return $next($request);
    }
}
