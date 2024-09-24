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
                'Authorization' => '0b76b57b-ef0f-4cf9-8e54-27d3ffc7249e-c8832ada-74ca-4360-8e0f-959d23d2631e',
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
