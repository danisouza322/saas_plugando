<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Cliente;
use App\Models\AtividadeEconomica;
use App\Models\InscricaoEstadual;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\CnpjService;

class EditCliente extends Component
{
    use WithFileUploads;

    public $clienteId;

    public $razao_social;
    public $cnpj;
    public $inscricao_estadual;
    public $regime_tributario;
    public $porte;
    public $natureza_juridica;
    public $atividadesEconomicas;
    public $simples;
    public $mei;
    public $cep;
    public $rua;
    public $numero;
    public $bairro;
    public $complemento;
    public $estado;
    public $cidade;
    public $email;
    public $telefone;
    public $celular;
    public $atividades = [];
    public $inscricoesEstaduais = [];
    public $dadosCnpj;
    public $buscandoDados = false;

    protected $rules = [
        'razao_social' => 'required|string',
        'cnpj' => 'required|cnpj|unique:clientes,cnpj,{{clienteId}}', // Ignorando o cliente atual na verificação de unicidade
        'regime_tributario' => 'required',
        // Outras regras de validação...
    ];

    public function mount($clienteId)
    {
        $this->clienteId = $clienteId;

        $cliente = Cliente::with(['inscricoesEstaduais', 'endereco'])->findOrFail($clienteId);

        //dd($cliente);

        $this->razao_social = $cliente->razao_social;
        $this->cnpj = $cliente->cnpj;
        $this->inscricao_estadual = $cliente->inscricao_estadual;
        $this->regime_tributario = $cliente->regime_tributario;
        $this->porte = $cliente->porte;
        $this->natureza_juridica = $cliente->natureza_juridica;
        $this->atividadesEconomicas = $cliente->atividadesEconomicas;
        $this->simples = $cliente->simples;
        $this->mei = $cliente->mei;
        $this->cep = $cliente->endereco->cep;
        $this->rua = $cliente->endereco->rua;
        $this->numero = $cliente->endereco->numero;
        $this->bairro = $cliente->endereco->bairro;
        $this->complemento = $cliente->endereco->complemento;
        $this->cidade = $cliente->endereco->cidade;
        $this->estado = $cliente->endereco->estado;
       // $this->municipio_ibge = $cliente->endereco->municipio_ibge;
        $this->email = $cliente->email;
        $this->telefone = $cliente->telefone;
        $this->celular = $cliente->celular;

        // Carregar as inscrições estaduais
        $this->inscricoesEstaduais = $cliente->inscricoesEstaduais->map(function ($inscricao) {
            return [
                'estado' => $inscricao->estado,
                'numero' => $inscricao->numero,
                'ativa' => $inscricao->ativa,
                'data_status' => $inscricao->data_status ? Carbon::parse($inscricao->data_status)->format('Y-m-d') : null,
                'status_texto' => $inscricao->status_texto,
                'tipo_texto' => $inscricao->tipo_texto,
            ];
        })->toArray();
    }

    public function buscarDadosCNPJ(CnpjService $cnpjService)
    {
        $this->buscandoDados = true;

        $this->dadosCnpj = $cnpjService->buscarDadosCnpj($this->cnpj);

        if (isset($this->dadosCnpj['error'])) {
            session()->flash('error', $this->dadosCnpj['error']);
        } else {
            $this->razao_social = $this->dadosCnpj['company']['name'] ?? '';
            $this->porte = $this->dadosCnpj['company']['size']['text'] ?? '';
            $this->natureza_juridica = $this->dadosCnpj['company']['nature']['text'] ?? '';
            $this->simples =  $this->dadosCnpj['company']['simples']['optant'] ? "Optante desde - ". Carbon::parse($this->dadosCnpj['company']['simples']['since'])->format('d/m/Y')  : "Não Optante";
            $this->mei =  $this->dadosCnpj['company']['simei']['optant'] ? "Optante desde - ". Carbon::parse($this->dadosCnpj['company']['simei']['since'])->format('d/m/Y')  : "Não Optante";
            $this->cep =  $this->dadosCnpj['address']['zip'] ?? '';
            $this->rua =  $this->dadosCnpj['address']['street'] ?? '';
            $this->numero =  $this->dadosCnpj['address']['number'] ?? '';
            $this->bairro =  $this->dadosCnpj['address']['district'] ?? '';
            $this->complemento =  $this->dadosCnpj['address']['details'] ?? '';
            $this->estado =  $this->dadosCnpj['address']['state'] ?? '';
            $this->cidade =  $this->dadosCnpj['address']['city'] ?? '';

            // Atualizar o valor de $regime_tributario com base na resposta da API
            $simplesOptante = $this->dadosCnpj['company']['simples']['optant'] ?? false;
            $meiOptante = $this->dadosCnpj['company']['simei']['optant'] ?? false;

            if ($simplesOptante && !$meiOptante) {
                $this->regime_tributario = 'simples_nacional';
            } elseif ($simplesOptante && $meiOptante) {
                $this->regime_tributario = 'mei';
            } else {
                $this->regime_tributario = 'lucro_presumido';
            }

            // Atividades
            $this->atividades = [];

            // Atividade principal
            if (isset($this->dadosCnpj['mainActivity'])) {
                $this->atividades[] = [
                    'tipo' => 'Principal',
                    'codigo' => $this->dadosCnpj['mainActivity']['id'],
                    'descricao' => $this->dadosCnpj['mainActivity']['text'],
                ];
            }

            // Atividades secundárias
            if (isset($this->dadosCnpj['sideActivities']) && is_array($this->dadosCnpj['sideActivities'])) {
                foreach ($this->dadosCnpj['sideActivities'] as $atividadeSecundaria) {
                    $this->atividades[] = [
                        'tipo' => 'Secundária',
                        'codigo' => $atividadeSecundaria['id'],
                        'descricao' => $atividadeSecundaria['text'],
                    ];
                }
            }

            // Processar inscrições estaduais
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

    public function save()
    {
        // Validações
        $this->validate([
            // Suas regras de validação
            'razao_social' => 'required|string',
           // 'cnpj' => 'required|cnpj|unique:clientes,cnpj,' . $this->clienteId,
            'regime_tributario' => 'required',
            // Outras regras...
        ]);

        // Buscar o cliente existente
        $cliente = Cliente::findOrFail($this->clienteId);

        // Atualizar os dados do cliente
        $cliente->update([
            'razao_social' => $this->razao_social,
            'cnpj' => $this->cnpj,
            'inscricao_estadual' => $this->inscricao_estadual,
            'regime_tributario' => $this->regime_tributario,
            'porte' => $this->porte,
            'natureza_juridica' => $this->natureza_juridica,
            'simples' => $this->simples,
            'mei' => $this->mei,
            'cep' => $this->cep,
            'rua' => $this->rua,
            'numero' => $this->numero,
            'bairro' => $this->bairro,
            'complemento' => $this->complemento,
            'estado' => $this->estado,
            'cidade' => $this->cidade,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'celular' => $this->celular,
        ]);


        // Atualizar as inscrições estaduais
        // Excluir as inscrições atuais
        $cliente->inscricoesEstaduais()->delete();

        // Adicionar as novas inscrições
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

        // Exibir mensagem de sucesso
        session()->flash('message', 'Cliente atualizado com sucesso!');

        // Redirecionar ou executar outra ação
        return redirect()->route('painel.clientes.index');
    }

    public function render()
    {
        return view('livewire.cliente.edit-cliente')
        ->layout('layouts.app', [
            'titulo' => 'Editar Cliente', // Passando 'titulo' para o layout
        ]);
    }
}


