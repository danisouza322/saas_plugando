<div>
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar minimal-border">
            <div class="p-4 d-flex flex-column h-100">
                <div class="mb-3">
                    <div>
                        <button type="button" class="btn btn-primary" wire:click="createTarefa">
                            <i class="ri-add-line align-bottom me-1"></i>Nova Tarefa
                        </button>
                    </div>
                </div>

                <div class="px-4 mx-n4" data-simplebar style="height: calc(100vh - 468px);">
                    <ul class="to-do-menu list-unstyled" id="projectlist-data">
                        <li>
                            <a data-bs-toggle="collapse" href="#statusTarefas" class="nav-link fs-13 active">Status das Tarefas</a>
                            <div class="collapse show" id="statusTarefas">
                                <ul class="mb-0 sub-menu list-unstyled ps-3 vstack gap-2 mb-2">
                                    <li>
                                        <a href="#" wire:click="$set('statusFilter', 'novo')">
                                            <i class="ri-stop-mini-fill align-middle fs-15 text-primary"></i> Novas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" wire:click="$set('statusFilter', 'em_andamento')">
                                            <i class="ri-stop-mini-fill align-middle fs-15 text-warning"></i> Em Andamento
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" wire:click="$set('statusFilter', 'pendente')">
                                            <i class="ri-stop-mini-fill align-middle fs-15 text-info"></i> Pendentes
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" wire:click="$set('statusFilter', 'concluido')">
                                            <i class="ri-stop-mini-fill align-middle fs-15 text-success"></i> Concluídas
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="mt-auto text-center">
                    <img src="{{ URL::asset('build/images/task.png') }}" alt="Task" class="img-fluid" />
                </div>
            </div>
        </div>

        <div class="file-manager-content minimal-border w-100 p-4 pb-0">
            <div class="row mb-4">
                <div class="col-auto order-1 d-block d-lg-none">
                    <button type="button" class="btn btn-soft-success btn-icon btn-sm fs-16 file-menu-btn">
                        <i class="ri-menu-2-fill align-bottom"></i>
                    </button>
                </div>
                <div class="col-sm order-3 order-sm-2 mt-3 mt-sm-0">
                    <h5 class="fw-semibold mb-0">Gerenciamento de Tarefas</h5>
                </div>
            </div>

            <div class="p-3 bg-light rounded mb-4">
                <div class="row g-2">
                    <div class="col-lg-auto">
                        <select class="form-control" wire:model.live="statusFilter">
                            <option value="">Todos os Status</option>
                            <option value="novo">Novas</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="pendente">Pendentes</option>
                            <option value="concluido">Concluídas</option>
                        </select>
                    </div>
                    <div class="col-lg">
                        <div class="search-box">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control search" placeholder="Buscar por título, cliente...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <button type="button" class="btn btn-primary" wire:click="createTarefa">
                            <i class="ri-add-line align-bottom me-1"></i>Nova Tarefa
                        </button>
                    </div>
                </div>
            </div>

            <div class="todo-content position-relative px-4 mx-n4">
                <div class="todo-task">
                    <div class="table-responsive">
                        <table class="table align-middle position-relative">
                            <thead class="table-active">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </div>
                                    </th>
                                    <th scope="col">Título</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Responsáveis</th>
                                    <th scope="col">Vencimento</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Prioridade</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tarefas as $tarefa)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input task-checkbox" type="checkbox"
                                                    wire:click="toggleStatus({{ $tarefa->id }})"
                                                    @checked($tarefa->status === 'concluido')>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="@if($tarefa->status === 'concluido') text-decoration-line-through text-muted @endif">
                                                        <h5 class="fs-14 mb-0">
                                                            {{ $tarefa->titulo }}
                                                            @if($tarefa->arquivos->count() > 0)
                                                                <i class="ri-attachment-2 text-muted" 
                                                                   data-bs-toggle="tooltip" 
                                                                   data-bs-placement="top" 
                                                                   title="{{ $tarefa->arquivos->count() }} arquivo(s) anexado(s)"></i>
                                                            @endif
                                                        </h5>
                                                        @if($tarefa->descricao)
                                                            <p class="text-muted fs-12 mb-0">{{ Str::limit($tarefa->descricao, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $tarefa->cliente->razao_social }}</td>
                                        <td>
                                            <div class="avatar-group">
                                                @foreach($tarefa->responsaveis as $responsavel)
                                                    <a href="javascript: void(0);" 
                                                       class="avatar-group-item" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       title="{{ $responsavel->name }}">
                                                        <img src="{{ $responsavel->profile_photo_url }}" 
                                                             alt="{{ $responsavel->name }}" 
                                                             class="rounded-circle avatar-xs">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>{{ $tarefa->data_vencimento->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $tarefa->status_color }}">
                                                {{ ucfirst($tarefa->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $tarefa->prioridade_color }}">
                                                {{ ucfirst($tarefa->prioridade) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2">
                                                <button class="btn btn-sm btn-soft-primary" wire:click="editTarefa({{ $tarefa->id }})">
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-soft-danger" wire:click="confirmDelete({{ $tarefa->id }})">
                                                    <i class="ri-delete-bin-2-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Nenhuma tarefa encontrada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Tarefa -->
    @livewire('tarefas.tarefa-modal')
</div>
