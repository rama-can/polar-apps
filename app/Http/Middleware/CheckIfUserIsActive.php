<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isActived = Auth::user()->isActived;
        if (Auth::check() && !$isActived) {
            return redirect()->back()->with('warning', 'Your account is not active. Please contact the officer.');
        }

        return $next($request);
    }
}
