<div>
    <div class="row">
                        <div class="col-xl-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Clientes</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{route('painel.clientes.index')}}">Clientes</a></li>
                                        <li class="breadcrumb-item active">lista Clientes</li>
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
                                                    <a href="{{route('painel.clientes.create')}}" class="btn btn-primary waves-light "><i class="ri-add-line align-bottom me-1"></i>Novo Cliente</a>
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
                                </div><!-- end card header -->

                                <div class="card-body">
                                        <div class="table-responsive">
                                        <table class="table table-nowrap table-striped-columns mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 50px;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" wire:model="selectAll">
                                                        </div>
                                                    </th>
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('razao_social')">
                                                            Razão Social
                                                            @include('livewire.partials._sort-icon', ['field' => 'razao_social'])
                                                        </a>
                                                    </th>
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('cpf_cnpj')">
                                                            CNPJ
                                                            @include('livewire.partials._sort-icon', ['field' => 'cpf_cnpj'])
                                                        </a>
                                                    </th>
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('inscricao_estadual')">
                                                            Inscrição Estadual
                                                            @include('livewire.partials._sort-icon', ['field' => 'inscricao_estadual'])
                                                        </a>
                                                    </th>
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('regime_tributario')">
                                                            Regime Tributário
                                                            @include('livewire.partials._sort-icon', ['field' => 'regime_tributario'])
                                                        </a>
                                                    </th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($clientes as $cliente)
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" wire:model="selectedClientes" value="{{ $cliente->id }}">
                                                        </div>
                                                    </td>
                                                    <td>{{ $cliente->razao_social }}</td>
                                                    <td>{{ $cliente->cnpj }}</td>
                                                    <td>{{ $cliente->inscricaoEstadualAtiva->numero ?? 'Insento/Inativo' }}</td>
                                                    <td>{{ $cliente->regime_tributario_label }}</td>
                                                    <td>
                                                    <a href="{{ route('painel.clientes.edit', ['clienteId' => $cliente->id]) }}" class="btn btn-sm btn-primary">Editar</a>
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $cliente->id }}">Excluir</button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Nenhum cliente encontrado.</td>
                                                </tr>
                                                @endforelse
                                         </tbody>
                                    </table>
                                        </div>
                                         <!-- Paginação -->
                            <div class="mt-3">
                                {{ $clientes->links() }}
                            </div>
                                    <div class="code-view d-none">
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div>
</div>
