<div>
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dados da Empresa</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informações da Empresa</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="salvar">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="razao_social" class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" wire:model="razao_social" id="razao_social">
                                    @error('razao_social') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="cnpj" class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" wire:model="cnpj" id="cnpj">
                                    @error('cnpj') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" wire:model="email" id="email">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" wire:model="telefone" id="telefone">
                                    @error('telefone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" wire:model="cep" id="cep">
                                    @error('cep') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="endereco" class="form-label">Endereço</label>
                                    <input type="text" class="form-control" wire:model="endereco" id="endereco">
                                    @error('endereco') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-lg-2">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="text" class="form-control" wire:model="numero" id="numero">
                                    @error('numero') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" wire:model="complemento" id="complemento">
                                </div>
                                <div class="col-lg-4">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" wire:model="bairro" id="bairro">
                                    @error('bairro') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" wire:model="cidade" id="cidade">
                                    @error('cidade') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" wire:model="estado" id="estado">
                                        <option value="">Selecione...</option>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                    @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
