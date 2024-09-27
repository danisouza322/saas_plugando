<?php


namespace App\Livewire\Task;

use Livewire\Component;
use App\Models\TaskTemplate;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EditTaskTemplate extends Component
{
    public $templateId;
    public $titulo;
    public $descricao;
    public $user_id;
    public $cliente_id;
    public $frequencia;
    public $dia_do_mes;

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'user_id' => 'required|exists:users,id',
        'cliente_id' => 'required|exists:clientes,id',
        'frequencia' => 'required|in:monthly',
        'dia_do_mes' => 'required|integer|min:1|max:31',
    ];

    protected $listeners = [
        'openEditTaskTemplateModal' => 'openModal',
    ];

    public $isOpen = false;

    /**
     * Abre o modal de edição e carrega os dados do modelo de tarefa.
     *
     * @param int $templateId
     */
    public function openModal($templateId)
    {
        $this->resetInputFields();

        $template = TaskTemplate::findOrFail($templateId);

        // Autorizar a ação usando a política definida
        $this->authorize('update', $template);

        // Garantir que o modelo pertence à mesma empresa do usuário
        if ($template->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Ação não autorizada.');
        }

        // Carregar os dados do modelo
        $this->templateId = $template->id;
        $this->titulo = $template->titulo;
        $this->descricao = $template->descricao;
        $this->user_id = $template->user_id;
        $this->cliente_id = $template->cliente_id;
        $this->frequencia = $template->frequencia;
        $this->dia_do_mes = $template->dia_do_mes;

        $this->isOpen = true;
    }

    /**
     * Fecha o modal de edição.
     */
    public function closeModal()
    {
        $this->isOpen = false;
    }

    /**
     * Reseta os campos de entrada.
     */
    private function resetInputFields()
    {
        $this->templateId = '';
        $this->titulo = '';
        $this->descricao = '';
        $this->user_id = '';
        $this->cliente_id = '';
        $this->frequencia = 'monthly';
        $this->dia_do_mes = 1;
    }

    /**
     * Atualiza o modelo de tarefa com os dados fornecidos.
     */
    public function updateTaskTemplate()
    {
        $this->validate();

        $template = TaskTemplate::findOrFail($this->templateId);

        // Autorizar a ação usando a política definida
        $this->authorize('update', $template);

        // Garantir que o modelo pertence à mesma empresa do usuário
        if ($template->empresa_id !== Auth::user()->empresa_id) {
            abort(403, 'Ação não autorizada.');
        }

        // Atualizar os dados do modelo
        $template->update([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'user_id' => $this->user_id,
            'cliente_id' => $this->cliente_id,
            'frequencia' => $this->frequencia,
            'dia_do_mes' => $this->dia_do_mes,
        ]);

        session()->flash('message', 'Modelo de tarefa atualizado com sucesso.');

        $this->closeModal();
        $this->dispatch('taskTemplateUpdated'); // Atualiza a lista de modelos
    }

    /**
     * Renderiza a view do componente.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $clientes = Cliente::where('empresa_id', Auth::user()->empresa_id)->get();
        $users = User::where('empresa_id', Auth::user()->empresa_id)->get();

        return view('livewire.task.edit-task-template', [
            'clientes' => $clientes,
            'users' => $users,
        ])->layout('layouts.app', [
            'titulo' => 'Meu Perfil', // Passando 'titulo' para o layout
        ]);;
    }
}
