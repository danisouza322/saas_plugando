<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidateCompanyData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $empresa = Auth::user()->empresa;
        
        $requiredFields = [
            'razao_social' => 'Razão Social',
            'cnpj' => 'CNPJ',
            'email' => 'E-mail',
            'telefone' => 'Telefone',
            'cep' => 'CEP',
            'endereco' => 'Endereço',
            'numero' => 'Número',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'estado' => 'Estado'
        ];

        $missingFields = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($empresa->$field)) {
                $missingFields[] = $label;
            }
        }

        if (!empty($missingFields)) {
            return redirect()
                ->route('painel.empresa.editar')
                ->with('error', 'Por favor, preencha os seguintes dados da empresa antes de continuar: ' . implode(', ', $missingFields));
        }

        return $next($request);
    }
}
