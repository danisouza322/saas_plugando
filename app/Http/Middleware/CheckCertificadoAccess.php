<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Certificado;
use Illuminate\Support\Facades\Auth;

class CheckCertificadoAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Pega o ID do certificado da rota
        $certificadoId = $request->route('certificado');

        // Se não houver ID do certificado na rota, permite passar
        if (!$certificadoId) {
            return $next($request);
        }

        // Busca o certificado
        $certificado = Certificado::find($certificadoId);

        // Se o certificado não existir, retorna 404
        if (!$certificado) {
            abort(404, 'Certificado não encontrado.');
        }

        // Verifica se o certificado pertence à empresa do usuário logado
        if ($certificado->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Você não tem permissão para acessar este certificado.');
        }

        return $next($request);
    }
}
