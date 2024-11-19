<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4">
                <div class="bg-soft-primary">
                    <div class="card-body pb-0 px-4">
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="row align-items-center g-3">
                                    <div class="col-md">
                                        <div>
                                            <h4 class="fw-bold">{{ $title }}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div class="text-muted">
                                                    <i class="bi bi-building me-1"></i>
                                                    {{ $task->cliente?->razao_social }}
                                                </div>
                                                <div class="vr"></div>
                                                <div class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    Criada em {{ $task->created_at->format('d/m/Y') }}
                                                </div>
                                                <div class="vr"></div>
                                                <div>
                                                    @switch($priority)
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
                                                            <span class="badge bg-secondary">{{ $priority }}</span>
                                                    @endswitch
                                                </div>
                                                <div class="vr"></div>
                                                <div>
                                                    @switch($status)
                                                        @case('pending')
                                                            <span class="badge bg-warning">🕒 Pendente</span>
                                                            @break
                                                        @case('in_progress')
                                                            <span class="badge bg-info">▶️ Em Andamento</span>
                                                            @break
                                                        @case('completed')
                                                            <span class="badge bg-success">✅ Concluída</span>
                                                            @break
                                                        @case('delayed')
                                                            <span class="badge bg-danger">⚠️ Atrasada</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-secondary">❌ Cancelada</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $status }}</span>
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <div class="hstack gap-1 flex-wrap">
                                    <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                                        <i class="bi bi-save me-1"></i> Salvar Alterações
                                    </button>
                                    <a href="{{ route('painel.tasks.index') }}" class="btn btn-light">
                                        <i class="bi bi-x-lg me-1"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#project-overview" role="tab">
                                    Visão Geral
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-documents" role="tab">
                                    Documentos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-activities" role="tab">
                                    Atividades
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Descrição</h6>
                                        <p>{{ $description }}</p>

                                        @if($task->start_date || $dueDate)
                                            <div class="pt-3">
                                                <div class="row">
                                                    @if($task->start_date)
                                                        <div class="col-6">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 me-2">
                                                                    <i class="ri-calendar-todo-line fs-15 text-muted"></i>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-0">Data de Início</h6>
                                                                    <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($task->start_date)->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($dueDate)
                                                        <div class="col-6">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 me-2">
                                                                    <i class="ri-calendar-check-line fs-15 text-muted"></i>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-0">Data Limite</h6>
                                                                    <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($dueDate)->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div class="pt-3">
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-6">
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">Status:</h6>
                                                        <select class="form-select" wire:model="status">
                                                            <option value="pending">🕒 Pendente</option>
                                                            <option value="in_progress">▶️ Em Andamento</option>
                                                            <option value="completed">✅ Concluída</option>
                                                            <option value="delayed">⚠️ Atrasada</option>
                                                            <option value="cancelled">❌ Cancelada</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">Prioridade:</h6>
                                                        <select class="form-select" wire:model="priority">
                                                            <option value="low">🟢 Baixa</option>
                                                            <option value="medium">🟡 Média</option>
                                                            <option value="high">🟠 Alta</option>
                                                            <option value="urgent">🔴 Urgente</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">Tipo de Tarefa:</h6>
                                                        <select class="form-select" wire:model="taskType">
                                                            @foreach($taskTypes as $type)
                                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">Cliente:</h6>
                                                        <select class="form-select" wire:model="clienteId">
                                                            <option value="">Selecione um cliente</option>
                                                            @foreach($clientes as $cliente)
                                                                <option value="{{ $cliente->id }}">{{ $cliente->razao_social }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">Usuários Designados:</h6>
                                                        <select class="form-select" wire:model="assignedTo" multiple>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">Tempo Estimado (minutos):</h6>
                                                        <input type="number" class="form-control" wire:model="estimatedMinutes">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="mb-3">
                                                        <h6 class="mb-2">Orçamento (R$):</h6>
                                                        <input type="number" step="0.01" class="form-control" wire:model="budget">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Comentários</h5>
                                        </div>
                                        <div class="card-body">
                                            <div>Em desenvolvimento...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Detalhes da Tarefa</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="mb-1">Localização:</h6>
                                        <input type="text" class="form-control" wire:model="location">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="requiresApproval" id="requiresApproval">
                                            <label class="form-check-label" for="requiresApproval">
                                                Requer Aprovação
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="isRecurring" id="isRecurring">
                                            <label class="form-check-label" for="isRecurring">
                                                Tarefa Recorrente
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="project-documents" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div>Em desenvolvimento...</div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="project-activities" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div>Em desenvolvimento...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
