<?php

namespace App\Http\Livewire\Empresa;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DadosEmpresa extends Component
{
    public $empresa;
    public $razao_social;
    public $cnpj;
    public $email;
    public $telefone;
    public $cep;
    public $endereco;
    public $numero;
    public $complemento;
    public $bairro;
    public $cidade;
    public $estado;

    public function mount()
    {
        $this->empresa = Auth::user()->empresa;
        $this->razao_social = $this->empresa->razao_social;
        $this->cnpj = $this->empresa->cnpj;
        $this->email = $this->empresa->email;
        $this->telefone = $this->empresa->telefone;
        $this->cep = $this->empresa->cep;
        $this->endereco = $this->empresa->endereco;
        $this->numero = $this->empresa->numero;
        $this->complemento = $this->empresa->complemento;
        $this->bairro = $this->empresa->bairro;
        $this->cidade = $this->empresa->cidade;
        $this->estado = $this->empresa->estado;
    }

    public function salvar()
    {
        $this->validate([
            'razao_social' => 'required',
            'cnpj' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
        ], [
            'required' => 'O campo :attribute é obrigatório',
            'email' => 'O campo e-mail deve ser um endereço válido'
        ]);

        $this->empresa->update([
            'razao_social' => $this->razao_social,
            'cnpj' => $this->cnpj,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'cep' => $this->cep,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
        ]);

        session()->flash('success', 'Dados da empresa atualizados com sucesso!');
    }

    public function render()
    {
        return view('livewire.empresa.dados-empresa');
    }
}
