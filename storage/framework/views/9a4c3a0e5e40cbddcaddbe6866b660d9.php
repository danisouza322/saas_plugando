<div>
    <div class="row">
                        <div class="col-xl-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Clientes</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo e(route('painel.clientes.index')); ?>">Clientes</a></li>
                                        <li class="breadcrumb-item active">lista Clientes</li>
                                    </ol>
                                </div>

                            </div>
                                <!-- Mensagem de Sucesso -->
                            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo e(session('message')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <div class="card">
                                <div class="card-header border-0">
                                <div class="row g-4">
                                            <div class="col-sm-auto">
                                                <div>
                                                    <a href="<?php echo e(route('painel.clientes.create')); ?>" class="btn btn-primary waves-light "><i class="ri-add-line align-bottom me-1"></i>Novo Cliente</a>
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
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('razao_social')">
                                                            Razão Social
                                                            <?php echo $__env->make('livewire.partials._sort-icon', ['field' => 'razao_social'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </a>
                                                    </th>
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('cpf_cnpj')">
                                                            CNPJ
                                                            <?php echo $__env->make('livewire.partials._sort-icon', ['field' => 'cpf_cnpj'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </a>
                                                    </th>
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('inscricao_estadual')">
                                                            Inscrição Estadual
                                                            <?php echo $__env->make('livewire.partials._sort-icon', ['field' => 'inscricao_estadual'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </a>
                                                    </th>
                                                    <th scope="col">
                                                        <a href="#" wire:click.prevent="sortBy('regime_tributario')">
                                                            Regime Tributário
                                                            <?php echo $__env->make('livewire.partials._sort-icon', ['field' => 'regime_tributario'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </a>
                                                    </th>
                                                    <th scope="col">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($cliente->razao_social); ?></td>
                                                    <td><?php echo e($cliente->cnpj); ?></td>
                                                    <td><?php echo e($cliente->inscricaoEstadualAtiva->numero ?? 'Insento/Inativo'); ?></td>
                                                    <td><?php echo e($cliente->regime_tributario_label); ?></td>
                                                    <td>
                                                    <a href="<?php echo e(route('painel.clientes.edit', ['clienteId' => $cliente->id])); ?>" class="btn btn-sm btn-primary">Editar</a>
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="<?php echo e($cliente->id); ?>">Excluir</button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">Nenhum cliente encontrado.</td>
                                                </tr>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                         </tbody>
                                    </table>
                                        </div>
                                         <!-- Paginação -->
                            <div class="mt-3">
                                <?php echo e($clientes->links()); ?>

                            </div>
                                    <div class="code-view d-none">
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div>
</div>
<?php /**PATH C:\laragon\www\saas_plugando\resources\views/livewire/cliente/index-clientes.blade.php ENDPATH**/ ?>