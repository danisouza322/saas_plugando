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
    public $atividadesEconomicas;
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
    public $atividades = [];

    public $inscricoesEstaduais = [];


    public $buscandoDados = false;
   


    protected $rules = [
        'razao_social' => 'required|string|max:255',
        'cnpj' => 'required|string|max:18|unique:clientes,cnpj',
        'regime_tributario' => 'nullable',
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
            'atividadesEconomicas' => $this->atividadesEconomicas,
            'data_abertura' => $this->data_abertura,
            'porte' => $this->porte,
            'capital_social' => $this->capital_social,
            'natureza_juridica' => $this->natureza_juridica,
            'tipo' => $this->tipo,
            'situacao_cadastral' => $this->situacao_cadastral,
            'mei' => $this->mei,
            'simples' => $this->simples,
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

    // Salvar as inscrições estaduais
    foreach ($this->inscricoesEstaduais as $inscricao) {
        $cliente->inscricoesEstaduais()->create([
            'estado' => $inscricao['estado'],
            'numero' => $inscricao['numero'],
            'ativa' => $inscricao['ativa'],
            'data_status' => $inscricao['data_status'],
            'status_texto' => $inscricao['status_texto'],
            'tipo_texto' => $inscricao['tipo_texto'],
        ]);
    }

        session()->flash('message', 'Cliente cadastrado com sucesso!');

        return redirect()->route('painel.clientes.index');
    }

    public function buscarDadosCNPJ(CnpjService $cnpjService)
    {

        $this->dadosCnpj = $cnpjService->buscarDadosCnpj($this->cnpj);

        //dd($this->dadosCnpj);

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


            // Atualizar o valor de $regime_tributario com base na resposta da API
            $simples = $this->dadosCnpj['company']['simples']['optant'];
            $mei = $this->dadosCnpj['company']['simei']['optant']; 

            if ($simples && !$mei) {
                $this->regime_tributario = 'simples_nacional';
            } elseif ($simples && $mei) {
                $this->regime_tributario = 'mei';
            } else {
                $this->regime_tributario = 'lucro_presumido';
            }

           // Concatenar as atividades econômicas em uma string
        $atividades = [];

        // Atividade principal
        if (isset($this->dadosCnpj['mainActivity'])) {
            $atividades[] = 'Principal: ' . $this->dadosCnpj['mainActivity']['id'] . ' - ' . $this->dadosCnpj['mainActivity']['text'];
        }

        // Atividades secundárias
        if (isset($this->dadosCnpj['sideActivities']) && is_array($this->dadosCnpj['sideActivities'])) {
            foreach ($this->dadosCnpj['sideActivities'] as $atividadeSecundaria) {
                $atividades[] = 'Secundária: ' . $atividadeSecundaria['id'] . ' - ' . $atividadeSecundaria['text'];
            }
        }

        // Armazenar as atividades econômicas como uma string separada por quebras de linha
        $this->atividadesEconomicas = implode("\n", $atividades); 

         // **Processar inscrições estaduais**
         $this->inscricoesEstaduais = [];

         if (isset($this->dadosCnpj['registrations']) && is_array($this->dadosCnpj['registrations'])) {
             foreach ($this->dadosCnpj['registrations'] as $registration) {
                 $this->inscricoesEstaduais[] = [
                     'estado' => $registration['state'] ?? '',
                     'numero' => $registration['number'] ?? '',
                     'ativa' => $registration['enabled'] ? 'Ativa' : 'Inativo',
                     'data_status' => $registration['statusDate'] ?? '',
                     'status_texto' => $registration['status']['text'] ?? '',
                     'tipo_texto' => $registration['type']['text'] ?? '',
                 ];
             }
         }

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

