<?php

namespace App\Livewire\Task;

// app/Http/Livewire/EditTask.php

use Livewire\Component;
use App\Models\Task;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EditTask extends Component
{
    public $taskId;
    public $titulo;
    public $descricao;
    public $tipo;
    public $data_de_vencimento;
    public $cliente_id;
    public $status;
    public $user_ids = []; // Para múltiplos usuários

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'tipo' => 'required|in:monthly,ad-hoc',
        'data_de_vencimento' => 'required|date',
        'cliente_id' => 'required|exists:clientes,id',
        'status' => 'required|in:pending,completed',
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'exists:users,id',
    ];

    protected $listeners = [
        'openEditTaskModal' => 'openModal',
    ];

    public $isOpen = false;

    public function openModal($taskId)
    {
        $this->resetInputFields();

        $task = Task::findOrFail($taskId);

        // Autorizar a ação
        $this->authorize('update', $task);

        // Garantir que a tarefa pertence à empresa do usuário
        if ($task->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $this->taskId = $task->id;
        $this->titulo = $task->titulo;
        $this->descricao = $task->descricao;
        $this->tipo = $task->tipo;
        $this->data_de_vencimento = Carbon::parse($task->data_de_vencimento)->format('Y-m-d');
        $this->cliente_id = $task->cliente_id;
        $this->status = $task->status;
        $this->user_ids = $task->users->pluck('id')->toArray(); // Obter IDs dos usuários atribuídos

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->taskId = '';
        $this->titulo = '';
        $this->descricao = '';
        $this->tipo = 'ad-hoc';
        $this->data_de_vencimento = '';
        $this->cliente_id = '';
        $this->status = 'pending';
        $this->user_ids = [];
    }

    public function updateTask()
    {
        $this->validate();

        $task = Task::findOrFail($this->taskId);

        // Autorizar a ação
        $this->authorize('update', $task);

        // Garantir que a tarefa pertence à empresa do usuário
        if ($task->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $task->update([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'tipo' => $this->tipo,
            'data_de_vencimento' => Carbon::parse($this->data_de_vencimento),
            'cliente_id' => $this->cliente_id,
            'status' => $this->status,
        ]);

        // Atualizar a atribuição de usuários
        $task->users()->sync($this->user_ids);

        // Notificar novos usuários atribuídos
        foreach ($this->user_ids as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->notify(new \App\Notifications\TaskAssignedNotification($task));
            }
        }

        session()->flash('message', 'Tarefa atualizada com sucesso.');

        $this->closeModal();
        $this->emit('taskUpdated'); // Atualiza a lista de tarefas
    }

    public function render()
    {
        $clientes = Cliente::where('empresa_id', Auth::user()->empresa_id)->get(); // Filtrar por empresa
        $users = User::where('empresa_id', Auth::user()->empresa_id)
                     ->where('id', '!=', Auth::id()) // Excluir o usuário atual, se necessário
                     ->get(); // Filtrar usuários pela mesma empresa

        return view('livewire.task.edit-task', [
            'clientes' => $clientes,
            'users' => $users,
        ])->layout('layouts.app', [
            'titulo' => 'Editar Cliente', // Passando 'titulo' para o layout
        ]);;
    }
}

