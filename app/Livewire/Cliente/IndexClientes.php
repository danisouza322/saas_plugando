<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class IndexClientes extends Component
{
    public $clientes;

    public function mount()
    {
        $this->clientes = Cliente::where('empresa_id', Auth::user()->empresa_id)->get();
    }

    public function render()
    {
        return view('livewire.cliente.index-clientes')
        ->layout('layouts.app');
    }
}

