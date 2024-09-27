<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Minhas Tarefas</h2>
        <button class="btn btn-primary" wire:click="$dispatch('openCreateTaskModal')">Nova Tarefa</button>
    </div>

    <!-- Filtro por Status -->
    <div class="mb-3">
        <label for="statusFilter" class="form-label">Filtrar por Status:</label>
        <select id="statusFilter" wire:model="statusFilter" class="form-select">
            <option value="all">Todas</option>
            <option value="pending">Pendentes</option>
            <option value="completed">Concluídas</option>
        </select>
    </div>

    <!-- Tabela de Tarefas -->
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
                    <td><?php echo e($task->cliente->nome); ?></td>
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
                        <button class="btn btn-sm btn-secondary" wire:click="$emit('openEditTaskModal', <?php echo e($task->id); ?>)">Editar</button>
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

    <!-- Mensagem de Sucesso -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="alert alert-success mt-3">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Inclusão dos Componentes de Criação e Edição de Tarefas -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('task.create-task');

$__html = app('livewire')->mount($__name, $__params, 'lw-2131395776-0', $__slots ?? [], get_defined_vars());

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

$__html = app('livewire')->mount($__name, $__params, 'lw-2131395776-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php /**PATH C:\Users\Daniel\Documents\saas_plugando\resources\views/livewire/task/task-list.blade.php ENDPATH**/ ?>