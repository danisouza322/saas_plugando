<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use App\Models\Cliente;
use App\Services\CnpjService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreateCliente extends Component
{
    public $razao_social;
    public $cnpj;
    public $nome_fantasia;
    public $regime_tributario;
    public $simples;
    public $mei;
    public $data_abertura;
    public $porte;
    public $capital_social;
    public $natureza_juridica;
    public $tipo;
    public $situacao_cadastral;

    // Campos de endereço
    public $cep;
    public $rua;
    public $complemento;
    public $numero;
    public $bairro;
    public $cidade;
    public $estado;
    public $municipio_ibge;

    public $dadosCnpj;

    public $buscandoDados = false;
   


    protected $rules = [
        'razao_social' => 'required|string|max:255',
        'cnpj' => 'required|string|max:18|unique:clientes,cnpj',
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
            'cnpj' => $this->cnpj,
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
            'cep' => $this->cep,
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

    public function buscarDadosCNPJ(CnpjService $cnpjService)
    {

        $this->dadosCnpj = $cnpjService->buscarDadosCnpj($this->cnpj);

       ($this->dadosCnpj);

        if (isset($this->dadosCnpj['error'])) {
            session()->flash('error', $this->dadosCnpj['error']);
        } else {

             $this->razao_social = $this->dadosCnpj['company']['name'] ?? '';
            // $this->razao_social = $this->dadosCnpj['company']['name'] ?? '';
             $this->porte = $this->dadosCnpj['company']['size']['text'] ?? '';
             $this->natureza_juridica = $this->dadosCnpj['company']['nature']['text'] ?? '';
             $this->simples =  $this->dadosCnpj['company']['simples']['optant'] ? "Optante desde - ". Carbon::createFromFormat('Y-m-d', $this->dadosCnpj['company']['simples']['since'])->format('d/m/Y')  : "Não Optante";
             $this->mei =  $this->dadosCnpj['company']['simei']['optant'] ? "Optante desde - ". Carbon::createFromFormat('Y-m-d', $this->dadosCnpj['company']['simei']['since'])->format('d/m/Y')  : "Não Optante";
             $this->cep =  $this->dadosCnpj['address']['zip'] ?? '';
             $this->rua =  $this->dadosCnpj['address']['street'] ?? '';
             $this->numero =  $this->dadosCnpj['address']['number'] ?? '';
             $this->bairro =  $this->dadosCnpj['address']['district'] ?? '';
             $this->complemento =  $this->dadosCnpj['address']['details'] ?? '';
             $this->estado =  $this->dadosCnpj['address']['state'] ?? '';
             $this->cidade =  $this->dadosCnpj['address']['city'] ?? '';
            //  $this->email =  $this->dadosCnpj['emails']['0']['address'] ?? '';
   
         $this->dispatch('showToast', 'Dados da empresa atualizados com sucesso!');
        }
        

        $this->buscandoDados = false;

    }

    public function render()
    {
        return view('livewire.cliente.create-cliente')
        ->layout('layouts.app');
    }
}

