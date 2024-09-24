<?php

namespace App\Livewire\Empresa;

use App\Services\CnpjService;
use Illuminate\Support\Facades\Auth;
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
    public $cep;
    public $endereco;
    public $numero;
    public $bairro;
    public $complemento;
    public $estado;
    public $cidade;
    public $dadosCnpj;

    public $buscandoDados = false;


    protected $rules = [
        'nome' => 'required|min:3',
        'razao_social' => 'required|min:3',
        'cnpj' => 'required',
        'plano' => 'required',
    ];

    public function mount()
    {

        $user = Auth::user();

        if ($user->empresa) {
            $this->empresa = $user->empresa;
            $this->authorize('update', $this->empresa);
        } else {
            session()->flash('error', 'Nenhuma empresa associada ao usuário.');
            return redirect()->route('dashboard');
        }

        $this->empresa = auth()->user()->empresa;
        $this->nome = $this->empresa->nome;
        $this->razao_social = $this->empresa->razao_social;
        $this->cnpj = $this->formatCnpj($this->empresa->cnpj);
        $this->plano = $this->empresa->plano;
        $this->email = $this->empresa->email;
        $this->cep = $this->empresa->cep;
        $this->cidade = $this->empresa->cidade;
        $this->bairro = $this->empresa->bairro;
        $this->numero = $this->empresa->numero;
        $this->complemento = $this->empresa->complemento;
        $this->estado = $this->empresa->estado;
        $this->endereco = $this->empresa->endereco;
    }

    public function atualizar()
    {

        $this->authorize('update', $this->empresa);

        $this->validate();

        $this->empresa->update([
            'nome' => $this->nome,
            'razao_social' => $this->razao_social,
            'cnpj' => preg_replace('/[^0-9]/', '', $this->cnpj),
            'plano' => $this->plano,
            'email' => $this->email,
            'cep' => $this->cep,
            'cidade' => $this->cidade,
            'bairro' => $this->bairro,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'estado' => $this->estado,
            'endereco' => $this->endereco,
        ]);

        //session()->flash('message', 'Empresa atualizada com sucesso!');

        $this->dispatch('showToast', 'Empresa atualizada com sucesso!');
    }

    public function buscarDadosCNPJ(CnpjService $cnpjService)
    {

        $this->dadosCnpj = $cnpjService->buscarDadosCnpj($this->cnpj);

        if (isset($this->dadosCnpj['error'])) {
            session()->flash('error', $this->dadosCnpj['error']);
        } else {
             // dd($this->dadosCnpj);

             $this->nome =  $this->dadosCnpj['alias'] ?? $this->dadosCnpj['company']['name'] ?? '';
             $this->razao_social = $this->dadosCnpj['company']['name'] ?? '';
             $this->email =  $this->dadosCnpj['email'] ?? '';
             $this->cep =  $this->dadosCnpj['address']['zip'] ?? '';
             $this->endereco =  $this->dadosCnpj['address']['street'] ?? '';
             $this->numero =  $this->dadosCnpj['address']['number'] ?? '';
             $this->bairro =  $this->dadosCnpj['address']['district'] ?? '';
             $this->complemento =  $this->dadosCnpj['address']['details'] ?? '';
             $this->estado =  $this->dadosCnpj['address']['state'] ?? '';
             $this->cidade =  $this->dadosCnpj['address']['city'] ?? '';
             $this->email =  $this->dadosCnpj['emails']['0']['address'] ?? '';
   
         $this->dispatch('showToast', 'Dados da empresa atualizados com sucesso!');
        }
        

        $this->buscandoDados = false;

    }

    public function render()
    {
        return view('livewire.empresa.editar-empresa')
                ->layout('layouts.app'); // Usando um layout para páginas de convidado
    }

    private function formatCnpj($cnpj)
{
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
}
}