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
    public $selectedUsers = [];  // Array para armazenar os IDs dos usuários selecionados
    public $cliente;
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

    // Dados para os selects
    public $clientes;
    public $users;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'taskType' => 'required|exists:task_types,id',
        'selectedUsers' => 'nullable|array',
        'selectedUsers.*' => 'exists:users,id',
        'cliente' => 'nullable|exists:clientes,id',
        'dueDate' => 'nullable|date',
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:pending,in_progress,completed,delayed,cancelled',
        'estimatedMinutes' => 'nullable|integer|min:1',
        'budget' => 'nullable|numeric|min:0',
        'location' => 'nullable|string|max:255',
        'tags' => 'nullable|array',
        'tags.*' => 'string|max:50',
        'requiresApproval' => 'boolean',
        'isRecurring' => 'boolean',
        'recurrencePattern' => 'nullable|string|max:50',
        'recurrenceConfig' => 'nullable|array'
    ];

    public function mount()
    {
        $this->initializeFields();
        $this->loadData();
    }

    private function initializeFields()
    {
        $this->status = 'pending';
        $this->priority = 'medium';
        $this->selectedUsers = [];
    }

    protected function getListeners()
    {
        return [
            'showTaskModal' => 'showModal',
            'editTask' => 'editTask',
            'hideModal' => 'hideModal',
        ];
    }

    public function init()
    {
        $this->loadData();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->ensureSelectedUsersArray();
    }

    private function ensureSelectedUsersArray()
    {
        if (!is_array($this->selectedUsers)) {
            $this->selectedUsers = [];
        }
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
            
            // Carrega usuários exceto o usuário logado
            $this->users = User::where('empresa_id', $empresaId)
                              ->where('ativo', true)
                              ->where('id', '!=', Auth::id())
                              ->get();
            
            logger()->info('Usuários carregados:', [
                'count' => $this->users->count(),
                'empresa_id' => $empresaId
            ]);
            
            $this->clientes = Cliente::where('empresa_id', $empresaId)
                                   ->where('ativo', true)
                                   ->get();
                                   
            logger()->info('Clientes carregados:', [
                'count' => $this->clientes->count(),
                'empresa_id' => $empresaId
            ]);
                                   
        } catch (\Exception $e) {
            logger()->error('Erro ao carregar dados:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->users = collect([]);
            $this->clientes = collect([]);
        }
    }

    public function showModal($data = [])
    {
        $this->reset(['taskId', 'title', 'description', 'taskType', 'selectedUsers', 'cliente', 'dueDate', 
                     'priority', 'status', 'estimatedMinutes', 'budget', 'location', 'tags', 
                     'requiresApproval', 'isRecurring', 'recurrencePattern', 'recurrenceConfig', 
                     'attachments', 'viewOnly']);
        
        // Define valores padrão após o reset
        $this->status = 'pending';
        $this->priority = 'medium';
        
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        $this->dispatch('show-task-modal');
    }

    public function loadTask($taskId)
    {
        try {
            $task = Task::with(['assignedUsers', 'cliente'])->findOrFail($taskId);
            
            $this->taskId = $task->id;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->taskType = $task->task_type_id;
            $this->selectedUsers = $task->assignedUsers->pluck('id')->toArray();
            $this->cliente = $task->cliente_id;
            $this->dueDate = $task->due_date;
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
            
        } catch (\Exception $e) {
            logger()->error('Erro ao carregar tarefa:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('showToast', 'error', 'Erro ao carregar tarefa');
        }
    }

    public function editTask($taskId)
    {
        $this->taskId = $taskId;
        $this->task = Task::findOrFail($taskId);
        
        $this->title = $this->task->title;
        $this->description = $this->task->description;
        $this->taskType = $this->task->task_type_id;
        $this->selectedUsers = $this->task->assignedUsers->pluck('id')->toArray();
        $this->cliente = $this->task->cliente_id;
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

        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->validate();

            DB::beginTransaction();

            $taskData = [
                'title' => $this->title,
                'description' => $this->description,
                'task_type_id' => $this->taskType,
                'cliente_id' => $this->cliente,
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
                'created_by' => Auth::id(),
                'ativo' => true,
                'start_date' => now() // Adicionando a data atual como data de início
            ];

            if ($this->taskId) {
                $task = Task::findOrFail($this->taskId);
                // Remove start_date do array se for uma atualização
                unset($taskData['start_date']);
                $task->update($taskData);
            } else {
                $task = Task::create($taskData);
            }

            // Sincroniza os usuários designados
            $task->assignedUsers()->sync($this->selectedUsers ?: []);

            DB::commit();

            // Limpa o formulário
            $this->clearForm();
            
            // Dispara eventos para atualizar a interface
            $this->dispatch('task-saved');
            $this->dispatch('refresh-tasks');
            $this->dispatch('showToast', 'success', 'Tarefa salva com sucesso!');
            
            // Fecha o modal via JavaScript
            $this->dispatch('close-task-modal');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Erro ao salvar tarefa:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('showToast', 'error', 'Erro ao salvar tarefa');
        }
    }

    public function hideModal()
    {
        $this->showModal = false;
        $this->viewOnly = false;
        $this->resetValidation();
        $this->reset([
            'taskId', 'title', 'description', 'status', 'priority', 
            'dueDate', 'cliente', 'selectedUsers', 'taskType',
            'estimatedMinutes', 'budget', 'location',
            'tags', 'requiresApproval', 'isRecurring',
            'recurrencePattern', 'recurrenceConfig'
        ]);
    }

    public function clearForm()
    {
        $this->initializeFields();
        $this->reset([
            'taskId',
            'title',
            'description',
            'taskType',
            'cliente',
            'dueDate',
            'estimatedMinutes',
            'budget',
            'location',
            'tags',
            'requiresApproval',
            'isRecurring',
            'recurrencePattern',
            'recurrenceConfig'
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
                                    ->where('ativo', true)
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

// Removendo o relacionamento duplicado no modelo Task
// Não há mais o relacionamento duplicado no modelo Task
