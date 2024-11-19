<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTypeSeeder extends Seeder
{
    public function run(): void
    {
        $empresaId = DB::table('empresas')->first()->id;

        $types = [
            [
                'name' => 'Envio de DCTF',
                'description' => 'Tarefas relacionadas ao envio de DCTF',
                'color' => '#e74c3c', // Vermelho
                'requires_approval' => true,
                'active' => true,
                'empresa_id' => $empresaId
            ],
            [
                'name' => 'Parcelamentos',
                'description' => 'Gestão e acompanhamento de parcelamentos',
                'color' => '#3498db', // Azul
                'requires_approval' => true,
                'active' => true,
                'empresa_id' => $empresaId
            ],
            [
                'name' => 'Guias',
                'description' => 'Emissão e controle de guias',
                'color' => '#2ecc71', // Verde
                'requires_approval' => false,
                'active' => true,
                'empresa_id' => $empresaId
            ],
            [
                'name' => 'Certidões',
                'description' => 'Emissão e acompanhamento de certidões',
                'color' => '#f1c40f', // Amarelo
                'requires_approval' => false,
                'active' => true,
                'empresa_id' => $empresaId
            ],
            [
                'name' => 'Processos',
                'description' => 'Acompanhamento de processos',
                'color' => '#9b59b6', // Roxo
                'requires_approval' => true,
                'active' => true,
                'empresa_id' => $empresaId
            ]
        ];

        foreach ($types as $type) {
            TaskType::create($type);
        }
    }
}
