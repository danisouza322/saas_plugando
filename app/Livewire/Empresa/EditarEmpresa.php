<?php

namespace App\Livewire\Empresa;

use Livewire\Component;
use App\Models\Empresa;

class EditarEmpresa extends Component
{
    public $empresa;
    public $nome;
    public $razao_social;
    public $cnpj;
    public $plano;

    protected $rules = [
        'nome' => 'required|min:3',
        'razao_social' => 'nullable|min:3',
        'cnpj' => 'nullable|digits:14',
        'plano' => 'required',
    ];

    public function mount()
    {
        $this->empresa = auth()->user()->empresa;
        $this->nome = $this->empresa->nome;
        $this->razao_social = $this->empresa->razao_social;
        $this->cnpj = $this->empresa->cnpj;
        $this->plano = $this->empresa->plano;
    }

    public function atualizar()
    {
        $this->validate();

        $this->empresa->update([
            'nome' => $this->nome,
            'razao_social' => $this->razao_social,
            'cnpj' => $this->cnpj,
            'plano' => $this->plano,
        ]);

        session()->flash('message', 'Empresa atualizada com sucesso!');
    }

    public function render()
    {
        return view('livewire.empresa.editar-empresa')
                ->layout('layouts.app'); // Usando um layout para páginas de convidado
    }
}