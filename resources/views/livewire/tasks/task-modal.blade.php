<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" 
        aria-hidden="true" wire:ignore.self wire:init="init">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">
                        {{ $taskId ? 'Editar Tarefa' : 'Nova Tarefa' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="hideModal" aria-label="Close"></button>
                </div>
                <form wire:submit="save" id="taskForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="title" class="form-label">Título</label>
                                    <input type="text" id="title" class="form-control" wire:model="title"
                                        placeholder="Digite o título da tarefa" @if($viewOnly) readonly @endif>
                                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div>
                                    <label for="description" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="description" rows="3" wire:model="description"
                                        placeholder="Digite a descrição da tarefa" @if($viewOnly) readonly @endif></textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="taskType" class="form-label">Tipo de Tarefa</label>
                                    <select wire:model="taskType" id="taskType" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="">Selecione o Tipo</option>
                                        @foreach($taskTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('taskType') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="cliente" class="form-label">Cliente</label>
                                    <select wire:model="cliente" id="cliente" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="">Selecione o Cliente</option>
                                        @foreach($clientes as $c)
                                            <option value="{{ $c->id }}">{{ $c->razao_social }}</option>
                                        @endforeach
                                    </select>
                                    @error('cliente') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label d-block">Usuários Designados</label>
                                    <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($users as $user)
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input" 
                                                       id="user_{{ $user->id }}"
                                                       wire:model="selectedUsers"
                                                       value="{{ $user->id }}"
                                                       @if($viewOnly) disabled @endif>
                                                <label class="form-check-label" for="user_{{ $user->id }}">
                                                    {{ $user->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('selectedUsers')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Selecione um ou mais usuários responsáveis pela tarefa</small>
                                </div>
                            </div>

                            @if(!$viewOnly)
                            <div class="col-lg-6">
                                <div>
                                    <label for="estimatedMinutes" class="form-label">Tempo Estimado (minutos)</label>
                                    <input type="number" id="estimatedMinutes" class="form-control" wire:model="estimatedMinutes"
                                        placeholder="Digite o tempo estimado">
                                    @error('estimatedMinutes') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif

                            <div class="col-lg-6">
                                <div>
                                    <label for="dueDate" class="form-label">Data de Entrega</label>
                                    <input type="date" id="dueDate" class="form-control" wire:model="dueDate" @if($viewOnly) readonly @endif>
                                    @error('dueDate') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="task_status" class="form-label">Status</label>
                                    <select wire:model="status" id="task_status" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="pending">Pendente</option>
                                        <option value="in_progress">Em Andamento</option>
                                        <option value="completed">Concluída</option>
                                        <option value="delayed">Atrasada</option>
                                        <option value="cancelled">Cancelada</option>
                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="priority" class="form-label">Prioridade</label>
                                    <select wire:model="priority" id="priority" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="">Selecione a Prioridade</option>
                                        <option value="low">Baixa</option>
                                        <option value="medium">Média</option>
                                        <option value="high">Alta</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                    @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if(!$viewOnly)
                            <button type="submit" class="btn btn-primary" id="saveTaskBtn">
                                <i class="bi bi-save me-2"></i>Salvar
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="hideModal">
                            @if($viewOnly)
                                <i class="bi bi-x-circle me-2"></i>Fechar
                            @else
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const taskForm = document.getElementById('taskForm');
            const saveTaskBtn = document.getElementById('saveTaskBtn');
            let taskModal = null;

            // Inicializa o modal
            function initModal() {
                const modalElement = document.getElementById('taskModal');
                if (modalElement) {
                    taskModal = new bootstrap.Modal(modalElement);
                    
                    // Limpa o backdrop quando o modal é fechado
                    modalElement.addEventListener('hidden.bs.modal', () => {
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                        document.body.classList.remove('modal-open');
                        document.body.style.paddingRight = '';
                    });
                }
            }

            initModal();

            if (taskForm) {
                taskForm.addEventListener('submit', (e) => {
                    console.log('Form submitted');
                    if (saveTaskBtn) {
                        saveTaskBtn.disabled = true;
                        saveTaskBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
                    }
                });
            }

            @this.on('task-saved', () => {
                console.log('Task saved event received');
                if (saveTaskBtn) {
                    saveTaskBtn.disabled = false;
                    saveTaskBtn.innerHTML = '{{ $taskId ? 'Atualizar' : 'Criar' }} Tarefa';
                }
                if (taskModal) {
                    taskModal.hide();
                }
            });

            @this.on('show-task-modal', () => {
                console.log('Show modal event received');
                if (taskModal) {
                    taskModal.show();
                }
            });

            @this.on('hide-task-modal', () => {
                console.log('Hide modal event received');
                if (taskModal) {
                    taskModal.hide();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listener para fechar o modal
            @this.on('close-task-modal', () => {
                const modalEl = document.getElementById('taskModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            });

            // Listener para atualizar a lista de tarefas
            @this.on('task-saved', () => {
                // Atualiza a lista de tarefas
                Livewire.dispatch('refresh-tasks');
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('hideModal', function () {
                // Supondo que você está usando Bootstrap 5
                var myModalEl = document.getElementById('taskModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('notify', (data) => {
                console.log('Notification:', data);
                // Aqui você pode adicionar uma biblioteca de notificações como Toastr ou SweetAlert2
                if (window.Toastr) {
                    Toastr[data.type](data.message);
                } else {
                    alert(data.message);
                }
            });
        });
    </script>
    @endpush
</div>
