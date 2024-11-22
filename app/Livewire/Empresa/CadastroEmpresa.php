<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CadastroEmpresa extends Component
{
    public $nome_empresa;
    public $nome;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'nome_empresa' => 'required|min:3|unique:empresas,nome',
        'nome' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function cadastrar()
    {
        $this->validate();

        try {
            // Criar a empresa
            $empresa = Empresa::create([
                'nome' => $this->nome_empresa,
                'data_inicio_plano' => now(),
                'plano' => 'basico', // Definindo um plano padrão
            ]);

            // Criar o usuário vinculado à empresa
            $user = User::create([
                'name' => $this->nome,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'empresa_id' => $empresa->id,
            ]);

            // Buscar a role super-admin
            $superAdminRole = Role::where('name', 'super-admin')->first();
            
            // Se a role não existir, criar
            if (!$superAdminRole) {
                $superAdminRole = Role::create([
                    'name' => 'super-admin',
                    'display_name' => 'Super Administrador',
                    'description' => 'Acesso total ao sistema'
                ]);
            }

            // Atribuir a role super-admin ao usuário
            $user->roles()->attach($superAdminRole->id);

            // Autenticar o usuário
            auth()->login($user);

            session()->flash('message', 'Empresa e usuário cadastrados com sucesso!');
            
            // Redirecionar para o painel principal
            return redirect()->route('painel.dashboard');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao cadastrar empresa: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.empresa.cadastro-empresa');
    }
}