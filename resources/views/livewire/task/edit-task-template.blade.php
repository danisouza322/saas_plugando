<!-- resources/views/livewire/task/edit-task.blade.php -->

<div>
    <!-- Modal do Velzon (Sempre presente no DOM) -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent="updateTask">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Editar Tarefa</h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Título da Tarefa -->
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" id="titulo" class="form-control @error('titulo') is-invalid @enderror" wire:model="titulo">
                            @error('titulo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Descrição da Tarefa -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea id="descricao" class="form-control @error('descricao') is-invalid @enderror" wire:model="descricao"></textarea>
                            @error('descricao') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tipo de Tarefa -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select id="tipo" class="form-select @error('tipo') is-invalid @enderror" wire:model="tipo">
                                <option value="ad-hoc">Avulsa</option>
                                <option value="monthly">Mensal</option>
                            </select>
                            @error('tipo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Data de Vencimento -->
                        <div class="mb-3">
                            <label for="data_de_vencimento" class="form-label">Data de Vencimento</label>
                            <input type="date" id="data_de_vencimento" class="form-control @error('data_de_vencimento') is-invalid @enderror" wire:model="data_de_vencimento">
                            @error('data_de_vencimento') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cliente Associado -->
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select id="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" wire:model="cliente_id">
                                <option value="">Selecione um cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                            @error('cliente_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status da Tarefa -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                <option value="pending">Pendente</option>
                                <option value="completed">Concluída</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Atribuir Usuários -->
                        <div class="mb-3">
                            <label for="user_ids" class="form-label">Atribuir a Usuários</label>
                            <select id="user_ids" class="form-select select2 @error('user_ids') is-invalid @enderror" wire:model="user_ids" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_ids') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Atualizar Tarefa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>