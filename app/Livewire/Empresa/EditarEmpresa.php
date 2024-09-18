<?php

namespace App\Livewire\Empresa;

use Illuminate\Support\Facades\Http;
use Livewire\Component;


class EditarEmpresa extends Component
{
    public $empresa;
    public $nome;
    public $razao_social;
    public $cnpj;
    public $plano;
    public $email;

    protected $rules = [
        'nome' => 'required|min:3',
        'razao_social' => 'required|min:3',
        'cnpj' => 'required|digits:14',
        'plano' => 'required',
    ];

    public function mount()
    {
        $this->empresa = auth()->user()->empresa;
        $this->nome = $this->empresa->nome;
        $this->razao_social = $this->empresa->razao_social;
        $this->cnpj = $this->empresa->cnpj;
        $this->plano = $this->empresa->plano;
        $this->email = $this->empresa->email;
    }

    public function atualizar()
    {
        $this->validate();

        $this->empresa->update([
            'nome' => $this->nome,
            'razao_social' => $this->razao_social,
            'cnpj' => $this->cnpj,
            'plano' => $this->plano,
            'email' => $this->email,
        ]);

        //session()->flash('message', 'Empresa atualizada com sucesso!');

        $this->dispatch('showToast', 'Empresa atualizada com sucesso!');
    }

    public function buscarDadosCNPJ()
    {
       // $this->validate(['cnpj' => 'required|digits:14']);

        //$apiKey = config('0b76b57b-ef0f-4cf9-8e54-27d3ffc7249e-c8832ada-74ca-4360-8e0f-959d23d2631e');
        $response = Http::withHeaders([
            'Authorization' => '0b76b57b-ef0f-4cf9-8e54-27d3ffc7249e-c8832ada-74ca-4360-8e0f-959d23d2631e',
        ])->get("https://api.cnpja.com/office/{$this->cnpj}");

        if ($response->successful()) {
            $dados = $response->json();

            $this->nome = $dados['alias'] ?? $dados['company']['name'] ?? '';
            $this->razao_social = $dados['company']['name'] ?? '';
            $this->email = $dados['email'] ?? '';
            
            $this->dispatch('showToast', 'Dados da empresa atualizados com sucesso!');
        } else {
            $this->dispatch('showToast', 'Erro ao buscar dados da empresa. Verifique o CNPJ e tente novamente.', 'error');
        }
    }

    public function render()
    {
        return view('livewire.empresa.editar-empresa')
                ->layout('layouts.app'); // Usando um layout para páginas de convidado
    }
}