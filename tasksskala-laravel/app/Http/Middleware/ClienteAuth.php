<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('cliente')->check()) {
            return redirect('/cliente/login');
        }

        return $next($request);
    }
}