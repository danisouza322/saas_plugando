<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class TaskModal extends Component
{
    use WithFileUploads;

    public $taskId;
    public $task;
    public $showModal = false;
    public $viewOnly = false;

    // Campos do formulário
    public $title;
    public $description;
    public $taskType;
    public $assignedTo;
    public $cliente;
    public $startDate;
    public $dueDate;
    public $priority = 'medium';
    public $status = 'pending';
    public $estimatedMinutes;
    public $budget;
    public $location;
    public $tags = [];
    public $requiresApproval = false;
    public $isRecurring = false;
    public $recurrencePattern;
    public $recurrenceConfig;
    public $attachments = [];
    public $teamMembers = [];

    // Dados para os selects
    protected $clientes;
    protected $users;

    public function mount()
    {
        $this->status = 'pending';
        $this->priority = 'medium';
        $this->loadData();
    }

    protected function getListeners()
    {
        return [
            'showTaskModal' => 'showModal',
            'editTask' => 'editTask',
            'hideModal' => 'hideModal',
        ];
    }

    protected function rules()
    {
        return [
            'title' => 'required|min:3',
            'description' => 'nullable',
            'taskType' => 'required|exists:task_types,id',
            'assignedTo' => 'nullable|exists:users,id',
            'cliente' => 'nullable|exists:clientes,id',
            'startDate' => 'nullable|date',
            'dueDate' => 'nullable|date|after_or_equal:startDate',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,delayed,cancelled',
            'estimatedMinutes' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'location' => 'nullable|string',
            'tags' => 'nullable|array',
            'requiresApproval' => 'boolean',
            'isRecurring' => 'boolean',
            'recurrencePattern' => 'nullable|required_if:isRecurring,true',
            'recurrenceConfig' => 'nullable',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => 'exists:users,id'
        ];
    }

    public function init()
    {
        $this->loadData();
    }

    public function updated($field)
    {
        logger()->info('Campo atualizado', ['field' => $field, 'value' => $this->$field]);
        $this->validateOnly($field);
    }

    public function hydrate()
    {
        if (empty($this->clientes) || empty($this->users)) {
            $this->loadData();
        }
    }

    public function dehydrate()
    {
        // Preserva os dados durante a serialização
        $this->clientes = $this->clientes;
        $this->users = $this->users;
    }

    public function loadData()
    {
        try {
            $empresaId = Session::get('empresa_id');
            if (!$empresaId) {
                logger()->error('Empresa ID não encontrado na sessão');
                $this->clientes = collect([]);
                $this->users = collect([]);
                return;
            }

            if (!empty($this->clientes) && !empty($this->users)) {
                return; // Já tem dados carregados
            }

            logger()->info('Carregando dados com empresa_id: ' . $empresaId);

            $this->clientes = Cliente::where('empresa_id', $empresaId)
                                   ->orderBy('nome')
                                   ->get();
                                   
            $this->users = User::where('empresa_id', $empresaId)
                              ->where('ativo', true)
                              ->orderBy('name')
                              ->get();

            logger()->info('Dados carregados com sucesso', [
                'empresa_id' => $empresaId,
                'total_clientes' => $this->clientes->count(),
                'total_users' => $this->users->count()
            ]);
        } catch (\Exception $e) {
            logger()->error('Erro ao carregar dados: ' . $e->getMessage());
            $this->clientes = collect([]);
            $this->users = collect([]);
        }
    }

    #[On('show-task-modal')]
    public function showModal($data = [])
    {
        logger()->info('showModal chamado', $data);
        
        $this->loadData();
        $this->resetValidation();
        
        if (isset($data['taskId'])) {
            $this->loadTask($data['taskId']);
        }
        
        $this->viewOnly = isset($data['mode']) && $data['mode'] === 'view';
        $this->showModal = true;
        
        $this->dispatch('open-modal', 'taskModal');
    }

    protected function loadTask($taskId)
    {
        try {
            $task = Task::with(['cliente', 'assignedTo', 'type'])
                       ->where('empresa_id', Session::get('empresa_id'))
                       ->findOrFail($taskId);

            $this->taskId = $task->id;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->taskType = $task->task_type_id;
            $this->assignedTo = $task->assigned_to;
            $this->cliente = $task->cliente_id;
            $this->startDate = $task->start_date?->format('Y-m-d');
            $this->dueDate = $task->due_date?->format('Y-m-d');
            $this->priority = $task->priority;
            $this->status = $task->status;
            $this->estimatedMinutes = $task->estimated_minutes;
            $this->budget = $task->budget;
            $this->location = $task->location;
            $this->tags = $task->tags;
            $this->requiresApproval = $task->requires_approval;
            $this->isRecurring = $task->is_recurring;
            $this->recurrencePattern = $task->recurrence_pattern;
            $this->recurrenceConfig = $task->recurrence_config;

            logger()->info('Tarefa carregada com sucesso', ['task_id' => $taskId]);

        } catch (\Exception $e) {
            logger()->error('Erro ao carregar tarefa', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erro ao carregar tarefa: ' . $e->getMessage()
            ]);
        }
    }

    public function editTask($taskId)
    {
        $this->taskId = $taskId;
        $this->task = Task::findOrFail($taskId);
        
        $this->title = $this->task->title;
        $this->description = $this->task->description;
        $this->taskType = $this->task->task_type_id;
        $this->assignedTo = $this->task->assigned_to;
        $this->cliente = $this->task->cliente_id;
        $this->startDate = $this->task->start_date;
        $this->dueDate = $this->task->due_date;
        $this->priority = $this->task->priority;
        $this->status = $this->task->status;
        $this->estimatedMinutes = $this->task->estimated_minutes;
        $this->budget = $this->task->budget;
        $this->location = $this->task->location;
        $this->tags = $this->task->tags;
        $this->requiresApproval = $this->task->requires_approval;
        $this->isRecurring = $this->task->is_recurring;
        $this->recurrencePattern = $this->task->recurrence_pattern;
        $this->recurrenceConfig = $this->task->recurrence_config;
        $this->teamMembers = $this->task->teamMembers->pluck('id')->toArray();

        $this->showModal = true;
    }

    public function save()
    {
        logger()->info('Iniciando save da tarefa', [
            'request' => request()->all(),
            'dados' => [
                'title' => $this->title,
                'cliente_id' => $this->cliente,
                'assigned_to' => $this->assignedTo,
                'task_type_id' => $this->taskType,
                'empresa_id' => Session::get('empresa_id')
            ]
        ]);

        try {
            $validatedData = $this->validate([
                'title' => 'required|min:3',
                'description' => 'nullable',
                'taskType' => 'required|exists:task_types,id',
                'assignedTo' => 'nullable|exists:users,id',
                'cliente' => 'nullable|exists:clientes,id',
                'startDate' => 'nullable|date',
                'dueDate' => 'nullable|date|after_or_equal:startDate',
                'priority' => 'required|in:low,medium,high,urgent',
                'status' => 'required|in:pending,in_progress,completed,delayed,cancelled',
                'estimatedMinutes' => 'nullable|integer|min:0',
                'budget' => 'nullable|numeric|min:0',
                'location' => 'nullable|string',
                'tags' => 'nullable|array',
                'requiresApproval' => 'boolean',
                'isRecurring' => 'boolean',
                'recurrencePattern' => 'nullable|required_if:isRecurring,true',
                'recurrenceConfig' => 'nullable'
            ]);

            logger()->info('Dados validados com sucesso', $validatedData);

            DB::beginTransaction();

            try {
                $data = [
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
                    'empresa_id' => Session::get('empresa_id'),
                    'created_by' => Auth::id()
                ];

                logger()->info('Dados preparados para salvar', $data);

                if (!$this->taskId) {
                    logger()->info('Criando nova tarefa');
                    $task = Task::create($data);
                    logger()->info('Nova tarefa criada', ['task_id' => $task->id]);
                } else {
                    logger()->info('Atualizando tarefa existente', ['task_id' => $this->taskId]);
                    $task = Task::findOrFail($this->taskId);
                    $task->update($data);
                    logger()->info('Tarefa atualizada com sucesso');
                }

                // Salvar anexos
                if ($this->attachments) {
                    foreach ($this->attachments as $attachment) {
                        $filename = $attachment->store('task-attachments');
                        $task->attachments()->create([
                            'user_id' => Auth::id(),
                            'filename' => basename($filename),
                            'original_filename' => $attachment->getClientOriginalName(),
                            'mime_type' => $attachment->getMimeType(),
                            'size' => $attachment->getSize()
                        ]);
                    }
                }

                // Atualizar membros da equipe
                if ($this->teamMembers) {
                    $task->teamMembers()->sync($this->teamMembers);
                }

                // Registrar histórico
                $task->logHistory(
                    Auth::id(),
                    $this->taskId ? 'update' : 'create',
                    $this->taskId ? 'Tarefa atualizada' : 'Tarefa criada',
                    $this->taskId ? 'Tarefa atualizada por ' . Auth::user()->name : 'Tarefa criada por ' . Auth::user()->name
                );

                DB::commit();

                logger()->info('Tarefa salva com sucesso', ['task_id' => $task->id]);
                
                $this->dispatch('task-saved');
                $this->hideModal();
                
                // Emitir evento de notificação
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => $this->taskId ? 'Tarefa atualizada com sucesso!' : 'Tarefa criada com sucesso!'
                ]);

                // Atualizar a lista de tarefas
                $this->dispatch('refresh-tasks');
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            logger()->error('Erro ao salvar tarefa: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Erro ao salvar tarefa: ' . $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function hideModal()
    {
        $this->showModal = false;
        $this->viewOnly = false;
        $this->resetValidation();
        $this->reset([
            'taskId', 'title', 'description', 'status', 'priority', 
            'dueDate', 'cliente', 'assignedTo', 'taskType',
            'startDate', 'estimatedMinutes', 'budget', 'location',
            'tags', 'requiresApproval', 'isRecurring',
            'recurrencePattern', 'recurrenceConfig'
        ]);
    }

    public function removeAttachment($attachmentId)
    {
        if ($this->taskId) {
            $attachment = $this->task->attachments()->find($attachmentId);
            if ($attachment) {
                Storage::delete('task-attachments/' . $attachment->filename);
                $attachment->delete();
            }
        }
    }

    public function getClientes()
    {
        return $this->clientes ?: collect([]);
    }

    public function getUsers()
    {
        return $this->users ?: collect([]);
    }

    public function render()
    {
        try {
            // Garante que os dados estão carregados
            if (empty($this->clientes) || empty($this->users)) {
                $this->loadData();
            }

            return view('livewire.tasks.task-modal', [
                'taskTypes' => TaskType::where('empresa_id', Session::get('empresa_id'))
                                    ->where('active', true)
                                    ->get(),
                'clientes' => $this->clientes ?: collect([]),
                'users' => $this->users ?: collect([])
            ]);
        } catch (\Exception $e) {
            logger()->error('Erro no render: ' . $e->getMessage());
            return view('livewire.tasks.task-modal', [
                'taskTypes' => collect([]),
                'clientes' => collect([]),
                'users' => collect([])
            ]);
        }
    }
}
