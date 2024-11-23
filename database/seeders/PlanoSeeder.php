<?php

namespace Database\Seeders;

use App\Models\Plano;
use Illuminate\Database\Seeder;

class PlanoSeeder extends Seeder
{
    public function run()
    {
        $planos = [
            [
                'nome' => 'Básico',
                'slug' => 'basico',
                'descricao' => 'Plano ideal para começar',
                'valor' => 49.90,
                'dias_teste' => 7,
                'recursos' => [
                    'Até 5 usuários',
                    'Certificados digitais',
                    'Suporte por email'
                ],
                'ativo' => true
            ],
            [
                'nome' => 'Profissional',
                'slug' => 'profissional',
                'descricao' => 'Para empresas em crescimento',
                'valor' => 99.90,
                'dias_teste' => 7,
                'recursos' => [
                    'Até 15 usuários',
                    'Certificados digitais',
                    'Suporte prioritário',
                    'API de integração',
                    'Relatórios avançados'
                ],
                'ativo' => true
            ],
            [
                'nome' => 'Enterprise',
                'slug' => 'enterprise',
                'descricao' => 'Para grandes empresas',
                'valor' => 199.90,
                'dias_teste' => 7,
                'recursos' => [
                    'Usuários ilimitados',
                    'Certificados digitais',
                    'Suporte 24/7',
                    'API de integração',
                    'Relatórios personalizados',
                    'Ambiente dedicado',
                    'Treinamento da equipe'
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
