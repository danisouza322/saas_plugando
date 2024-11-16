<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="modal fade" id="certificadoModal" tabindex="-1" aria-labelledby="certificadoModalLabel" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certificadoModalLabel">
                        {{ $certificadoId ? 'Editar Certificado' : 'Novo Certificado' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="arquivo" class="form-label">Arquivo do Certificado</label>
                            <input type="file" class="form-control" id="arquivo" wire:model="arquivo" accept=".pfx">
                            @error('arquivo') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="text-muted">Selecione um arquivo .pfx</small>
                        </div>

                        @if($arquivo)
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha do Certificado</label>
                            <div class="input-group">
                                <input type="password" class="form-control {{ $senhaInvalida ? 'is-invalid' : '' }}" 
                                    id="senha" 
                                    wire:model.live.debounce.2000ms="certificado.senha" 
                                    placeholder="Digite a senha para ler o certificado"
                                    {{ $tentarLerCertificado ? 'readonly' : '' }}>
                                <div class="input-group-text" wire:loading wire:target="certificado.senha">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Carregando...</span>
                                    </div>
                                </div>
                            </div>
                            @if($senhaInvalida)
                            <div class="invalid-feedback d-block">
                                Senha do certificado inválida
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Cliente</label>
                            <input type="text" class="form-control" id="nome" wire:model="certificado.nome" {{ !$arquivo ? 'readonly' : '' }}>
                            @error('certificado.nome') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Documento</label>
                            <select class="form-select" id="tipo" wire:model="certificado.tipo" {{ !$arquivo ? 'disabled' : '' }}>
                                <option value="">Selecione o tipo</option>
                                <option value="CPF">CPF</option>
                                <option value="CNPJ">CNPJ</option>
                            </select>
                            @error('certificado.tipo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cnpj_cpf" class="form-label">{{ $certificado['tipo'] ?: 'CPF/CNPJ' }}</label>
                            <input type="text" class="form-control" id="cnpj_cpf" 
                                wire:model.live="certificado.cnpj_cpf" 
                                {{ !$arquivo ? 'readonly' : '' }}
                                maxlength="{{ $certificado['tipo'] === 'CPF' ? '11' : '14' }}"
                                placeholder="{{ $certificado['tipo'] === 'CPF' ? '000.000.000-00' : '00.000.000/0000-00' }}">
                            @error('certificado.cnpj_cpf') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                            <input type="date" class="form-control" id="data_vencimento" wire:model="certificado.data_vencimento" {{ !$arquivo ? 'readonly' : '' }}>
                            @error('certificado.data_vencimento') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('certificadoSaved', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('certificadoModal'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
    @endpush
</div>
