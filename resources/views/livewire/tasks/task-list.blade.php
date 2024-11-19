<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="tasksList">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Todas as Tarefas</h5>
                        <div class="flex-shrink-0">
                            <button class="btn btn-danger add-btn" type="button" data-bs-toggle="modal" data-bs-target="#taskModal" wire:click="$dispatch('showTaskModal')">
                                <i class="ri-add-line align-bottom me-1"></i> Nova Tarefa
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body border border-dashed border-end-0 border-start-0">
                    <div class="row g-3 mb-4">
                        <!-- Busca -->
                        <div class="col-xxl-3 col-sm-12">
                            <div class="search-box">
                                <input type="text" 
                                       class="form-control search" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Buscar por título ou descrição...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <!-- Filtro por Status -->
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label for="status_filter" class="form-label">Status</label>
                                <select wire:model.live="status" id="status_filter" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="pending">Pendente</option>
                                    <option value="in_progress">Em Andamento</option>
                                    <option value="completed">Concluída</option>
                                    <option value="delayed">Atrasada</option>
                                    <option value="cancelled">Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtro por Prioridade -->
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label for="priority_filter" class="form-label">Prioridade</label>
                                <select wire:model.live="priority" id="priority_filter" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="low">Baixa</option>
                                    <option value="medium">Média</option>
                                    <option value="high">Alta</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtro por Tipo -->
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label for="taskType_filter" class="form-label">Tipo</label>
                                <select wire:model.live="taskType" id="taskType_filter" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($taskTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Filtro por Usuário -->
                        <div class="col-xxl-3 col-sm-4">
                            <div>
                                <label for="assignedTo_filter" class="form-label">Responsável</label>
                                <select wire:model.live="assignedTo" id="assignedTo_filter" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th class="sort" wire:click="sortBy('title')">Tarefa</th>
                                    <th class="sort" wire:click="sortBy('cliente_id')">Cliente</th>
                                    <th class="sort" wire:click="sortBy('assigned_to')">Responsáveis</th>
                                    <th class="sort" wire:click="sortBy('due_date')">Data Limite</th>
                                    <th class="sort" wire:click="sortBy('status')">Status</th>
                                    <th class="sort" wire:click="sortBy('priority')">Prioridade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-14 mb-1">
                                                        <a href="{{ route('painel.tasks.view', ['taskId' => $task->id]) }}" class="text-dark">{{ $task->title }}</a>
                                                    </h5>
                                                    <p class="text-muted mb-0">
                                                        {{ Str::limit($task->description, 50) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $task->cliente ? $task->cliente->razao_social : 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @forelse($task->assignedUsers as $user)
                                                    <span class="badge bg-info">{{ $user->name }}</span>
                                                @empty
                                                    <span class="text-muted">Sem responsável</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>{{ $task->due_date ? $task->due_date->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @switch($task->status)
                                                @case('pending')
                                                    <span class="badge bg-warning-subtle text-warning">🕒 Pendente</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-info-subtle text-info">▶️ Em Andamento</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success-subtle text-success">✅ Concluída</span>
                                                    @break
                                                @case('delayed')
                                                    <span class="badge bg-danger-subtle text-danger">⚠️ Atrasada</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-dark-subtle text-dark">❌ Cancelada</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary-subtle text-secondary">{{ $task->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($task->priority)
                                                @case('low')
                                                    <span class="badge bg-success">🟢 Baixa</span>
                                                    @break
                                                @case('medium')
                                                    <span class="badge bg-warning">🟡 Média</span>
                                                    @break
                                                @case('high')
                                                    <span class="badge bg-orange">🟠 Alta</span>
                                                    @break
                                                @case('urgent')
                                                    <span class="badge bg-danger">🔴 Urgente</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $task->priority }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('painel.tasks.view', ['taskId' => $task->id]) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Editar">
                                                    <i class="ri-edit-2-line"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="if(confirm('Tem certeza que deseja excluir esta tarefa?')) { @this.deleteTask({{ $task->id }}) }"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Excluir">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="p-4">
                                                <div class="avatar-lg mx-auto mb-4">
                                                    <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                                        <i class="ri-folder-3-line"></i>
                                                    </div>
                                                </div>
                                                <h4>Nenhuma tarefa encontrada</h4>
                                                <p class="text-muted">Crie uma nova tarefa para começar.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                            <i class="ri-list-check"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total de Tarefas</p>
                                    <h4 class="fs-4 mb-0">{{ $totalTasks }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Concluídas</p>
                                    <h4 class="fs-4 mb-0">{{ $completedTasks }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i class="ri-time-line"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Em Andamento</p>
                                    <h4 class="fs-4 mb-0">{{ $inProgressTasks }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                            <i class="ri-error-warning-line"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Atrasadas</p>
                                    <h4 class="fs-4 mb-0">{{ $overdueTasks }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewire('tasks.task-modal')

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Livewire.on('confirmDelete', (data) => {
            //     if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
            //         Livewire.dispatch('deleteTask', { taskId: data.taskId });
            //     }
            // });
        });
    </script>
    @endpush
</div>
