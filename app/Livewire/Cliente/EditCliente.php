<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditCliente extends Component
{
    use AuthorizesRequests;

    public $cliente;

    protected $rules = [
        'cliente.razao_social' => 'required|string|max:255',
        'cliente.nome_fantasia' => 'required|string|max:255',
        //'cliente.cpf_cnpj' => 'required|string|max:18|unique:clientes,cpf_cnpj,' . $this->cliente->id,
        // Outras regras de validação...
    ];

    public function mount($id)
    {
        $this->cliente = Cliente::findOrFail($id);
        $this->authorize('update', $this->cliente);
    }

    public function submit()
    {
        $this->validate();

        $this->cliente->save();

        session()->flash('message', 'Cliente atualizado com sucesso.');

        return redirect()->route('clientes.index');
    }

    public function render()
    {
        return view('livewire.cliente.edit-cliente')
        ->layout('layouts.app');
    }
}

