<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Papéis padrão do sistema
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Administrador do sistema com acesso total',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user',
                'display_name' => 'Usuário',
                'description' => 'Usuário padrão do sistema',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'empresa',
                'display_name' => 'Empresa',
                'description' => 'Empresa cadastrada no sistema',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
