
<!-- CSS e outros links -->
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<!-- Sweet Alerts js -->
<script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
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

               // Listener para abrir o modal de criação de tarefas
            window.addEventListener('openCreateTaskModal', event => {
                
                var myModal = new bootstrap.Modal(document.getElementById('createTaskModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                myModal.show();
            });

          

            // Listener para abrir o modal de edição de tarefas
            window.addEventListener('openEditTaskModal', event => {
                var myModal = new bootstrap.Modal(document.getElementById('editTaskModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                myModal.show();
            });

            // Listener para fechar o modal de edição de tarefas
            window.addEventListener('closeEditTaskModal', event => {
                var myModalEl = document.getElementById('editTaskModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                if (modal) {
                    modal.hide();
                }
            });


    </script>
    <!-- Adicionar o script para escutar o evento Livewire -->
   
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Daniel\Documents\saas_plugando\resources\views/layouts/app.blade.php ENDPATH**/ ?>