<div>
    @if($isOpen)
        <div class="modal fade show d-block" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form wire:submit.prevent="createTaskTemplate">
                        <div class="modal-header">
                            <h5 class="modal-title">Criar Novo Modelo de Tarefa</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Título do Modelo -->
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" id="titulo" class="form-control @error('titulo') is-invalid @enderror" wire:model="titulo">
                                @error('titulo') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Descrição do Modelo -->
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea id="descricao" class="form-control @error('descricao') is-invalid @enderror" wire:model="descricao"></textarea>
                                @error('descricao') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Usuário Responsável -->
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Usuário Responsável</label>
                                <select id="user_id" class="form-select @error('user_id') is-invalid @enderror" wire:model="user_id">
                                    <option value="">Selecione um usuário</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
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

                            <!-- Frequência -->
                            <div class="mb-3">
                                <label for="frequencia" class="form-label">Frequência</label>
                                <select id="frequencia" class="form-select @error('frequencia') is-invalid @enderror" wire:model="frequencia">
                                    <option value="monthly">Mensal</option>
                                </select>
                                @error('frequencia') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <!-- Dia do Mês -->
                            <div class="mb-3">
                                <label for="dia_do_mes" class="form-label">Dia do Mês</label>
                                <input type="number" id="dia_do_mes" class="form-control @error('dia_do_mes') is-invalid @enderror" wire:model="dia_do_mes" min="1" max="31">
                                @error('dia_do_mes') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Criar Modelo</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Fundo da Modal -->
            <div class="modal-backdrop fade show"></div>
        </div>
    @endif
</div>
