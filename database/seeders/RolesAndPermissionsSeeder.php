<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Criar ou obter roles existentes
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'display_name' => 'Super Administrador',
                'description' => 'Usuário com acesso total ao sistema'
            ]
        );

        $user = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'Usuário',
                'description' => 'Usuário com acesso limitado'
            ]
        );

        // Criar ou obter permissões básicas
        $permissions = [
            'view-dashboard' => 'Visualizar Dashboard',
            'manage-users' => 'Gerenciar Usuários',
            'manage-clients' => 'Gerenciar Clientes',
            'manage-certificates' => 'Gerenciar Certificados',
        ];

        foreach ($permissions as $name => $display_name) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'display_name' => $display_name,
                    'description' => $display_name
                ]
            );
        }

        // Atribuir todas as permissões ao super-admin
        $allPermissions = Permission::all()->pluck('id')->toArray();
        $superAdmin->permissions()->sync($allPermissions);

        // Atribuir permissões básicas ao usuário normal
        $basicPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'manage-clients',
            'manage-certificates'
        ])->pluck('id')->toArray();
        $user->permissions()->sync($basicPermissions);

        // Atribuir roles aos usuários existentes
        $users = User::all();
        foreach ($users as $existingUser) {
            if ($existingUser->id === 1) {
                // Super admin
                $existingUser->roles()->sync([$superAdmin->id]);
            } else {
                // Usuário normal
                $existingUser->roles()->sync([$user->id]);
            }
        }
    }
}
