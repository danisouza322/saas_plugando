<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
use App\Models\User;
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

    public function cadastrar()
{
    $this->validate();

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

    // Autenticar o usuário
    auth()->login($user);

    session()->flash('message', 'Empresa e usuário cadastrados com sucesso!');
    return redirect()->route('index'); // Redirecionar para o dashboard após o login
}

    public function render()
    {
        return view('livewire.empresa.cadastro-empresa')
            ->layout('layouts.guest'); // Usando um layout para páginas de convidado
    }
}