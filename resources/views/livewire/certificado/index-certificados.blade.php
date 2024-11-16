<div>
    <div class="row">
        <div class="col-xl-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Certificados</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('painel.certificados.index')}}">Certificados</a></li>
                        <li class="breadcrumb-item active">Lista de Certificados</li>
                    </ol>
                </div>
            </div>

            <!-- Mensagem de Sucesso -->
            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-header border-0">
                    <div class="row g-4">
                        <div class="col-sm-auto">
                            <div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#certificadoModal" wire:click="createCertificado">
                                    <i class="ri-add-line align-bottom me-1"></i>Novo Certificado
                                </button>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-striped-columns mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Nome do Cliente</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Vencimento</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($certificados as $certificado)
                                <tr>
                                    <td>{{ $certificado->nome }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $certificado->tipo }}</span>
                                        {{ $certificado->tipo === 'CPF' 
                                            ? substr($certificado->cnpj_cpf, 0, 3) . '.' . substr($certificado->cnpj_cpf, 3, 3) . '.' . substr($certificado->cnpj_cpf, 6, 3) . '-' . substr($certificado->cnpj_cpf, 9)
                                            : substr($certificado->cnpj_cpf, 0, 2) . '.' . substr($certificado->cnpj_cpf, 2, 3) . '.' . substr($certificado->cnpj_cpf, 5, 3) . '/' . substr($certificado->cnpj_cpf, 8, 4) . '-' . substr($certificado->cnpj_cpf, 12)
                                        }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($certificado->data_vencimento)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="hstack gap-3 flex-wrap">
                                            <button type="button" class="link-success fs-15" wire:click="editCertificado({{ $certificado->id }})" data-bs-toggle="modal" data-bs-target="#certificadoModal">
                                                <i class="ri-edit-2-line"></i>
                                            </button>
                                            @if($certificado->arquivo_path)
                                            <a href="{{ asset('storage/' . $certificado->arquivo_path) }}" class="link-info fs-15" download>
                                                <i class="ri-download-2-line"></i>
                                            </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $certificado->id }}">Excluir</button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Nenhum certificado encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $certificados->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewire('certificado.certificado-modal')

    @push('scripts')
    @endpush
</div>
