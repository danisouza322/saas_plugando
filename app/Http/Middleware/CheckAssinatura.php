<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAssinatura
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if (!$user || !$user->empresa) {
            return redirect()->route('login');
        }

        $empresa = $user->empresa;

        // Se a assinatura estiver cancelada ou inadimplente
        if (in_array($empresa->status_assinatura, ['cancelada', 'inadimplente'])) {
            return redirect()->route('assinatura.pendente')->with('error', 'Sua assinatura precisa ser regularizada');
        }

        return $next($request);
    }
}
