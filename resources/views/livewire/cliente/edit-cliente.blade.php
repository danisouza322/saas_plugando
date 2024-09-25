<div>
    <h1>Editar Cliente</h1>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <!-- Campos do formulário -->
        <div class="row">
            <!-- Razão Social -->
            <div class="col-md-6">
                <label for="razao_social" class="form-label">Razão Social:</label>
                <input type="text" id="razao_social" class="form-control @error('razao_social') is-invalid @enderror" wire:model="razao_social">
                @error('razao_social') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- CNPJ -->
            <div class="col-md-6">
                <label for="cnpj" class="form-label">CNPJ:</label>
                <input type="text" id="cnpj" class="form-control @error('cnpj') is-invalid @enderror" wire:model="cnpj">
                @error('cnpj') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- Botão para buscar dados do CNPJ -->
            <div class="col-md-12 mt-2">
                <button type="button" class="btn btn-secondary" wire:click="buscarDadosCNPJ" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="buscarDadosCNPJ">Buscar Dados do CNPJ</span>
                    <span wire:loading wire:target="buscarDadosCNPJ">Buscando...</span>
                </button>
            </div>

            <!-- Outros campos -->
            <!-- Adicione aqui os demais campos seguindo o mesmo padrão -->

            <!-- Exemplo: Inscrição Estadual -->
            <div class="col-md-6">
                <label for="inscricao_estadual" class="form-label">Inscrição Estadual:</label>
                <input type="text" id="inscricao_estadual" class="form-control @error('inscricao_estadual') is-invalid @enderror" wire:model="inscricao_estadual">
                @error('inscricao_estadual') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- Regime Tributário -->
            <div class="col-md-6">
                <label for="regime_tributario" class="form-label">Regime Tributário:</label>
                <select id="regime_tributario" class="form-control @error('regime_tributario') is-invalid @enderror" wire:model="regime_tributario">
                    <option value="">Selecione</option>
                    <option value="simples_nacional">Simples Nacional</option>
                    <option value="lucro_presumido">Lucro Presumido</option>
                    <option value="lucro_real">Lucro Real</option>
                    <option value="mei">MEI</option>
                </select>
                @error('regime_tributario') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- Adicione os demais campos necessários -->

            <!-- Atividades Econômicas -->
            <div class="col-md-12 mt-3">
                <h4>Atividades Econômicas</h4>
            </div>
            @foreach($atividades as $index => $atividade)
                <div class="col-md-3">
                    <label for="atividades_{{ $index }}_codigo" class="form-label">Código ({{ $atividade['tipo'] }}):</label>
                    <input type="text" id="atividades_{{ $index }}_codigo" class="form-control @error('atividades.'.$index.'.codigo') is-invalid @enderror" wire:model="atividades.{{ $index }}.codigo">
                    @error('atividades.'.$index.'.codigo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-9">
                    <label for="atividades_{{ $index }}_descricao" class="form-label">Descrição ({{ $atividade['tipo'] }}):</label>
                    <input type="text" id="atividades_{{ $index }}_descricao" class="form-control @error('atividades.'.$index.'.descricao') is-invalid @enderror" wire:model="atividades.{{ $index }}.descricao">
                    @error('atividades.'.$index.'.descricao') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endforeach

            <!-- Inscrições Estaduais -->
            <div class="col-md-12 mt-3">
                <h4>Inscrições Estaduais</h4>
            </div>
            @foreach($inscricoesEstaduais as $index => $inscricao)
                <div class="col-md-2">
                    <label for="inscricoesEstaduais_{{ $index }}_estado" class="form-label">Estado:</label>
                    <input type="text" id="inscricoesEstaduais_{{ $index }}_estado" class="form-control @error('inscricoesEstaduais.'.$index.'.estado') is-invalid @enderror" wire:model="inscricoesEstaduais.{{ $index }}.estado">
                    @error('inscricoesEstaduais.'.$index.'.estado') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <label for="inscricoesEstaduais_{{ $index }}_numero" class="form-label">Número:</label>
                    <input type="text" id="inscricoesEstaduais_{{ $index }}_numero" class="form-control @error('inscricoesEstaduais.'.$index.'.numero') is-invalid @enderror" wire:model="inscricoesEstaduais.{{ $index }}.numero">
                    @error('inscricoesEstaduais.'.$index.'.numero') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-2">
                    <label for="inscricoesEstaduais_{{ $index }}_ativa" class="form-label">Ativa:</label>
                    <select id="inscricoesEstaduais_{{ $index }}_ativa" class="form-control @error('inscricoesEstaduais.'.$index.'.ativa') is-invalid @enderror" wire:model="inscricoesEstaduais.{{ $index }}.ativa">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                    @error('inscricoesEstaduais.'.$index.'.ativa') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-2">
                    <label for="inscricoesEstaduais_{{ $index }}_data_status" class="form-label">Data do Status:</label>
                    <input type="date" id="inscricoesEstaduais_{{ $index }}_data_status" class="form-control @error('inscricoesEstaduais.'.$index.'.data_status') is-invalid @enderror" wire:model="inscricoesEstaduais.{{ $index }}.data_status">
                    @error('inscricoesEstaduais.'.$index.'.data_status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <label for="inscricoesEstaduais_{{ $index }}_status_texto" class="form-label">Status:</label>
                    <input type="text" id="inscricoesEstaduais_{{ $index }}_status_texto" class="form-control @error('inscricoesEstaduais.'.$index.'.status_texto') is-invalid @enderror" wire:model="inscricoesEstaduais.{{ $index }}.status_texto">
                    @error('inscricoesEstaduais.'.$index.'.status_texto') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <label for="inscricoesEstaduais_{{ $index }}_tipo_texto" class="form-label">Tipo:</label>
                    <input type="text" id="inscricoesEstaduais_{{ $index }}_tipo_texto" class="form-control @error('inscricoesEstaduais.'.$index.'.tipo_texto') is-invalid @enderror" wire:model="inscricoesEstaduais.{{ $index }}.tipo_texto">
                    @error('inscricoesEstaduais.'.$index.'.tipo_texto') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            @endforeach

            <!-- Botão de Submit -->
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </div>
    </form>
</div>
