<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the 'user' role
        if ($request->user()) {
            if ($request->user()->role === 'user') {
                return $next($request);
            } elseif ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        return redirect()->route('login');
    }
}
