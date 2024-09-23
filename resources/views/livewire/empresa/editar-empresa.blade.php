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
                            <label for="Cnpj" class="form-label">CNPJ:</label>
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
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" class="form-control border-dashed @error('nome') is-invalid @enderror" id="nome" wire:model="nome">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="razao_social" class="form-label">Razão Social:</label>
                            <input type="text" class="form-control border-dashed @error('razao_social') is-invalid @enderror" id="razao_social" wire:model="razao_social">
                            @error('razao_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="cep" class="form-label">Cep:</label>
                            <input type="text" class="form-control border-dashed @error('cep') is-invalid @enderror" id="cep" wire:model="cep">
                            @error('cep')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="endereco" class="form-label">Logradouro:</label>
                            <input type="text" class="form-control border-dashed @error('endereco') is-invalid @enderror" id="endereco" wire:model="endereco">
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro:</label>
                            <input type="text" class="form-control border-dashed @error('bairro') is-invalid @enderror" id="bairro" wire:model="bairro">
                            @error('bairro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="numero" class="form-label">Número:</label>
                            <input type="text" class="form-control border-dashed @error('numero') is-invalid @enderror" id="numero" wire:model="numero">
                            @error('numero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="complemento" class="form-label">Complemento:</label>
                            <input type="text" class="form-control border-dashed @error('complemento') is-invalid @enderror" id="complemento" wire:model="complemento">
                            @error('complemento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="bairro" class="form-label">Bairro:</label>
                            <input type="text" class="form-control border-dashed @error('bairro') is-invalid @enderror" id="bairro" wire:model="bairro">
                            @error('bairro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cidade" class="form-label">Cidade:</label>
                            <input type="text" class="form-control border-dashed @error('cidade') is-invalid @enderror" id="cidade" wire:model="cidade">
                            @error('cidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="estado" class="form-label">Estado:</label>
                            <input type="text" class="form-control border-dashed @error('estado') is-invalid @enderror" id="estado" wire:model="estado">
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email:</label>
                            <input type="text" class="form-control border-dashed @error('email') is-invalid @enderror" id="email" wire:model="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Atualizar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
    <script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-masks.init.js') }}"></script>
</div>