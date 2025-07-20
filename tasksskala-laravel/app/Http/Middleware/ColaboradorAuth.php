<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ColaboradorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('colaborador_id')) {
            return redirect()->route('colaborador.login.form');
        }

        return $next($request);
    }
}