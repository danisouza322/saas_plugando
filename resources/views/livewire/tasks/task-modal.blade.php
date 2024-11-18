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
                        @if(app()->environment('local'))
                        <div class="alert alert-info">
                            <p>Debug Info:</p>
                            <p>Total Clientes: {{ isset($clientes) ? $clientes->count() : 0 }}</p>
                            <p>Total Usuários: {{ isset($users) ? $users->count() : 0 }}</p>
                            <p>Empresa ID: {{ Session::get('empresa_id') }}</p>
                            <p>Form Data:</p>
                            <ul>
                                <li>Title: {{ $title }}</li>
                                <li>Task Type: {{ $taskType }}</li>
                                <li>Cliente: {{ $cliente }}</li>
                                <li>Status: {{ $status }}</li>
                                <li>Priority: {{ $priority }}</li>
                                <li>View Only: {{ $viewOnly ? 'Sim' : 'Não' }}</li>
                            </ul>
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="title" class="form-label">Título</label>
                                    <input type="text" id="title" class="form-control" wire:model.live="title"
                                        placeholder="Digite o título da tarefa" @if($viewOnly) readonly @endif>
                                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div>
                                    <label for="description" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="description" rows="3" wire:model.live="description"
                                        placeholder="Digite a descrição da tarefa" @if($viewOnly) readonly @endif></textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="taskType" class="form-label">Tipo de Tarefa</label>
                                    <select wire:model.live="taskType" id="taskType" class="form-select" @if($viewOnly) disabled @endif>
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
                                    <select wire:model.live="cliente" id="cliente" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="">Selecione o Cliente</option>
                                        @foreach($clientes as $c)
                                            <option value="{{ $c->id }}">{{ $c->razao_social }}</option>
                                        @endforeach
                                    </select>
                                    @error('cliente') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="assignedTo" class="form-label">Responsável</label>
                                    <select wire:model.live="assignedTo" id="assignedTo" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="">Selecione o Responsável</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assignedTo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="startDate" class="form-label">Data de Início</label>
                                    <input type="date" id="startDate" class="form-control" wire:model.live="startDate" @if($viewOnly) readonly @endif>
                                    @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="dueDate" class="form-label">Data Limite</label>
                                    <input type="date" id="dueDate" class="form-control" wire:model.live="dueDate" @if($viewOnly) readonly @endif>
                                    @error('dueDate') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="status" class="form-label">Status</label>
                                    <select wire:model.live="status" id="status" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="">Selecione o Status</option>
                                        <option value="pending">🕒 Pendente</option>
                                        <option value="in_progress">▶️ Em Andamento</option>
                                        <option value="completed">✅ Concluída</option>
                                        <option value="delayed">⚠️ Atrasada</option>
                                        <option value="cancelled">❌ Cancelada</option>
                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="priority" class="form-label">Prioridade</label>
                                    <select wire:model.live="priority" id="priority" class="form-select" @if($viewOnly) disabled @endif>
                                        <option value="">Selecione a Prioridade</option>
                                        <option value="low">🟢 Baixa</option>
                                        <option value="medium">🟡 Média</option>
                                        <option value="high">🟠 Alta</option>
                                        <option value="urgent">🔴 Urgente</option>
                                    </select>
                                    @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            @if(!$viewOnly)
                            <div class="col-lg-6">
                                <div>
                                    <label for="estimatedMinutes" class="form-label">Tempo Estimado (minutos)</label>
                                    <input type="number" id="estimatedMinutes" class="form-control" wire:model.live="estimatedMinutes"
                                        placeholder="Digite o tempo estimado">
                                    @error('estimatedMinutes') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="budget" class="form-label">Orçamento</label>
                                    <input type="number" step="0.01" id="budget" class="form-control" wire:model.live="budget"
                                        placeholder="Digite o orçamento">
                                    @error('budget') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="hideModal">Fechar</button>
                        @if(!$viewOnly)
                        <button type="submit" class="btn btn-primary" id="saveTaskBtn">
                            {{ $taskId ? 'Atualizar' : 'Criar' }} Tarefa
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('open-modal', (modalId) => {
                const modal = new bootstrap.Modal(document.getElementById('taskModal'));
                modal.show();
            });
        });
    </script>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            const taskForm = document.getElementById('taskForm');
            const saveTaskBtn = document.getElementById('saveTaskBtn');
            const taskModal = new bootstrap.Modal(document.getElementById('taskModal'));

            if (taskForm) {
                taskForm.addEventListener('submit', (e) => {
                    console.log('Form submitted');
                    saveTaskBtn.disabled = true;
                    saveTaskBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
                });
            }

            @this.on('task-saved', () => {
                console.log('Task saved event received');
                saveTaskBtn.disabled = false;
                saveTaskBtn.innerHTML = '{{ $taskId ? 'Atualizar' : 'Criar' }} Tarefa';
                taskModal.hide();
            });

            @this.on('notify', (data) => {
                console.log('Notification:', data);
                // Aqui você pode adicionar uma biblioteca de notificações como Toastr ou SweetAlert2
                if (window.Toastr) {
                    Toastr[data.type](data.message);
                } else {
                    alert(data.message);
                }
            });

            @this.on('show-task-modal', () => {
                console.log('Show modal event received');
                taskModal.show();
            });

            @this.on('hide-task-modal', () => {
                console.log('Hide modal event received');
                taskModal.hide();
            });
        });
    </script>
    @endpush
</div>
