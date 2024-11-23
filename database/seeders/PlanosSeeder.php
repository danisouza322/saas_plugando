<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planos = [
            [
                'nome' => 'Básico',
                'slug' => 'basico',
                'descricao' => 'Ideal para pequenas empresas',
                'valor' => 49.90,
                'dias_teste' => 7,
                'recursos' => [
                    'Até 5 clientes',
                    'Emissão de certificados',
                    'Suporte por email'
                ],
                'ativo' => true
            ],
            [
                'nome' => 'Profissional',
                'slug' => 'profissional',
                'descricao' => 'Perfeito para empresas em crescimento',
                'valor' => 99.90,
                'dias_teste' => 7,
                'recursos' => [
                    'Até 15 clientes',
                    'Emissão de certificados',
                    'Suporte prioritário',
                    'Relatórios avançados'
                ],
                'ativo' => true
            ],
            [
                'nome' => 'Enterprise',
                'slug' => 'enterprise',
                'descricao' => 'Para empresas de grande porte',
                'valor' => 199.90,
                'dias_teste' => 7,
                'recursos' => [
                    'Clientes ilimitados',
                    'Emissão de certificados',
                    'Suporte 24/7',
                    'Relatórios personalizados',
                    'API de integração',
                    'Treinamento exclusivo'
                ],
                'ativo' => true
            ]
        ];

        foreach ($planos as $plano) {
            Plano::updateOrCreate(
                ['slug' => $plano['slug']],
                $plano
            );
        }
    }
}
