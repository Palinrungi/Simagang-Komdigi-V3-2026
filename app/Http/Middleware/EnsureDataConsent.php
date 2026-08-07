<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDataConsent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->hasRole('intern') && $user->intern) {
            if (!$user->intern->has_agreed_data_consent) {
                if (!$request->routeIs('intern.consent*')) {
                    return redirect()->route('intern.consent');
                }
            }
        }

        return $next($request);
    }
}
