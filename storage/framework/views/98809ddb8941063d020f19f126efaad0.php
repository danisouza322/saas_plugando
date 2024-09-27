<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Modelos de Tarefas</h2>
        <button class="btn btn-primary" wire:click="$dispatch('openCreateTaskTemplateModal')">Novo Modelo</button>
    </div>

    <!-- Tabela de Modelos de Tarefas -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Cliente</th>
                <th>Usuário Responsável</th>
                <th>Frequência</th>
                <th>Dia do Mês</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $taskTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($template->titulo); ?></td>
                    <td><?php echo e($template->cliente->nome); ?></td>
                    <td><?php echo e($template->user->name); ?></td>
                    <td><?php echo e(ucfirst($template->frequencia)); ?></td>
                    <td><?php echo e($template->dia_do_mes); ?></td>
                    <td>
                        <button class="btn btn-sm btn-secondary" wire:click="$dispatch('openEditTaskTemplateModal', <?php echo e($template->id); ?>)">Editar</button>
                        <button class="btn btn-sm btn-danger" wire:click="deleteTaskTemplate(<?php echo e($template->id); ?>)" onclick="confirm('Tem certeza que deseja deletar este modelo de tarefa?') || event.stopImmediatePropagation()">Deletar</button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum modelo de tarefa encontrado.</td>
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

    <!-- Inclusão dos Componentes de Criação e Edição de Modelos de Tarefas -->
     <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('task.create-task-template');

$__html = app('livewire')->mount($__name, $__params, 'lw-1175104690-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('task.edit-task-template');

$__html = app('livewire')->mount($__name, $__params, 'lw-1175104690-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</div>
<?php /**PATH C:\Users\Daniel\Documents\saas_plugando\resources\views/livewire/task/task-template-list.blade.php ENDPATH**/ ?>