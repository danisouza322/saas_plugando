<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetEmpresaIdInSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Session::has('empresa_id')) {
            $empresaId = Auth::user()->empresa_id;
            Session::put('empresa_id', $empresaId);
        }

        return $next($request);
    }
}
