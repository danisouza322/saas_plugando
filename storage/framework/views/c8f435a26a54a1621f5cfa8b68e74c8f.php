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
                                                <button class="btn btn-primary" wire:click="$dispatch('openCreateTaskModal')">Nova Tarefa</button>    
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="d-flex justify-content-sm-end">
                                                        <!-- Filtro por Status -->
                                                <div class="mb-3">
                                                    <label for="statusFilter" class="form-label">Filtrar por Status:</label>
                                                    <select id="statusFilter" wire:model="statusFilter" class="form-select">
                                                        <option value="all">Todas</option>
                                                        <option value="pending">Pendentes</option>
                                                        <option value="completed">Concluídas</option>
                                                    </select>
                                                </div>
                                                </div>
                                            </div>
                                        </div>    
                                </div><!-- end card header -->

                                <div class="card-body">
                                        <div class="table-responsive">
                                        <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Data de Vencimento</th>
                <th>Usuários Atribuídos</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($task->titulo); ?></td>
                    <td><?php echo e($task->cliente->razao_social); ?></td>
                    <td><?php echo e(ucfirst($task->tipo)); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($task->data_de_vencimento)->format('d/m/Y')); ?></td>
                    <td>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $task->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-info text-dark"><?php echo e($user->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <td>
                        <!--[if BLOCK]><![endif]--><?php if($task->status == 'pending'): ?>
                            <span class="badge bg-warning">Pendente</span>
                        <?php else: ?>
                            <span class="badge bg-success">Concluída</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <td>

                    <button class="btn btn-sm btn-secondary" wire:click="$dispatch('openEditTaskModal', { taskId: <?php echo e($task->id); ?> })">Editar</button>
                    <button class="btn btn-sm btn-success" wire:click="markAsCompleted(<?php echo e($task->id); ?>)" <?php echo e($task->status == 'completed' ? 'disabled' : ''); ?>>Concluir</button>
                    <button class="btn btn-sm btn-danger" wire:click="deleteTask(<?php echo e($task->id); ?>)" onclick="confirm('Tem certeza que deseja deletar esta tarefa?') || event.stopImmediatePropagation()">Deletar</button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhuma tarefa encontrada.</td>
                </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
                                        </div>
                                         <!-- Paginação -->
                            <div class="mt-3">
                           
                            </div>
                                    <div class="code-view d-none">
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div>

    <!-- Inclusão dos Componentes de Criação e Edição de Tarefas -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('task.create-task');

$__html = app('livewire')->mount($__name, $__params, 'lw-763793169-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('task.edit-task');

$__html = app('livewire')->mount($__name, $__params, 'lw-763793169-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div><?php /**PATH C:\laragon\www\saas_plugando\resources\views/livewire/task/task-list.blade.php ENDPATH**/ ?>