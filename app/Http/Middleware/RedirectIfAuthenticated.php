<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                if (!$user instanceof User) {
                    Auth::guard($guard)->logout();
                    return $next($request);
                }
                
                // Check if user has valid role
                if (!$user->isAdmin() && !$user->isIntern() && !$user->isMentor()) {
                    Auth::guard($guard)->logout();
                    return $next($request);
                }
                
                // Redirect based on role
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->isMentor()) {
                    return redirect()->route('mentor.dashboard');
                } elseif ($user->isIntern()) {
                    return redirect()->route('intern.dashboard');
                }
            }
        }

        return $next($request);
    }
}
