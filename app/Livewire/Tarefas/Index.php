<?php

namespace App\Livewire\Tarefas;

use App\Models\Tarefa;
use App\Models\Cliente;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $statusFilter = '';
    public $tarefaId = null;

    protected $listeners = ['tarefaUpdated' => '$refresh'];

    public function toggleStatus($tarefaId)
    {
        try {
            $tarefa = Tarefa::where('empresa_id', Auth::user()->empresa_id)
                           ->findOrFail($tarefaId);
            
            $tarefa->status = $tarefa->status === 'concluido' ? 'novo' : 'concluido';
            $tarefa->save();

            $this->dispatch('showToast', [
                'message' => 'Status da tarefa atualizado com sucesso!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            logger()->error('Erro ao atualizar status da tarefa:', [
                'error' => $e->getMessage(),
                'tarefa_id' => $tarefaId
            ]);
            
            $this->dispatch('showToast', [
                'message' => 'Erro ao atualizar status da tarefa: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function createTarefa()
    {
        $this->dispatch('showModal');
    }

    public function editTarefa($tarefa_id)
    {
        $this->dispatch('showModal', ['tarefa_id' => $tarefa_id]);
    }

    public function confirmDelete($id)
    {
        $this->tarefaId = $id;
        logger()->info('confirmDelete chamado com ID:', ['id' => $id]);
        $this->dispatch('confirmDelete', ['id' => $id]);
    }

    #[On('deleteTarefa')]
    public function delete()
    {
        logger()->info('delete iniciado:', [
            'id' => $this->tarefaId,
            'tipo' => gettype($this->tarefaId)
        ]);

        try {
            if (!$this->tarefaId) {
                throw new \Exception('ID da tarefa não fornecido');
            }

            logger()->info('Buscando tarefa:', ['id' => $this->tarefaId, 'empresa_id' => Auth::user()->empresa_id]);
            
            $tarefa = Tarefa::where('empresa_id', Auth::user()->empresa_id)
                           ->findOrFail($this->tarefaId);
            
            logger()->info('Tarefa encontrada:', ['tarefa' => $tarefa->toArray()]);
            
            // Remove os arquivos do storage
            foreach ($tarefa->arquivos as $arquivo) {
                logger()->info('Verificando arquivo:', ['arquivo' => $arquivo->toArray()]);
                
                if (Storage::disk('public')->exists($arquivo->caminho_arquivo)) {
                    logger()->info('Deletando arquivo:', ['caminho' => $arquivo->caminho_arquivo]);
                    Storage::disk('public')->delete($arquivo->caminho_arquivo);
                }
            }
            
            $tarefa->delete();
            logger()->info('Tarefa deletada com sucesso');
            
            $this->dispatch('success', 'Tarefa excluída com sucesso!');
            $this->dispatch('refresh-list');
            $this->tarefaId = null;
        } catch (\Exception $e) {
            logger()->error('Erro ao excluir tarefa:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'id' => $this->tarefaId ?? 'não fornecido'
            ]);
            $this->dispatch('error', 'Erro ao excluir tarefa: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Tarefa::where('empresa_id', Auth::user()->empresa_id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titulo', 'like', '%' . $this->search . '%')
                  ->orWhere('descricao', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $tarefas = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.tarefas.index', [
            'tarefas' => $tarefas,
            'clientes' => Cliente::where('empresa_id', Auth::user()->empresa_id)->get(),
            'responsaveis' => User::where('empresa_id', Auth::user()->empresa_id)->get()
        ])->layout('layouts.app');
    }
}
