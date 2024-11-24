<div>
    <style>
        .avatar-xs {
            height: 2rem !important;
            width: 2rem !important;
        }
        .avatar-xs img {
            height: 100% !important;
            width: 100% !important;
            object-fit: cover !important;
        }
        .avatar-title {
            height: 100% !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    </style>
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0">
            <div class="row mb-4">
                <div class="col-sm">
                    <div class="d-flex">
                        <h5 class="fw-semibold mb-0">Gerenciamento de Tarefas</h5>
                        <button type="button" class="btn btn-primary ms-auto" wire:click="createTarefa">
                            <i class="ri-add-line align-bottom me-1"></i>Nova Tarefa
                        </button>
                    </div>
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
                            <input type="text" 
                                wire:model.live.debounce.300ms="search" 
                                class="form-control search" 
                                placeholder="Buscar por título, descrição, nome fantasia ou razão social do cliente...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('admin') || Auth::user()->can('view_all_tasks'))
                    <div class="col-lg-auto">
                        <button type="button" class="btn btn-secondary" wire:click="toggleViewAll">
                            <i class="ri-eye-line align-bottom me-1"></i>
                            {{ $showAllTasks ? 'Ver Minhas Tarefas' : 'Ver Todas as Tarefas' }}
                        </button>
                    </div>
                    @endif
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
                                                    wire:change="toggleStatus({{ $tarefa->id }})"
                                                    {{ $tarefa->status === 'concluido' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-14 mb-1 @if($tarefa->status === 'concluido') text-decoration-line-through text-muted @endif">
                                                        <a class="text-dark" href="javascript:void(0);" wire:click="editTarefa({{ $tarefa->id }})">{{ $tarefa->titulo }}</a>
                                                        @if($tarefa->arquivos->count() > 0)
                                                            <i class="ri-attachment-2 text-muted" 
                                                               data-bs-toggle="tooltip" 
                                                               data-bs-placement="top" 
                                                               title="{{ $tarefa->arquivos->count() }} arquivo(s) anexado(s)"></i>
                                                        @endif
                                                    </h5>
                                                    @if($tarefa->descricao)
                                                        <p class="text-muted fs-12 mb-0 @if($tarefa->status === 'concluido') text-decoration-line-through @endif">
                                                            {{ Str::limit($tarefa->descricao, 50) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $tarefa->cliente->razao_social }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @foreach($tarefa->responsaveis as $responsavel)
                                                    <div class="avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $responsavel->name }}">
                                                        @if($responsavel->profile_photo_url)
                                                            <img src="{{ $responsavel->profile_photo_url }}" alt="{{ $responsavel->name }}" class="rounded-circle">
                                                        @else
                                                            <div class="avatar-title rounded-circle bg-light text-primary">
                                                                {{ substr($responsavel->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
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
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Comentários">
                                                    <a href="javascript:void(0);" wire:click="showComentarios({{ $tarefa->id }})" class="text-primary d-inline-block">
                                                        <i class="ri-chat-3-line fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Editar">
                                                    <a href="javascript:void(0);" wire:click="editTarefa({{ $tarefa->id }})" class="text-primary d-inline-block">
                                                        <i class="ri-pencil-line fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Excluir">
                                                    <a class="text-danger d-inline-block" wire:click="confirmDelete({{ $tarefa->id }})" role="button">
                                                        <i class="ri-delete-bin-5-line fs-16"></i>
                                                    </a>
                                                </li>
                                            </ul>
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
<!-- Modal de Comentários -->
@livewire('tarefas.comentarios-modal', ['tarefa_id' => null], key('comentarios-modal'))
</div>
