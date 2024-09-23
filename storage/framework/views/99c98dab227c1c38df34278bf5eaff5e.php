<div>
    <div class="container-fluid">
        <!-- Título -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Cadastro de Cliente</h4>
            </div>
        </div>

        <!-- Formulário -->
        <form wire:submit.prevent="submit">
            <div class="row">
                <!-- Razão Social -->
                <div class="col-md-6 mb-3">
                    <label for="razao_social" class="form-label">Razão Social</label>
                    <input type="text" id="razao_social" class="form-control" wire:model.defer="razao_social">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['razao_social'];
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
                    <input type="text" id="nome_fantasia" class="form-control" wire:model.defer="nome_fantasia">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['nome_fantasia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Regime Tributário -->
                <div class="col-md-6 mb-3">
                    <label for="regime_tributario" class="form-label">Regime Tributário</label>
                    <select id="regime_tributario" class="form-select" wire:model.defer="regime_tributario">
                        <option value="">Selecione</option>
                        <option value="1">Simples Nacional</option>
                        <option value="2">Lucro Presumido</option>
                        <option value="3">Lucro Real</option>
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

                <!-- Data de Abertura -->
                <div class="col-md-6 mb-3">
                    <label for="data_abertura" class="form-label">Data de Abertura</label>
                    <input type="date" id="data_abertura" class="form-control" wire:model.defer="data_abertura">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['data_abertura'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Porte -->
                <div class="col-md-6 mb-3">
                    <label for="porte" class="form-label">Porte</label>
                    <select id="porte" class="form-select" wire:model.defer="porte">
                        <option value="">Selecione</option>
                        <option value="MEI">MEI</option>
                        <option value="Microempresa">Microempresa</option>
                        <option value="Pequeno Porte">Empresa de Pequeno Porte</option>
                        <option value="Médio Porte">Médio Porte</option>
                        <option value="Grande Porte">Grande Porte</option>
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['porte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Capital Social -->
                <div class="col-md-6 mb-3">
                    <label for="capital_social" class="form-label">Capital Social</label>
                    <input type="number" step="0.01" id="capital_social" class="form-control" wire:model.defer="capital_social">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['capital_social'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Natureza Jurídica -->
                <div class="col-md-6 mb-3">
                    <label for="natureza_juridica" class="form-label">Natureza Jurídica</label>
                    <input type="text" id="natureza_juridica" class="form-control" wire:model.defer="natureza_juridica">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['natureza_juridica'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Tipo -->
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <input type="text" id="tipo" class="form-control" wire:model.defer="tipo">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Situação Cadastral -->
                <div class="col-md-6 mb-3">
                    <label for="situacao_cadastral" class="form-label">Situação Cadastral</label>
                    <input type="text" id="situacao_cadastral" class="form-control" wire:model.defer="situacao_cadastral">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['situacao_cadastral'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Endereço -->
                <div class="col-12">
                    <h5 class="mt-4">Endereço</h5>
                </div>

                <!-- Rua -->
                <div class="col-md-6 mb-3">
                    <label for="rua" class="form-label">Rua</label>
                    <input type="text" id="rua" class="form-control" wire:model.defer="rua">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['rua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Número -->
                <div class="col-md-6 mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" id="numero" class="form-control" wire:model.defer="numero">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['numero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Bairro -->
                <div class="col-md-6 mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" id="bairro" class="form-control" wire:model.defer="bairro">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['bairro'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Cidade -->
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" id="cidade" class="form-control" wire:model.defer="cidade">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cidade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Estado -->
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" class="form-select" wire:model.defer="estado">
                        <option value="">Selecione</option>
                        <!-- Adicione aqui as opções de estados -->
                        <option value="SP">São Paulo</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <!-- ... -->
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Município IBGE -->
                <div class="col-md-6 mb-3">
                    <label for="municipio_ibge" class="form-label">Município IBGE</label>
                    <input type="number" id="municipio_ibge" class="form-control" wire:model.defer="municipio_ibge">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['municipio_ibge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!-- Botões -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?php echo e(route('painel.clientes.index')); ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\saas_plugando\resources\views/livewire/cliente/create-cliente.blade.php ENDPATH**/ ?>