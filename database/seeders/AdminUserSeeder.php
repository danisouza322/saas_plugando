<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Obter a empresa padrão
        $empresa = Empresa::first();

        // Criar usuário admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
            'empresa_id' => $empresa->id
        ]);

        // Atribuir papel de admin
        DB::table('role_user')->insert([
            'user_id' => $admin->id,
            'role_id' => DB::table('roles')->where('name', 'admin')->first()->id
        ]);
    }
}
