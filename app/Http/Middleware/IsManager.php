<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsManager
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $guards = empty($guards) ? [null] : $guards;

        if (auth()->user()->type == "supervisor" || auth()->user()->type == "super_admin" || auth()->user()->is_assist) {
            return $next($request);
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
