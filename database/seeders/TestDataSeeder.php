<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar empresa de teste
        $empresa = \App\Models\Empresa::create([
            'nome' => 'Empresa Teste',
            'cnpj' => '12345678901234',
            'email' => 'teste@empresa.com',
            'telefone' => '1234567890',
            'ativo' => true
        ]);

        // Criar usuário admin
        $admin = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@teste.com',
            'password' => bcrypt('password'),
            'empresa_id' => $empresa->id,
            'ativo' => true
        ]);

        // Criar alguns usuários
        for ($i = 1; $i <= 3; $i++) {
            \App\Models\User::create([
                'name' => "Usuário $i",
                'email' => "usuario$i@teste.com",
                'password' => bcrypt('password'),
                'empresa_id' => $empresa->id,
                'ativo' => true
            ]);
        }

        // Criar alguns clientes
        for ($i = 1; $i <= 5; $i++) {
            \App\Models\Cliente::create([
                'empresa_id' => $empresa->id,
                'razao_social' => "Cliente $i LTDA",
                'nome_fantasia' => "Cliente $i",
                'cpf_cnpj' => str_pad($i, 14, '0', STR_PAD_LEFT),
                'tipo_cliente' => 'PJ',
                'email' => "cliente$i@teste.com",
                'telefone' => "123456789$i",
                'regime_tributario' => 'Simples Nacional',
                'porte' => 'ME',
                'situacao_cadastral' => 'Ativa',
                'tipo' => 'PJ',
                'ativo' => true
            ]);
        }

        // Criar alguns tipos de tarefa
        $tipos = ['Desenvolvimento', 'Suporte', 'Reunião', 'Manutenção'];
        foreach ($tipos as $tipo) {
            \App\Models\TaskType::create([
                'name' => $tipo,
                'description' => "Tarefas de $tipo",
                'empresa_id' => $empresa->id,
                'ativo' => true
            ]);
        }
    }
}
