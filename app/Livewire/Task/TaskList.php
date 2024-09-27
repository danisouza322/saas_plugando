<?php

namespace App\Livewire\Task;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
class TaskList extends Component
{
    public $tasks;
    public $statusFilter = 'all'; // Opções: 'all', 'pending', 'completed'


    protected $listeners = [
        'taskUpdated' => 'loadTasks',
        'taskCreated' => 'loadTasks',
        'taskDeleted' => 'loadTasks',
    ];

    public function mount()
    {
        $this->loadTasks();
    }
    

    public function loadTasks()
    {
        $query = Task::with('users', 'cliente')
                     ->where('empresa_id', Auth::user()->empresa_id)
                     ->whereHas('users', function ($q) {
                         $q->where('users.id', Auth::id());
                     });

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $this->tasks = $query->orderBy('data_de_vencimento', 'asc')->get();
    }

    public function updatedStatusFilter()
    {
        $this->loadTasks();
    }

    public function deleteTask($taskId)
    {
        $task = Task::findOrFail($taskId);

        // Autorizar a ação
        $this->authorize('delete', $task);

        $task->delete();

        session()->flash('message', 'Tarefa deletada com sucesso.');

        $this->loadTasks();
    }

    public function markAsCompleted($taskId)
    {
        $task = Task::findOrFail($taskId);

        // Autorizar a ação
        $this->authorize('update', $task);

        $task->status = 'completed';
        $task->save();

        // Emitir evento para exibir notificação
        $this->dispatch('showToast', 'Tarefa marcada como concluída.');

        $this->loadTasks();
    }

    public function render()
    {
        return view('livewire.task.task-list')
        ->layout('layouts.app', [
            'titulo' => 'Lista de Tar', // Passando 'titulo' para o layout
        ]);
    }
}

