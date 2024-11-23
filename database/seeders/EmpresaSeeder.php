<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run()
    {
        // Criar empresa padrão
        Empresa::create([
            'nome' => 'Empresa Administradora',
            'razao_social' => 'Empresa Administradora LTDA',
            'cnpj' => '00000000000000',
            'email' => 'admin@admin.com',
            'telefone' => '0000000000',
            'cep' => '00000000',
            'endereco' => 'Endereço Padrão',
            'numero' => '0',
            'bairro' => 'Bairro Padrão',
            'cidade' => 'Cidade Padrão',
            'estado' => 'SP',
            'status' => true,
        ]);
    }
}
