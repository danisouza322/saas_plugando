<div>
    <div class="container-fluid">
        <!-- Título -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Cadastro de Cliente</h4>
            </div>
        </div>

        <!-- Formulário -->
        <form wire:submit.prevent="submit">
            <div class="row">
                <!-- Razão Social -->
                <div class="col-md-6 mb-3">
                    <label for="razao_social" class="form-label">Razão Social</label>
                    <input type="text" id="razao_social" class="form-control" wire:model.defer="razao_social">
                    @error('razao_social') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Nome Fantasia -->
                <div class="col-md-6 mb-3">
                    <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                    <input type="text" id="nome_fantasia" class="form-control" wire:model.defer="nome_fantasia">
                    @error('nome_fantasia') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Regime Tributário -->
                <div class="col-md-6 mb-3">
                    <label for="regime_tributario" class="form-label">Regime Tributário</label>
                    <select id="regime_tributario" class="form-select" wire:model.defer="regime_tributario">
                        <option value="">Selecione</option>
                        <option value="1">Simples Nacional</option>
                        <option value="2">Lucro Presumido</option>
                        <option value="3">Lucro Real</option>
                    </select>
                    @error('regime_tributario') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Data de Abertura -->
                <div class="col-md-6 mb-3">
                    <label for="data_abertura" class="form-label">Data de Abertura</label>
                    <input type="date" id="data_abertura" class="form-control" wire:model.defer="data_abertura">
                    @error('data_abertura') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Porte -->
                <div class="col-md-6 mb-3">
                    <label for="porte" class="form-label">Porte</label>
                    <select id="porte" class="form-select" wire:model.defer="porte">
                        <option value="">Selecione</option>
                        <option value="MEI">MEI</option>
                        <option value="Microempresa">Microempresa</option>
                        <option value="Pequeno Porte">Empresa de Pequeno Porte</option>
                        <option value="Médio Porte">Médio Porte</option>
                        <option value="Grande Porte">Grande Porte</option>
                    </select>
                    @error('porte') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Capital Social -->
                <div class="col-md-6 mb-3">
                    <label for="capital_social" class="form-label">Capital Social</label>
                    <input type="number" step="0.01" id="capital_social" class="form-control" wire:model.defer="capital_social">
                    @error('capital_social') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Natureza Jurídica -->
                <div class="col-md-6 mb-3">
                    <label for="natureza_juridica" class="form-label">Natureza Jurídica</label>
                    <input type="text" id="natureza_juridica" class="form-control" wire:model.defer="natureza_juridica">
                    @error('natureza_juridica') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Tipo -->
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <input type="text" id="tipo" class="form-control" wire:model.defer="tipo">
                    @error('tipo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Situação Cadastral -->
                <div class="col-md-6 mb-3">
                    <label for="situacao_cadastral" class="form-label">Situação Cadastral</label>
                    <input type="text" id="situacao_cadastral" class="form-control" wire:model.defer="situacao_cadastral">
                    @error('situacao_cadastral') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Endereço -->
                <div class="col-12">
                    <h5 class="mt-4">Endereço</h5>
                </div>

                <!-- Rua -->
                <div class="col-md-6 mb-3">
                    <label for="rua" class="form-label">Rua</label>
                    <input type="text" id="rua" class="form-control" wire:model.defer="rua">
                    @error('rua') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Número -->
                <div class="col-md-6 mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" id="numero" class="form-control" wire:model.defer="numero">
                    @error('numero') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Bairro -->
                <div class="col-md-6 mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" id="bairro" class="form-control" wire:model.defer="bairro">
                    @error('bairro') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Cidade -->
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" id="cidade" class="form-control" wire:model.defer="cidade">
                    @error('cidade') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Estado -->
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" class="form-select" wire:model.defer="estado">
                        <option value="">Selecione</option>
                        <!-- Adicione aqui as opções de estados -->
                        <option value="SP">São Paulo</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <!-- ... -->
                    </select>
                    @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Município IBGE -->
                <div class="col-md-6 mb-3">
                    <label for="municipio_ibge" class="form-label">Município IBGE</label>
                    <input type="number" id="municipio_ibge" class="form-control" wire:model.defer="municipio_ibge">
                    @error('municipio_ibge') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Botões -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('painel.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
