<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Check if the user has the "admin" role
            if (Auth::user()->role === 'admin')
            {
                return $next($request);
            }
                // User doesn't have the "admin" role, show a toast message or perform another action
                // return response()->json(['error' => 'Unauthorized. You do not have the required role.'], 403);
                return redirect()->back();

        }

        // User is not authenticated, redirect to login page
    }
}
