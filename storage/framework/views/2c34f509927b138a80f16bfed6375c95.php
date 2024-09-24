<div>
    <h1>Lista de Clientes</h1>

    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Razão Social</th>
                <th>Nome Fantasia</th>
                <!-- Outros cabeçalhos -->
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($cliente->razao_social); ?></td>
                    <td><?php echo e($cliente->nome_fantasia); ?></td>
                    <!-- Outros dados -->
                    <td>
                        <a href="<?php echo e(route('clientes.edit', $cliente->id)); ?>" class="btn btn-sm btn-primary">Editar</a>
                        <!-- Botão de exclusão -->
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\Daniel\Documents\saas_plugando\resources\views/livewire/cliente/index-clientes.blade.php ENDPATH**/ ?>