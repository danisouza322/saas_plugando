<?php

namespace App\Livewire\Tarefas;

use App\Models\TarefaComentario;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class TarefaComentarios extends Component
{
    use WithFileUploads;

    public $tarefa;
    public $novoComentario = '';
    public $anexos = [];
    public $respondendoA = null;

    protected $rules = [
        'novoComentario' => 'required|min:2',
        'anexos.*' => 'file|max:10240' // 10MB max
    ];

    public function mount($tarefa)
    {
        $this->tarefa = $tarefa;
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

        $this->reset(['novoComentario', 'anexos', 'respondendoA']);
        $this->dispatch('comentarioAdicionado');
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
            $this->dispatch('comentarioExcluido');
        }
    }

    public function render()
    {
        return view('livewire.tarefas.comentarios', [
            'comentarios' => $this->tarefa->comentarios
        ]);
    }
}
