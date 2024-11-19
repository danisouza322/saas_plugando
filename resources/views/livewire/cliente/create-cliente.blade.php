<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Novo Cliente</h4>
                <div class="flex-shrink-0">
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="live-preview">
                    <form wire:submit.prevent="submit" class="row g-3">
                        <div class="col-md-4">
                            <label for="cpf_cnpj" class="form-label">CNPJ:</label>
                            <div class="input-group">
                                <input type="text" class="form-control border-dashed @error('cpf_cnpj') is-invalid @enderror" id="cpf_cnpj" wire:model="cpf_cnpj">
                                <button class="btn btn-outline-secondary" type="button" wire:click="buscarDadosCNPJ" wire:loading.attr="disabled" wire:target="buscarDadosCNPJ">
                                    <span wire:loading.remove wire:target="buscarDadosCNPJ">Buscar Dados</span>
                                    <span wire:loading wire:target="buscarDadosCNPJ">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Buscando...
                                    </span>
                                </button>
                            </div>
                            @error('cpf_cnpj')
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
                        <div class="col-md-4">
                            <label for="nome_fantasia" class="form-label">Nome Fantasia:</label>
                            <input type="text" class="form-control border-dashed @error('nome_fantasia') is-invalid @enderror" id="nome_fantasia" wire:model="nome_fantasia">
                            @error('nome_fantasia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="nome_fantasia" class="form-label">Porte:</label>
                            <input type="text" class="form-control border-dashed @error('porte') is-invalid @enderror" id="porte" wire:model="porte">
                            @error('porte')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="natureza_juridica" class="form-label">Natureza Jurídica:</label>
                            <input type="text" class="form-control border-dashed @error('natureza_juridica') is-invalid @enderror" id="natureza_juridica" wire:model="natureza_juridica">
                            @error('natureza_juridica')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="simples" class="form-label">Simples Nacional:</label>
                            <input type="text" class="form-control-plaintext @error('simples') is-invalid @enderror" id="simples" wire:model="simples">
                            @error('simples')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label for="mei" class="form-label">Mei:</label>
                            <input type="text" class="form-control-plaintext @error('mei') is-invalid @enderror" id="mei" wire:model="mei">
                            @error('mei')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">  
                        <label for="mei" class="form-label">Regime Tributário:</label>
                            <select class="form-select border-dashed @error('regime_tributario') is-invalid @enderror" id="regime_tributario" wire:model="regime_tributario"" aria-label="Default select example">
                                <option selected value="">Selecione o Regime Tributário</option>
                                <option value="simples_nacional">Simples Nacional</option>
                                <option value="mei">Mei</option>
                                <option value="lucro_presumido">Lucro Presumido</option>
                            </select>
                            @error('regime_tributario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="fs-15 mt-3">Endereço</div>
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
                            <input type="text" class="form-control border-dashed @error('rua') is-invalid @enderror" id="rua" wire:model="rua">
                            @error('rua')
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

                        <div class="col-md-12">
                            <div class="fs-15 mt-3">Inscrições Estaduais</div>
                        </div>
                        @foreach($inscricoesEstaduais as $index => $inscricao)

                            <div class="col-md-2 mb-3">
                                <label for="inscricoesEstaduais_{{ $index }}_estado" class="form-label">Estado:</label>
                                <input type="text" id="inscricoesEstaduais_{{ $index }}_estado"
                                    class="form-control border-dashed @error('inscricoesEstaduais.'.$index.'.estado') is-invalid @enderror"
                                    wire:model="inscricoesEstaduais.{{ $index }}.estado">
                                @error('inscricoesEstaduais.'.$index.'.estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="inscricoesEstaduais_{{ $index }}_numero" class="form-label">Número:</label>
                                <input type="text" id="inscricoesEstaduais_{{ $index }}_numero"
                                    class="form-control border-dashed @error('inscricoesEstaduais.'.$index.'.numero') is-invalid @enderror"
                                    wire:model="inscricoesEstaduais.{{ $index }}.numero">
                                @error('inscricoesEstaduais.'.$index.'.numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                               
                            <div class="col-md-3 mb-3">
                                <label for="inscricoesEstaduais_{{ $index }}_ativa" class="form-label">Ativa:</label>
                                <input type="text" id="inscricoesEstaduais_{{ $index }}_ativa"
                                    class="form-control border-dashed @error('inscricoesEstaduais.'.$index.'.ativa') is-invalid @enderror"
                                    wire:model="inscricoesEstaduais.{{ $index }}.ativa">
                                @error('inscricoesEstaduais.'.$index.'.ativa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="inscricoesEstaduais_{{ $index }}_status_texto" class="form-label">Status:</label>
                                <input type="text" id="inscricoesEstaduais_{{ $index }}_status_texto"
                                    class="form-control border-dashed @error('inscricoesEstaduais.'.$index.'.status_texto') is-invalid @enderror"
                                    wire:model="inscricoesEstaduais.{{ $index }}.status_texto">
                                @error('inscricoesEstaduais.'.$index.'.status_texto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                        <div class="col-md-12">
                            <label for="atividadesEconomicas" class="form-label">Atividades Econômicas:</label>
                            <textarea id="atividadesEconomicas" class="form-control @error('atividadesEconomicas') is-invalid @enderror" wire:model="atividadesEconomicas" rows="5"></textarea>
                            @error('atividadesEconomicas') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Cadastrar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
    
    <script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-masks.init.js') }}"></script>
</div>