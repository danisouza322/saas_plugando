<?php

namespace App\Livewire\Tarefas;

use App\Models\Tarefa;
use App\Models\Cliente;
use App\Models\User;
use App\Models\ArquivoTarefa;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TarefaModal extends Component
{
    use WithFileUploads;

    public $empresa_id;
    public $user_id;
    public $clientes;
    public $usuarios;
    public $tarefa = [];
    public $tarefa_id;
    public $responsaveis = [];
    public $arquivos = [];
    public $arquivos_existentes = [];

    protected $listeners = ['showModal'];

    protected $rules = [
        'tarefa.titulo' => 'required|min:3',
        'tarefa.cliente_id' => 'required|exists:clientes,id',
        'tarefa.descricao' => 'required',
        'tarefa.prioridade' => 'required|in:baixa,media,alta',
        'tarefa.data_vencimento' => 'required|date',
        'responsaveis' => 'required|array|min:1',
        'arquivos.*' => 'nullable|file|max:10240', // máximo 10MB
    ];

    protected $messages = [
        'tarefa.titulo.required' => 'O título é obrigatório',
        'tarefa.titulo.min' => 'O título deve ter no mínimo 3 caracteres',
        'tarefa.cliente_id.required' => 'O cliente é obrigatório',
        'tarefa.cliente_id.exists' => 'Cliente inválido',
        'tarefa.descricao.required' => 'A descrição é obrigatória',
        'tarefa.prioridade.required' => 'A prioridade é obrigatória',
        'tarefa.prioridade.in' => 'Prioridade inválida',
        'tarefa.data_vencimento.required' => 'A data de vencimento é obrigatória',
        'tarefa.data_vencimento.date' => 'Data de vencimento inválida',
        'responsaveis.required' => 'Selecione pelo menos um responsável',
        'responsaveis.min' => 'Selecione pelo menos um responsável',
        'arquivos.*.max' => 'O arquivo não pode ser maior que 10MB',
    ];

    public function mount($tarefa_id = null)
    {
        $this->empresa_id = Auth::user()->empresa_id;
        $this->user_id = Auth::id();
        
        $this->clientes = Cliente::where('empresa_id', $this->empresa_id)->get();
        $this->usuarios = User::where('empresa_id', $this->empresa_id)
                             ->where('id', '!=', $this->user_id)
                             ->get();

        if ($tarefa_id) {
            $this->tarefa_id = $tarefa_id;
            $this->loadTarefa();
        } else {
            $this->tarefa = [
                'empresa_id' => $this->empresa_id,
                'status' => 'novo'
            ];
        }
    }

    private function resetForm()
    {
        $this->tarefa_id = null;
        $this->tarefa = [
            'empresa_id' => $this->empresa_id,
            'cliente_id' => '',
            'titulo' => '',
            'descricao' => '',
            'prioridade' => 'baixa',
            'data_vencimento' => '',
            'status' => 'novo'
        ];
        $this->responsaveis = [];
        $this->arquivos = [];
        $this->arquivos_existentes = [];
        
        // Recarrega a lista de usuários excluindo o usuário atual
        $this->usuarios = User::where('empresa_id', $this->empresa_id)
                            ->where('id', '!=', $this->user_id)
                            ->get();
    }

    public function showModal($tarefa_id = null)
    {
        $this->resetForm();
        
        if (isset($tarefa_id['tarefa_id'])) {
            $tarefa_id = $tarefa_id['tarefa_id'];
        }
        
        if ($tarefa_id) {
            $tarefa = Tarefa::with('responsaveis', 'arquivos')->where('empresa_id', Auth::user()->empresa_id)
                           ->findOrFail($tarefa_id);
            
            $this->tarefa_id = $tarefa_id;
            $this->tarefa = [
                'empresa_id' => $tarefa->empresa_id,
                'cliente_id' => $tarefa->cliente_id,
                'titulo' => $tarefa->titulo,
                'descricao' => $tarefa->descricao,
                'prioridade' => $tarefa->prioridade,
                'data_vencimento' => $tarefa->data_vencimento->format('Y-m-d'),
                'criado_por' => $tarefa->criado_por
            ];
            $this->responsaveis = $tarefa->responsaveis->pluck('id')->toArray();
            $this->arquivos_existentes = $tarefa->arquivos;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            if ($this->tarefa_id) {
                $tarefa = Tarefa::where('empresa_id', $this->empresa_id)
                               ->findOrFail($this->tarefa_id);
                $tarefa->update($this->tarefa);
                $message = 'Tarefa atualizada com sucesso!';
            } else {
                // Garantir que a empresa_id e criado_por estão corretos
                $this->tarefa['empresa_id'] = $this->empresa_id;
                $this->tarefa['criado_por'] = $this->user_id;
                $this->tarefa['status'] = 'novo';
                
                $tarefa = Tarefa::create($this->tarefa);
                $message = 'Tarefa criada com sucesso!';

                // Adiciona o usuário logado como responsável
                $tarefa->responsaveis()->attach($this->user_id, [
                    'atribuido_por' => $this->user_id,
                    'data_atribuicao' => now()
                ]);
            }

            // Adiciona os outros responsáveis selecionados
            if (!empty($this->responsaveis)) {
                $responsaveisData = [];
                foreach ($this->responsaveis as $responsavel_id) {
                    $responsaveisData[$responsavel_id] = [
                        'atribuido_por' => $this->user_id,
                        'data_atribuicao' => now()
                    ];
                }
                
                if ($this->tarefa_id) {
                    $tarefa->responsaveis()->sync($responsaveisData);
                } else {
                    $tarefa->responsaveis()->attach($responsaveisData);
                }
            }

            // Upload de arquivos
            if (!empty($this->arquivos)) {
                foreach ($this->arquivos as $arquivo) {
                    if ($arquivo && $arquivo->isValid()) {
                        $path = $arquivo->store('tarefas/' . $tarefa->id, 'public');
                        
                        $tarefa->arquivos()->create([
                            'nome_arquivo' => $arquivo->getClientOriginalName(),
                            'caminho_arquivo' => $path,
                            'tipo_arquivo' => $arquivo->getClientOriginalExtension(),
                            'tamanho' => $arquivo->getSize(),
                            'uploaded_por' => $this->user_id
                        ]);
                    }
                }
            }

            DB::commit();
            
            $this->dispatch('success', $message);
            $this->dispatch('refresh-list');
            $this->dispatch('close-modal');
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Erro ao salvar tarefa:', [
                'error' => $e->getMessage(),
                'tarefa' => $this->tarefa,
                'responsaveis' => $this->responsaveis
            ]);
            $this->dispatch('error', 'Erro ao salvar tarefa: ' . $e->getMessage());
        }
    }

    public function deleteArquivo($arquivo_id)
    {
        try {
            $arquivo = ArquivoTarefa::where('tarefa_id', $this->tarefa_id)
                                  ->findOrFail($arquivo_id);

            // Remove o arquivo do storage
            if (Storage::disk('public')->exists($arquivo->caminho_arquivo)) {
                Storage::disk('public')->delete($arquivo->caminho_arquivo);
            }

            $arquivo->delete();

            // Atualiza a lista de arquivos
            $this->arquivos_existentes = ArquivoTarefa::where('tarefa_id', $this->tarefa_id)->get();

            $this->dispatch('showToast', [
                'message' => 'Arquivo excluído com sucesso!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'message' => 'Erro ao excluir arquivo: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tarefas.tarefa-modal');
    }
}
