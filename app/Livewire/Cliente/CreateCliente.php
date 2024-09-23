<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class CreateCliente extends Component
{
    public $razao_social;
    public $cpf_cnpj;

    public $nome_fantasia;
    public $regime_tributario;
    public $data_abertura;
    public $porte;
    public $capital_social;
    public $natureza_juridica;
    public $tipo;
    public $situacao_cadastral;

    // Campos de endereço
    public $rua;
    public $numero;
    public $bairro;
    public $cidade;
    public $estado;
    public $municipio_ibge;

    protected $rules = [
        'razao_social' => 'required|string|max:255',
        'cpf_cnpj' => 'required|string|max:18|unique:clientes,cpf_cnpj',
        'nome_fantasia' => 'required|string|max:255',
        'regime_tributario' => 'nullable|integer',
        'data_abertura' => 'nullable|date',
        'porte' => 'nullable|string|max:50',
        'capital_social' => 'nullable|numeric',
        'natureza_juridica' => 'nullable|string|max:50',
        'tipo' => 'nullable|string|max:20',
        'situacao_cadastral' => 'nullable|string|max:50',

        // Validações de endereço
        'rua' => 'nullable|string|max:255',
        'numero' => 'nullable|string|max:15',
        'bairro' => 'nullable|string|max:255',
        'cidade' => 'nullable|string|max:255',
        'estado' => 'nullable|string|max:4',
        'municipio_ibge' => 'nullable|integer',
    ];

    public function submit()
    {
        $this->validate();

        $cliente = Cliente::create([
            'empresa_id' => Auth::user()->empresa_id,
            'razao_social' => $this->razao_social,
            'cpf_cnpj' => $this->cpf_cnpj,
            'nome_fantasia' => $this->nome_fantasia,
            'regime_tributario' => $this->regime_tributario,
            'data_abertura' => $this->data_abertura,
            'porte' => $this->porte,
            'capital_social' => $this->capital_social,
            'natureza_juridica' => $this->natureza_juridica,
            'tipo' => $this->tipo,
            'situacao_cadastral' => $this->situacao_cadastral,
        ]);

        // Salvar endereço
        $cliente->endereco()->create([
            'rua' => $this->rua,
            'numero' => $this->numero,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'municipio_ibge' => $this->municipio_ibge,
        ]);

        session()->flash('message', 'Cliente cadastrado com sucesso!');

        return redirect()->route('painel.clientes.index');
    }

    public function render()
    {
        return view('livewire.cliente.create-cliente')
        ->layout('layouts.app');
    }
}

