<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CnpjService
{
    public function buscarDadosCnpj($cnpj)
    {
        // Limpa o CNPJ, removendo pontos, traços e barras
        $cnpjLimpo = preg_replace('/[^0-9]/', '', $cnpj);

        // Verifica se o CNPJ possui 14 dígitos
        if (strlen($cnpjLimpo) !== 14) {
            return ['error' => 'CNPJ inválido'];
        }

        try {
            // Faz a requisição para a API pública
            
            $response = Http::withHeaders([
                'Authorization' => '4b80a913-6fe9-40aa-ba10-2e6d07cf1ca5-224e1411-8e20-4b23-b742-00f26bce5769',
            ])->get("https://api.cnpja.com/office/{$cnpjLimpo}?simples=true&registrations=BR");

            if ($response->successful()) {
                return $response->json();
            } else {
                return ['error' => 'Não foi possível obter os dados do CNPJ'];
            }
        } catch (\Exception $e) {
            // Trata exceções, como problemas de conexão
            return ['error' => 'Ocorreu um erro ao consultar o CNPJ'];
        }
    }
}
