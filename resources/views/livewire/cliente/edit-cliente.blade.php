<div>
    <div class="container-fluid">
        <!-- Título -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Editar Cliente</h4>
            </div>
        </div>

        <!-- Formulário -->
        <form wire:submit.prevent="submit">
            <div class="row">
                <!-- Razão Social -->
                <div class="col-md-6 mb-3">
                    <label for="razao_social" class="form-label">Razão Social</label>
                    <input type="text" id="razao_social" class="form-control" wire:model.defer="cliente.razao_social">
                    @error('cliente.razao_social') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Nome Fantasia -->
                <div class="col-md-6 mb-3">
                    <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                    <input type="text" id="nome_fantasia" class="form-control" wire:model.defer="cliente.nome_fantasia">
                    @error('cliente.nome_fantasia') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Repita os demais campos do cliente conforme necessário -->

                <!-- Endereço -->
                <div class="col-12">
                    <h5 class="mt-4">Endereço</h5>
                </div>

                <!-- Rua -->
                <div class="col-md-6 mb-3">
                    <label for="rua" class="form-label">Rua</label>
                    <input type="text" id="rua" class="form-control" wire:model.defer="endereco.rua">
                    @error('endereco.rua') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Repita os demais campos de endereço conforme necessário -->

                <!-- Botões -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <a href="{{ route('painel.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
