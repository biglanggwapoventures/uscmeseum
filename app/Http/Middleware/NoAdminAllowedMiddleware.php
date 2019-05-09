<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;

class NoAdminAllowedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isRole(User::ROLE_ADMIN)) {
            return redirect('/');
        }

        return $next($request);
    }
}
