<?php

namespace App\Livewire\Empresa;

use App\Models\Empresa;
use App\Models\Role;
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
    public $telefone;
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
        'plano' => 'nullable', // Tornando o plano opcional
        'email' => 'required|email',
        'telefone' => 'required|min:14|max:15', // (99) 99999-9999 ou (99) 9999-9999
        'cep' => 'required|min:9', // 99999-999
        'endereco' => 'required',
        'numero' => 'required',
        'bairro' => 'required',
        'cidade' => 'required',
        'estado' => 'required|size:2',
    ];

    protected $messages = [
        'nome.required' => 'O nome é obrigatório',
        'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
        'razao_social.required' => 'A razão social é obrigatória',
        'razao_social.min' => 'A razão social deve ter no mínimo 3 caracteres',
        'cnpj.required' => 'O CNPJ é obrigatório',
        'plano.required' => 'O plano é obrigatório',
        'email.required' => 'O e-mail é obrigatório',
        'email.email' => 'Digite um e-mail válido',
        'telefone.required' => 'O telefone é obrigatório',
        'telefone.min' => 'Digite um telefone válido',
        'telefone.max' => 'Digite um telefone válido',
        'cep.required' => 'O CEP é obrigatório',
        'cep.min' => 'Digite um CEP válido',
        'endereco.required' => 'O endereço é obrigatório',
        'numero.required' => 'O número é obrigatório',
        'bairro.required' => 'O bairro é obrigatório',
        'cidade.required' => 'A cidade é obrigatória',
        'estado.required' => 'O estado é obrigatório',
        'estado.size' => 'O estado deve ter 2 caracteres',
    ];

    public function mount()
    {
        $user = Auth::user();

        if ($user->empresa) {
            $this->empresa = $user->empresa;
            $this->authorize('update', $this->empresa);

            $this->nome = $this->empresa->nome;
            $this->razao_social = $this->empresa->razao_social;
            $this->cnpj = $this->formatCnpj($this->empresa->cnpj);
            $this->plano = $this->empresa->plano;
            $this->email = $this->empresa->email;
            $this->telefone = $this->formatTelefone($this->empresa->telefone);
            $this->cep = $this->formatCep($this->empresa->cep);
            $this->cidade = $this->empresa->cidade;
            $this->bairro = $this->empresa->bairro;
            $this->numero = $this->empresa->numero;
            $this->complemento = $this->empresa->complemento;
            $this->estado = $this->empresa->estado;
            $this->endereco = $this->empresa->endereco;
        } else {
            session()->flash('error', 'Nenhuma empresa associada ao usuário.');
            return redirect()->route('dashboard');
        }
    }

    public function atualizar()
    {
        try {
            $this->authorize('update', $this->empresa);

            $this->validate();

            $isNewEmpresa = !$this->empresa;

            if (!$this->empresa) {
                $this->empresa = new Empresa();
            }

            $this->empresa->nome = $this->nome;
            $this->empresa->razao_social = $this->razao_social;
            $this->empresa->cnpj = preg_replace('/[^0-9]/', '', $this->cnpj);
            $this->empresa->plano = $this->plano ?? 'trial'; // Define plano padrão como 'trial' se nenhum for selecionado
            $this->empresa->email = $this->email;
            $this->empresa->telefone = preg_replace('/[^0-9]/', '', $this->telefone);
            $this->empresa->cep = preg_replace('/[^0-9]/', '', $this->cep);
            $this->empresa->cidade = $this->cidade;
            $this->empresa->bairro = $this->bairro;
            $this->empresa->numero = $this->numero;
            $this->empresa->complemento = $this->complemento;
            $this->empresa->estado = $this->estado;
            $this->empresa->endereco = $this->endereco;
            
            if (!$this->empresa->save()) {
                throw new \Exception('Erro ao salvar a empresa.');
            }

            $user = auth()->user();
            $user->empresa_id = $this->empresa->id;
            $user->save();

            if ($isNewEmpresa) {
                // Se é uma nova empresa, atribui o role de super-admin ao usuário
                $superAdminRole = Role::where('name', 'super-admin')->first();
                if ($superAdminRole) {
                    $user->roles()->sync([$superAdminRole->id]);
                }
            }

            $this->dispatch('showToast', 'Empresa atualizada com sucesso!');

        } catch (\Exception $e) {
            $this->dispatch('showToast', 'Erro ao atualizar empresa: ' . $e->getMessage());
        }
    }

    public function buscarDadosCNPJ(CnpjService $cnpjService)
    {
        $this->dadosCnpj = $cnpjService->buscarDadosCnpj($this->cnpj);

        if (isset($this->dadosCnpj['error'])) {
            session()->flash('error', $this->dadosCnpj['error']);
        } else {
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
            $this->telefone = $this->formatTelefone($this->dadosCnpj['phones']['0']['number'] ?? '');

            $this->dispatch('showToast', 'Dados da empresa atualizados com sucesso!');
        }
        
        $this->buscandoDados = false;
    }

    public function render()
    {
        return view('livewire.empresa.editar-empresa')
            ->layout('layouts.app', [
                'titulo' => 'Editar Empresa',
            ]);
    }

    private function formatCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }

    private function formatTelefone($telefone)
    {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        $length = strlen($telefone);

        if ($length === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } elseif ($length === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }

        return $telefone;
    }

    private function formatCep($cep)
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
    }
}