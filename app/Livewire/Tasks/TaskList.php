<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\User;
use App\Models\Cliente;
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

    protected $listeners = [
        'refresh-tasks' => 'refreshTasks',
        'deleteTask' => 'deleteTask'
    ];

    public function getListeners()
    {
        return [
            'refresh-tasks' => 'refreshTasks',
        ];
    }

    public function mount()
    {
        // $this->loadTasks();
    }

    public function refreshTasks()
    {
        // Força a renderização do componente
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $empresaId = Session::get('empresa_id');
        $tasks = Task::with(['cliente', 'assignedUsers', 'type'])
            ->where('empresa_id', $empresaId)
            ->where('ativo', true)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
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
                $query->whereHas('assignedUsers', function($q) {
                    $q->where('users.id', $this->assignedTo);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.tasks.task-list', [
            'tasks' => $tasks,
            'taskTypes' => TaskType::where('empresa_id', session('empresa_id'))
                                 ->where('ativo', true)
                                 ->get(),
            'users' => User::where('empresa_id', session('empresa_id'))
                          ->where('ativo', true)
                          ->get(),
            'clientes' => Cliente::where('empresa_id', session('empresa_id'))
                                ->where('ativo', true)
                                ->get(),
            'totalTasks' => Task::forEmpresa(session('empresa_id'))->count(),
            'completedTasks' => Task::forEmpresa(session('empresa_id'))->completed()->count(),
            'pendingTasks' => Task::forEmpresa(session('empresa_id'))->pending()->count(),
            'inProgressTasks' => Task::forEmpresa(session('empresa_id'))->inProgress()->count(),
            'overdueTasks' => Task::forEmpresa(session('empresa_id'))->overdue()->count(),
        ])->extends('layouts.master')->section('content');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPriority()
    {
        $this->resetPage();
    }

    public function updatingTaskType()
    {
        $this->resetPage();
    }

    public function updatingAssignedTo()
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
            $task = Task::findOrFail($taskId);
            $task->delete();

            // Dispara notificação de sucesso
            $this->js('
                Toastify({
                    text: "Tarefa excluída com sucesso!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#4CAF50",
                    },
                }).showToast();
            ');

        } catch (\Exception $e) {
            logger()->error('Erro ao excluir tarefa', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);
            
            // Dispara notificação de erro
            $this->js('
                Toastify({
                    text: "Erro ao excluir tarefa: ' . addslashes($e->getMessage()) . '",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#F44336",
                    },
                }).showToast();
            ');
        }
    }
}
