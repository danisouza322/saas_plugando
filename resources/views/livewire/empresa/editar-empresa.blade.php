<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Atualizar Empresa</h4>
                <div class="flex-shrink-0">
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="live-preview">
                    <form wire:submit.prevent="atualizar" class="row g-3">
                        <div class="col-md-4">
                            <label for="Cnpj" class="form-label">Cnpj</label>
                            <div class="input-group">
                                <input type="text" class="form-control border-dashed @error('cnpj') is-invalid @enderror" id="cnpj" wire:model="cnpj">
                                <button class="btn btn-outline-secondary" type="button" wire:click="buscarDadosCNPJ" wire:loading.attr="disabled" wire:target="buscarDadosCNPJ">
                                    <span wire:loading.remove wire:target="buscarDadosCNPJ">Buscar Dados</span>
                                    <span wire:loading wire:target="buscarDadosCNPJ">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Buscando...
                                    </span>
                                </button>
                            </div>
                            @error('cnpj')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control border-dashed @error('nome') is-invalid @enderror" id="nome" wire:model="nome">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="razao_social" class="form-label">Razão Social</label>
                            <input type="text" class="form-control border-dashed @error('razao_social') is-invalid @enderror" id="razao_social" wire:model="razao_social">
                            @error('razao_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit form</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
    <script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-masks.init.js') }}"></script>
</div>