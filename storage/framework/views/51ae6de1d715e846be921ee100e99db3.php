<div>
    <div class="container-fluid">
        <!-- Título -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Editar Cliente</h4>
            </div>
        </div>

        <!-- Formulário -->
        <form wire:submit.prevent="submit">
            <div class="row">
                <!-- Razão Social -->
                <div class="col-md-6 mb-3">
                    <label for="razao_social" class="form-label">Razão Social</label>
                    <input type="text" id="razao_social" class="form-control" wire:model.defer="cliente.razao_social">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cliente.razao_social'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Nome Fantasia -->
                <div class="col-md-6 mb-3">
                    <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                    <input type="text" id="nome_fantasia" class="form-control" wire:model.defer="cliente.nome_fantasia">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cliente.nome_fantasia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Repita os demais campos do cliente conforme necessário -->

                <!-- Endereço -->
                <div class="col-12">
                    <h5 class="mt-4">Endereço</h5>
                </div>

                <!-- Rua -->
                <div class="col-md-6 mb-3">
                    <label for="rua" class="form-label">Rua</label>
                    <input type="text" id="rua" class="form-control" wire:model.defer="endereco.rua">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['endereco.rua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Repita os demais campos de endereço conforme necessário -->

                <!-- Botões -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <a href="<?php echo e(route('painel.clientes.index')); ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\Users\Daniel\Documents\saas_plugando\resources\views/livewire/cliente/edit-cliente.blade.php ENDPATH**/ ?>