<div>
    <h1>Editar Cliente</h1>

    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <form wire:submit.prevent="save">
        <!-- Campos do formulário -->
        <div class="row">
            <!-- Razão Social -->
            <div class="col-md-6">
                <label for="razao_social" class="form-label">Razão Social:</label>
                <input type="text" id="razao_social" class="form-control <?php $__errorArgs = ['razao_social'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="razao_social">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['razao_social'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- CNPJ -->
            <div class="col-md-6">
                <label for="cnpj" class="form-label">CNPJ:</label>
                <input type="text" id="cnpj" class="form-control <?php $__errorArgs = ['cnpj'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="cnpj">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cnpj'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
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
                <input type="text" id="inscricao_estadual" class="form-control <?php $__errorArgs = ['inscricao_estadual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="inscricao_estadual">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['inscricao_estadual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Regime Tributário -->
            <div class="col-md-6">
                <label for="regime_tributario" class="form-label">Regime Tributário:</label>
                <select id="regime_tributario" class="form-control <?php $__errorArgs = ['regime_tributario'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="regime_tributario">
                    <option value="">Selecione</option>
                    <option value="simples_nacional">Simples Nacional</option>
                    <option value="lucro_presumido">Lucro Presumido</option>
                    <option value="lucro_real">Lucro Real</option>
                    <option value="mei">MEI</option>
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['regime_tributario'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Adicione os demais campos necessários -->

            <!-- Atividades Econômicas -->
            <div class="col-md-12 mt-3">
                <h4>Atividades Econômicas</h4>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $atividades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $atividade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3">
                    <label for="atividades_<?php echo e($index); ?>_codigo" class="form-label">Código (<?php echo e($atividade['tipo']); ?>):</label>
                    <input type="text" id="atividades_<?php echo e($index); ?>_codigo" class="form-control <?php $__errorArgs = ['atividades.'.$index.'.codigo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="atividades.<?php echo e($index); ?>.codigo">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['atividades.'.$index.'.codigo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-md-9">
                    <label for="atividades_<?php echo e($index); ?>_descricao" class="form-label">Descrição (<?php echo e($atividade['tipo']); ?>):</label>
                    <input type="text" id="atividades_<?php echo e($index); ?>_descricao" class="form-control <?php $__errorArgs = ['atividades.'.$index.'.descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="atividades.<?php echo e($index); ?>.descricao">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['atividades.'.$index.'.descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Inscrições Estaduais -->
            <div class="col-md-12 mt-3">
                <h4>Inscrições Estaduais</h4>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $inscricoesEstaduais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inscricao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-2">
                    <label for="inscricoesEstaduais_<?php echo e($index); ?>_estado" class="form-label">Estado:</label>
                    <input type="text" id="inscricoesEstaduais_<?php echo e($index); ?>_estado" class="form-control <?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="inscricoesEstaduais.<?php echo e($index); ?>.estado">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-md-3">
                    <label for="inscricoesEstaduais_<?php echo e($index); ?>_numero" class="form-label">Número:</label>
                    <input type="text" id="inscricoesEstaduais_<?php echo e($index); ?>_numero" class="form-control <?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.numero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="inscricoesEstaduais.<?php echo e($index); ?>.numero">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.numero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-md-2">
                    <label for="inscricoesEstaduais_<?php echo e($index); ?>_ativa" class="form-label">Ativa:</label>
                    <select id="inscricoesEstaduais_<?php echo e($index); ?>_ativa" class="form-control <?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.ativa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="inscricoesEstaduais.<?php echo e($index); ?>.ativa">
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.ativa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-md-2">
                    <label for="inscricoesEstaduais_<?php echo e($index); ?>_data_status" class="form-label">Data do Status:</label>
                    <input type="date" id="inscricoesEstaduais_<?php echo e($index); ?>_data_status" class="form-control <?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.data_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="inscricoesEstaduais.<?php echo e($index); ?>.data_status">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.data_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-md-3">
                    <label for="inscricoesEstaduais_<?php echo e($index); ?>_status_texto" class="form-label">Status:</label>
                    <input type="text" id="inscricoesEstaduais_<?php echo e($index); ?>_status_texto" class="form-control <?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.status_texto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="inscricoesEstaduais.<?php echo e($index); ?>.status_texto">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.status_texto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-md-3">
                    <label for="inscricoesEstaduais_<?php echo e($index); ?>_tipo_texto" class="form-label">Tipo:</label>
                    <input type="text" id="inscricoesEstaduais_<?php echo e($index); ?>_tipo_texto" class="form-control <?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.tipo_texto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="inscricoesEstaduais.<?php echo e($index); ?>.tipo_texto">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['inscricoesEstaduais.'.$index.'.tipo_texto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Botão de Submit -->
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </div>
    </form>
</div>
<?php /**PATH C:\laragon\www\saas_plugando\resources\views/livewire/cliente/edit-cliente.blade.php ENDPATH**/ ?>