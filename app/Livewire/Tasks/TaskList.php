<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TaskList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $priority = '';
    public $taskType = '';
    public $assignedTo = '';
    public $dueDate = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'priority' => ['except' => ''],
        'taskType' => ['except' => ''],
        'assignedTo' => ['except' => ''],
        'dueDate' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = ['refresh-tasks' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function markAsCompleted($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->markAsCompleted();
            $task->logHistory(Auth::id(), 'status', 'pending', 'completed');
        }
    }

    public function viewTask($taskId)
    {
        try {
            logger()->info('Visualizando tarefa', ['task_id' => $taskId]);
            $this->dispatch('show-task-modal', [
                'taskId' => $taskId,
                'mode' => 'view'
            ]);
        } catch (\Exception $e) {
            logger()->error('Erro ao visualizar tarefa', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erro ao visualizar tarefa: ' . $e->getMessage()
            ]);
        }
    }

    public function editTask($taskId) 
    {
        try {
            logger()->info('Editando tarefa', ['task_id' => $taskId]);
            $this->dispatch('show-task-modal', [
                'taskId' => $taskId,
                'mode' => 'edit'
            ]);
        } catch (\Exception $e) {
            logger()->error('Erro ao editar tarefa', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erro ao editar tarefa: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteTask($taskId)
    {
        try {
            $task = Task::where('empresa_id', Session::get('empresa_id'))
                       ->findOrFail($taskId);
            
            // Log deletion
            TaskHistory::logChange($task, 'delete');
            
            $task->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Tarefa excluída com sucesso!'
            ]);

            $this->dispatch('refresh-tasks');

        } catch (\Exception $e) {
            logger()->error('Erro ao excluir tarefa', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erro ao excluir tarefa: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $tasks = Task::with(['cliente', 'assignedTo', 'type'])
                    ->where('empresa_id', Session::get('empresa_id'))
                    ->when($this->search, function($query) {
                        $query->where('title', 'like', '%' . $this->search . '%')
                              ->orWhere('description', 'like', '%' . $this->search . '%');
                    })
                    ->when($this->status, function($query) {
                        $query->where('status', $this->status);
                    })
                    ->when($this->priority, function($query) {
                        $query->where('priority', $this->priority);
                    })
                    ->when($this->taskType, function($query) {
                        $query->where('task_type_id', $this->taskType);
                    })
                    ->when($this->assignedTo, function($query) {
                        $query->where('assigned_to', $this->assignedTo);
                    })
                    ->when($this->dueDate, function($query) {
                        switch ($this->dueDate) {
                            case 'today':
                                $query->whereDate('due_date', today());
                                break;
                            case 'week':
                                $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
                                break;
                            case 'month':
                                $query->whereBetween('due_date', [now()->startOfMonth(), now()->endOfMonth()]);
                                break;
                            case 'overdue':
                                $query->where('due_date', '<', now())
                                      ->whereNotIn('status', ['completed', 'cancelled']);
                                break;
                        }
                    })
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage);

        return view('livewire.tasks.task-list', [
            'tasks' => $tasks,
            'taskTypes' => TaskType::where('empresa_id', session('empresa_id'))
                                 ->where('active', true)
                                 ->get(),
            'users' => User::where('empresa_id', session('empresa_id'))
                          ->where('ativo', true)
                          ->get(),
            'totalTasks' => Task::forEmpresa(session('empresa_id'))->count(),
            'completedTasks' => Task::forEmpresa(session('empresa_id'))->completed()->count(),
            'pendingTasks' => Task::forEmpresa(session('empresa_id'))->pending()->count(),
            'inProgressTasks' => Task::forEmpresa(session('empresa_id'))->inProgress()->count(),
            'overdueTasks' => Task::forEmpresa(session('empresa_id'))->overdue()->count(),
        ])->extends('layouts.master')->section('content');
    }
}
