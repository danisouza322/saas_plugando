<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class CheckClienteAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Pega o ID do cliente da rota
        $clienteId = $request->route('cliente');

        // Se não houver ID do cliente na rota, permite passar
        if (!$clienteId) {
            return $next($request);
        }

        // Busca o cliente
        $cliente = Cliente::find($clienteId);

        // Se o cliente não existir, retorna 404
        if (!$cliente) {
            abort(404, 'Cliente não encontrado.');
        }

        // Verifica se o cliente pertence à empresa do usuário logado
        if ($cliente->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Você não tem permissão para acessar este cliente.');
        }

        return $next($request);
    }
}
