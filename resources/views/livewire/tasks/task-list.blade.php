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
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-12">
                            <div class="search-box">
                                <input type="text" class="form-control search" wire:model.debounce.300ms="search"
                                    placeholder="Buscar por título ou descrição...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-4">
                            <div>
                                <select class="form-control" wire:model="status">
                                    <option value="">Selecione o Status</option>
                                    <option value="pending">Pendente</option>
                                    <option value="in_progress">Em Andamento</option>
                                    <option value="completed">Concluída</option>
                                    <option value="delayed">Atrasada</option>
                                    <option value="cancelled">Cancelada</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-4">
                            <div>
                                <select class="form-control" wire:model="priority">
                                    <option value="">Selecione a Prioridade</option>
                                    <option value="low">Baixa</option>
                                    <option value="medium">Média</option>
                                    <option value="high">Alta</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-1 col-sm-4">
                            <div>
                                <button type="button" class="btn btn-primary w-100" wire:click="$refresh">
                                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th class="sort" wire:click="sortBy('id')">#ID</th>
                                    <th class="sort" wire:click="sortBy('title')">Tarefa</th>
                                    <th class="sort" wire:click="sortBy('cliente_id')">Cliente</th>
                                    <th class="sort" wire:click="sortBy('assigned_to')">Responsável</th>
                                    <th class="sort" wire:click="sortBy('due_date')">Data Limite</th>
                                    <th class="sort" wire:click="sortBy('status')">Status</th>
                                    <th class="sort" wire:click="sortBy('priority')">Prioridade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>{{ $task->id }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-14 mb-1">
                                                        <a href="#" class="text-dark">{{ $task->title }}</a>
                                                    </h5>
                                                    <p class="text-muted mb-0">
                                                        {{ Str::limit($task->description, 50) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $task->cliente ? $task->cliente->razao_social : 'N/A' }}</td>
                                        <td>
                                            <div class="avatar-group">
                                                @if($task->assignedTo)
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="{{ $task->assignedTo->name }}">
                                                        <div class="avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                {{ substr($task->assignedTo->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </a>
                                                @else
                                                    <span class="badge bg-light text-muted">Não atribuído</span>
                                                @endif
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
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownTask{{ $task->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownTask{{ $task->id }}">
                                                    <li>
                                                        <a href="{{ route('painel.tasks.view', ['taskId' => $task->id]) }}" class="dropdown-item">
                                                            <i class="bi bi-eye me-2"></i> Visualizar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('painel.tasks.view', ['taskId' => $task->id]) }}" class="dropdown-item">
                                                            <i class="bi bi-pencil me-2"></i> Editar
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger" 
                                                            wire:click="deleteTask({{ $task->id }})"
                                                            wire:loading.attr="disabled"
                                                            onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')">
                                                            <i class="bi bi-trash me-2"></i> Excluir
                                                        </button>
                                                    </li>
                                                </ul>
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
</div>
