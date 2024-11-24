<?php

namespace App\Http\Livewire\Tarefas;

use App\Models\Tarefa;
use App\Models\Cliente;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    // Propriedades do formulário
    public $titulo;
    public $cliente_id;
    public $descricao;
    public $status = 'novo';
    public $prioridade = 'media';
    public $data_vencimento;
    public $responsaveis = [];
    public $arquivos = [];

    // Propriedades de filtro e busca
    public $search = '';
    public $statusFilter = '';
    public $prioridadeFilter = '';
    public $clienteFilter = '';
    public $orderBy = 'data_vencimento';
    public $orderAsc = true;

    // Propriedades para exclusão de tarefa
    public $tarefaToDelete = null;

    // Regras de validação
    protected $rules = [
        'titulo' => 'required|min:3',
        'cliente_id' => 'required|exists:clientes,id',
        'descricao' => 'nullable',
        'status' => 'required|in:novo,em_andamento,pendente,concluido',
        'prioridade' => 'required|in:baixa,media,alta',
        'data_vencimento' => 'required|date|after:today',
        'responsaveis' => 'required|array|min:1',
        'arquivos.*' => 'nullable|file|max:10240' // máximo 10MB por arquivo
    ];

    public function mount()
    {
        $this->orderBy = 'data_vencimento';
        $this->statusFilter = '';
    }

    public function render()
    {
        $tarefas = Tarefa::query()
            ->where('empresa_id', Auth::user()->empresa_id)
            ->when($this->search, function ($query) {
                $query->where('titulo', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->prioridadeFilter, function ($query) {
                $query->where('prioridade', $this->prioridadeFilter);
            })
            ->when($this->clienteFilter, function ($query) {
                $query->where('cliente_id', $this->clienteFilter);
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->with(['cliente', 'responsaveis', 'arquivos'])
            ->paginate(10);

        $clientes = Cliente::where('empresa_id', Auth::user()->empresa_id)
            ->where('ativo', true)
            ->get();

        $usuarios = User::where('empresa_id', Auth::user()->empresa_id)
            ->where('ativo', true)
            ->get();

        return view('livewire.tarefas.index', [
            'tarefas' => $tarefas,
            'clientes' => $clientes,
            'usuarios' => $usuarios,
        ]);
    }

    public function create()
    {
        $this->validate();

        $tarefa = Tarefa::create([
            'empresa_id' => Auth::user()->empresa_id,
            'cliente_id' => $this->cliente_id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'status' => $this->status,
            'prioridade' => $this->prioridade,
            'data_vencimento' => $this->data_vencimento,
            'criado_por' => Auth::id(),
        ]);

        // Adiciona os responsáveis
        foreach ($this->responsaveis as $userId) {
            $tarefa->responsaveis()->attach($userId, [
                'atribuido_por' => Auth::id(),
                'data_atribuicao' => now(),
            ]);
        }

        // Upload dos arquivos
        foreach ($this->arquivos as $arquivo) {
            $path = $arquivo->store('tarefas/' . $tarefa->id, 'public');
            
            $tarefa->arquivos()->create([
                'nome_arquivo' => $arquivo->getClientOriginalName(),
                'caminho_arquivo' => $path,
                'tipo_arquivo' => $arquivo->getClientOriginalExtension(),
                'tamanho' => $arquivo->getSize(),
                'uploaded_por' => Auth::id(),
            ]);
        }

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Tarefa criada com sucesso!'
        ]);
    }

    public function updateTaskOrder($taskId, $newStatus)
    {
        $tarefa = Tarefa::find($taskId);
        if ($tarefa && $tarefa->empresa_id === Auth::user()->empresa_id) {
            $tarefa->update(['status' => $newStatus]);
        }
    }

    public function confirmDelete($id)
    {
        $this->tarefaToDelete = $id;
        $this->dispatch('showDeleteConfirmation');
    }

    public function deleteTarefa()
    {
        try {
            $tarefa = Tarefa::where('empresa_id', Auth::user()->empresa_id)
                           ->findOrFail($this->tarefaToDelete);
            
            // Remove os arquivos do storage
            foreach ($tarefa->arquivos as $arquivo) {
                if (Storage::disk('public')->exists($arquivo->caminho)) {
                    Storage::disk('public')->delete($arquivo->caminho);
                }
            }
            
            $tarefa->delete();
            
            $this->dispatch('showToast', 'Tarefa excluída com sucesso!');
            $this->tarefaToDelete = null;
        } catch (\Exception $e) {
            $this->dispatch('showToast', 'Erro ao excluir tarefa: ' . $e->getMessage());
        }
    }

    public function cancelDelete()
    {
        $this->tarefaToDelete = null;
    }
}
