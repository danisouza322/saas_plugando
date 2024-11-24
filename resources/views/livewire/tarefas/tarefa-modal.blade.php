<div>
    <div wire:ignore.self class="modal fade" id="createTask" tabindex="-1" aria-labelledby="createTaskLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-3 bg-success-subtle">
                    <h5 class="modal-title" id="createTaskLabel">{{ $tarefa_id ? 'Editar' : 'Nova' }} Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeTaskModal"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" wire:model="tarefa.titulo" class="form-control" placeholder="Digite o título da tarefa">
                                @error('tarefa.titulo') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select wire:model="tarefa.cliente_id" class="form-select">
                                    <option value="">Selecione o cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->razao_social }}</option>
                                    @endforeach
                                </select>
                                @error('tarefa.cliente_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea wire:model="tarefa.descricao" class="form-control" rows="3" placeholder="Digite a descrição da tarefa"></textarea>
                                @error('tarefa.descricao') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-6">
                                <label for="status" class="form-label">Status</label>
                                <select wire:model="tarefa.status" class="form-select">
                                    <option value="novo">Novo</option>
                                    <option value="em_andamento">Em Andamento</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="concluido">Concluído</option>
                                </select>
                                @error('tarefa.status') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-6">
                                <label for="prioridade" class="form-label">Prioridade</label>
                                <select wire:model="tarefa.prioridade" class="form-select">
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                </select>
                                @error('tarefa.prioridade') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                                <input type="date" wire:model="tarefa.data_vencimento" class="form-control">
                                @error('tarefa.data_vencimento') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="responsaveis" class="form-label">Responsáveis</label>
                                <select wire:model="responsaveis" class="form-select" multiple>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endforeach
                                </select>
                                @error('responsaveis') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="arquivos" class="form-label">Arquivos</label>
                                <div class="mb-3">
                                    <input type="file" wire:model.live="arquivos" class="form-control" multiple>
                                    
                                    <div class="mt-2">
                                        @if(count($arquivos ?? []) > 0)
                                            <small class="text-muted">{{ count($arquivos) }} arquivo(s) selecionado(s)</small>
                                        @endif
                                        
                                        <div wire:loading wire:target="arquivos">
                                            <div class="d-flex align-items-center text-primary">
                                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                                    <span class="visually-hidden">Carregando arquivos...</span>
                                                </div>
                                                <small>Processando arquivos...</small>
                                            </div>
                                        </div>
                                        
                                        <div wire:loading wire:target="save">
                                            <div class="d-flex align-items-center text-primary">
                                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                                    <span class="visually-hidden">Salvando...</span>
                                                </div>
                                                <small>Salvando tarefa...</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('arquivos.*') 
                                        <div class="mt-1">
                                            <span class="text-danger">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                @if($tarefa_id && count($arquivos_existentes) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-nowrap align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Arquivo</th>
                                                    <th scope="col">Tamanho</th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($arquivos_existentes as $arquivo)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="ri-file-line fs-16 align-middle text-primary me-2"></i>
                                                                <span class="text-body">{{ $arquivo->nome_arquivo }}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{ number_format($arquivo->tamanho / 1024, 2) }} KB</td>
                                                        <td>
                                                            <div class="hstack gap-2">
                                                                <a href="{{ Storage::disk('public')->url($arquivo->caminho_arquivo) }}" 
                                                                   class="btn btn-sm btn-soft-primary"
                                                                   download="{{ $arquivo->nome_arquivo }}"
                                                                   target="_blank">
                                                                    <i class="ri-download-2-line"></i>
                                                                </a>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-soft-danger"
                                                                        wire:click="deleteArquivo({{ $arquivo->id }})"
                                                                        wire:confirm="Tem certeza que deseja excluir este arquivo?">
                                                                    <i class="ri-delete-bin-2-line"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">{{ $tarefa_id ? 'Atualizar' : 'Criar' }} Tarefa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Inicializa o Select2
            if (typeof jQuery !== 'undefined') {
                $('.select2').select2({
                    theme: 'bootstrap-5'
                });
            }

            // Fecha o modal
            @this.on('close-modal', () => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('createTask'));
                if (modal) {
                    modal.hide();
                }
            });

            // Gerencia notificações
            @this.on('success', (message) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: message,
                    showConfirmButton: false,
                    timer: 1500
                });
            });

            @this.on('error', (message) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: message
                });
            });
        });
    </script>
    @endpush
</div>
