<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\TaskHistory;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class TaskView extends Component
{
    use WithFileUploads;

    public $task;
    public $taskId;
    public $title;
    public $description;
    public $taskType;
    public $assignedTo;
    public $cliente;
    public $startDate;
    public $dueDate;
    public $priority;
    public $status;
    public $estimatedMinutes;
    public $budget;
    public $location;
    public $tags = [];
    public $requiresApproval = false;
    public $isRecurring = false;
    public $recurrencePattern;
    public $recurrenceConfig;

    public $clientes = [];
    public $users = [];
    public $taskTypes = [];

    public function mount($taskId = null)
    {
        try {
            logger()->info('TaskView mount iniciado', [
                'taskId' => $taskId,
                'empresa_id' => Session::get('empresa_id')
            ]);

            $this->taskId = $taskId;
            $this->loadData();

            if ($taskId) {
                $this->loadTask();
                logger()->info('Task carregada no mount', [
                    'task_id' => $this->taskId,
                    'cliente_id' => $this->cliente,
                    'cliente_type' => gettype($this->cliente)
                ]);
            }

        } catch (\Exception $e) {
            logger()->error('Erro no mount do TaskView', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Erro ao carregar componente: ' . $e->getMessage());
        }
    }

    protected function loadData()
    {
        try {
            $empresa_id = Session::get('empresa_id');
            
            logger()->info('Iniciando loadData', ['empresa_id' => $empresa_id]);

            $this->taskTypes = TaskType::where('empresa_id', $empresa_id)
                                     ->where('active', true)
                                     ->get()
                                     ->toArray();

            $this->users = User::where('empresa_id', $empresa_id)
                              ->where('ativo', true)
                              ->get()
                              ->toArray();

            // Carregando clientes e garantindo que seja um array
            $clientes = \App\Models\Cliente::where('empresa_id', $empresa_id)
                                         ->where('ativo', true)
                                         ->orderBy('razao_social')
                                         ->get();
            
            $this->clientes = $clientes->map(function($cliente) {
                return [
                    'id' => (string)$cliente->id,
                    'razao_social' => $cliente->razao_social
                ];
            })->toArray();

            logger()->info('Dados carregados:', [
                'clientes_count' => count($this->clientes),
                'clientes_exemplo' => array_slice($this->clientes, 0, 2),
                'users_count' => count($this->users),
                'taskTypes_count' => count($this->taskTypes),
                'empresa_id' => $empresa_id
            ]);

        } catch (\Exception $e) {
            logger()->error('Erro ao carregar dados do formulário', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function loadTask()
    {
        try {
            $task = Task::with(['cliente']) // Carregando o relacionamento do cliente
                       ->where('empresa_id', Session::get('empresa_id'))
                       ->findOrFail($this->taskId);

            $this->task = $task;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->cliente = (string)$task->cliente_id;
            $this->taskType = $task->task_type_id;
            $this->assignedTo = $task->assigned_to;
            $this->location = $task->location;
            $this->status = $task->status;
            $this->priority = $task->priority;
            
            // Formatando as datas corretamente
            $this->startDate = $task->start_date ? Carbon::parse($task->start_date)->format('Y-m-d') : null;
            $this->dueDate = $task->due_date ? Carbon::parse($task->due_date)->format('Y-m-d') : null;
            $this->endDate = $task->end_date ? Carbon::parse($task->end_date)->format('Y-m-d') : null;

            // Log para debug
            logger()->info('Task carregada com dados completos:', [
                'task_id' => $this->taskId,
                'cliente_id' => $this->cliente,
                'cliente_razao_social' => $task->cliente?->razao_social,
                'start_date' => $this->startDate,
                'due_date' => $this->dueDate,
                'end_date' => $this->endDate
            ]);

        } catch (\Exception $e) {
            logger()->error('Erro ao carregar tarefa', [
                'task_id' => $this->taskId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Erro ao carregar tarefa: ' . $e->getMessage());
        }
    }

    public function getClienteNome()
    {
        logger()->info('getClienteNome chamado', [
            'cliente_id' => $this->cliente,
            'cliente_type' => gettype($this->cliente),
            'clientes_count' => count($this->clientes),
            'clientes_ids' => array_column($this->clientes, 'id'),
            'clientes' => $this->clientes
        ]);

        if (empty($this->cliente)) {
            return '';
        }

        // Procurando o cliente pelo ID como string
        $clienteId = (string)$this->cliente;
        
        $clienteEncontrado = collect($this->clientes)->first(function($c) use ($clienteId) {
            return $c['id'] === $clienteId;
        });

        if ($clienteEncontrado) {
            return $clienteEncontrado['razao_social'];
        }
        
        return '';
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required|min:3',
                'description' => 'required',
                'taskType' => 'required',
                'cliente' => 'required',
                'status' => 'required',
                'priority' => 'required',
            ]);

            $this->task->update([
                'title' => $this->title,
                'description' => $this->description,
                'task_type_id' => $this->taskType,
                'assigned_to' => $this->assignedTo,
                'cliente_id' => $this->cliente,
                'start_date' => $this->startDate,
                'due_date' => $this->dueDate,
                'priority' => $this->priority,
                'status' => $this->status,
                'estimated_minutes' => $this->estimatedMinutes,
                'budget' => $this->budget,
                'location' => $this->location,
                'tags' => $this->tags,
                'requires_approval' => $this->requiresApproval,
                'is_recurring' => $this->isRecurring,
                'recurrence_pattern' => $this->recurrencePattern,
                'recurrence_config' => $this->recurrenceConfig,
            ]);

            TaskHistory::logChange(
                $this->task->id,
                auth()->id(),
                'update',
                'Tarefa atualizada'
            );

            session()->flash('success', 'Tarefa atualizada com sucesso!');
            return redirect()->route('painel.tasks.view', ['taskId' => $this->taskId]);

        } catch (\Exception $e) {
            logger()->error('Erro ao atualizar tarefa', [
                'task_id' => $this->taskId,
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'Erro ao atualizar tarefa: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.tasks.task-view')
               ->layout('layouts.app');
    }
}
