
<?php $__env->startSection('title'); ?>
<?php echo e($titulo ?? 'Dashaboard'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo e($slot); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/pages/profile-setting.init.js')); ?>"></script>
<script src="<?php echo e(URL::asset('build/js/clientes.js')); ?>"></script>
<!-- Sweet Alerts js -->
<script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
<script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showToast', (message) => {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    },
                }).showToast();
            });

            Livewire.on('avatarUpdated', (avatarUrl) => {
                console.log('Evento avatarUpdated recebido:', avatarUrl); // Log para depuração

                // Selecionar o elemento do avatar na top bar
                const headerAvatar = document.getElementById('header-avatar');

                if (headerAvatar) {
                    // Atualizar o atributo 'src' com o novo URL do avatar
                    headerAvatar.src = avatarUrl + '?t=' + new Date().getTime();
                }

                // Exibir uma notificação usando Toastr
                toastr.success('Avatar atualizado com sucesso!');
            });

        });
    </script>
    <!-- Adicionar o script para escutar o evento Livewire -->
   
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\saas_plugando\resources\views/layouts/app.blade.php ENDPATH**/ ?>