<div>
    <div class="container-fluid">
        <!-- Mensagens de sucesso/erro -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">{{ $titulo }}</h1>
            <div class="d-flex gap-2">
                <input wire:model.live="search" type="search" class="form-control" placeholder="Buscar empresas...">
            </div>
        </div>

        <!-- Tabela de Empresas -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Usuários</th>
                                <th>Plano</th>
                                <th>Status</th>
                                <th>Data de Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($empresas as $empresa)
                                <tr>
                                    <td>{{ $empresa->id }}</td>
                                    <td>{{ $empresa->nome }}</td>
                                    <td>{{ $empresa->users_count }}</td>
                                    <td>{{ ucfirst($empresa->plano) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $empresa->ativo ? 'success' : 'danger' }}">
                                            {{ $empresa->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>{{ $empresa->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button wire:click="toggleStatus({{ $empresa->id }})" 
                                                    class="btn btn-sm btn-{{ $empresa->ativo ? 'warning' : 'success' }}"
                                                    @if($empresa->id == 1) disabled @endif>
                                                {{ $empresa->ativo ? 'Desativar' : 'Ativar' }}
                                            </button>
                                            <button wire:click="confirmDelete({{ $empresa->id }})" 
                                                    class="btn btn-sm btn-danger"
                                                    @if($empresa->id == 1) disabled @endif>
                                                Excluir
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $empresas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="deleteEmpresa()">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let empresaIdToDelete = null;

        window.addEventListener('showDeleteConfirmation', event => {
            empresaIdToDelete = event.detail.empresaId;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });

        window.addEventListener('refresh', event => {
            window.location.reload();
        });

        function deleteEmpresa() {
            if (empresaIdToDelete) {
                @this.deleteEmpresa(empresaIdToDelete);
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
            }
        }
    </script>
    @endpush
</div>
