<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Fetch logged-in user's role from the database
        $userRole = Auth::user()->role; // Assuming "role" column exists in "users" table

        // Allow Superadmin to access everything
        if ($userRole === 'superadmin') {
            return $next($request);
        }

        // Convert comma-separated roles into an array
        $rolesArray = explode(',', $roles);

        // Check if the user's role is allowed
        if (!in_array($userRole, $rolesArray)) {
            return back();
        }

        return $next($request);


}
}