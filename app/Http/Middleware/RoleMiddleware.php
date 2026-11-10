<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('status', 'Please sign in to access this area.');
        }

        $user = Auth::user();

        if (! in_array($user->role, $roles)) {
            // Silently redirect to the user's primary dashboard without displaying restriction warnings
            $redirectRoute = $user->isOfficer() ? 'marketing-plan.index' : 'forecast.index';
            return redirect()->route($redirectRoute);
        }

        return $next($request);
    }
}
