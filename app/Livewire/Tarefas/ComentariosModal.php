<?php

namespace App\Livewire\Tarefas;

use App\Models\Tarefa;
use App\Models\TarefaComentario;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ComentariosModal extends Component
{
    use WithFileUploads;

    public $tarefa = null;
    public $novoComentario = '';
    public $anexos = [];
    public $respondendoA = null;

    protected $listeners = ['openComentariosModal' => 'showModal'];

    protected $rules = [
        'novoComentario' => 'required|min:2',
        'anexos.*' => 'file|max:10240' // 10MB max
    ];

    public function mount($tarefa_id = null)
    {
        $this->resetForm();
        if ($tarefa_id) {
            $this->loadTarefa($tarefa_id);
        }
    }

    private function resetForm()
    {
        $this->tarefa = null;
        $this->novoComentario = '';
        $this->anexos = [];
        $this->respondendoA = null;
    }

    private function loadTarefa($tarefa_id)
    {
        try {
            $this->tarefa = Tarefa::with(['comentarios.user', 'comentarios.respostas.user'])
                ->where('empresa_id', Auth::user()->empresa_id)
                ->findOrFail($tarefa_id);
        } catch (\Exception $e) {
            logger()->error('Erro ao carregar tarefa:', [
                'error' => $e->getMessage(),
                'tarefa_id' => $tarefa_id
            ]);
            
            $this->dispatch('showToast', [
                'message' => 'Erro ao carregar tarefa: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function showModal($data = null)
    {
        logger()->info('showModal chamado em ComentariosModal:', ['data' => $data]);
        $this->resetForm();
        
        if (isset($data['tarefa_id'])) {
            logger()->info('Carregando tarefa:', ['tarefa_id' => $data['tarefa_id']]);
            $this->loadTarefa($data['tarefa_id']);
            logger()->info('Tarefa carregada');
        }
    }

    public function salvarComentario()
    {
        $this->validate();

        $anexosPaths = [];
        foreach ($this->anexos as $anexo) {
            $path = $anexo->store('comentarios/' . $this->tarefa->id, 'public');
            $anexosPaths[] = [
                'nome' => $anexo->getClientOriginalName(),
                'path' => $path
            ];
        }

        TarefaComentario::create([
            'tarefa_id' => $this->tarefa->id,
            'user_id' => Auth::id(),
            'conteudo' => $this->novoComentario,
            'anexos' => $anexosPaths,
            'parent_id' => $this->respondendoA
        ]);

        $this->novoComentario = '';
        $this->anexos = [];
        $this->respondendoA = null;
        $this->tarefa->refresh();
        
        $this->dispatch('success', 'Comentário adicionado com sucesso!');
    }

    public function responderA($comentarioId)
    {
        $this->respondendoA = $comentarioId;
    }

    public function cancelarResposta()
    {
        $this->respondendoA = null;
    }

    public function excluirComentario($id)
    {
        $comentario = TarefaComentario::find($id);
        if ($comentario && ($comentario->user_id === Auth::id() || Auth::user()->can('excluir_comentarios'))) {
            $comentario->delete();
            $this->tarefa->refresh();
            $this->dispatch('success', 'Comentário excluído com sucesso!');
        }
    }

    public function render()
    {
        return view('livewire.tarefas.comentarios-modal');
    }
}
