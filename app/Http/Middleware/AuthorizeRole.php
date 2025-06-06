<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizeRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (! $user || ! $user->role || ! in_array(strtolower($user->role->name), array_map('strtolower', $roles))) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
