@extends('layouts.master')
<!-- CSS e outros links -->
@section('title')
{{ $titulo ?? 'Dashaboard' }}
@endsection
@section('content')
{{ $slot }}
@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
<script src="{{ URL::asset('build/js/clientes.js') }}"></script>
<script src="{{ URL::asset('build/js/certificados.js') }}"></script>
<script src="{{ URL::asset('build/js/tasks.js') }}"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<!-- Sweet Alerts js -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
<script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('closeModal', () => {
                // Fechar o modal de certificado
                var modalEl = document.getElementById('certificadoModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            });

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

                // Listener para abrir o modal de criação de tarefas
                Livewire.on('openCreateTaskModal', () => {
                    var createTaskModal = new bootstrap.Modal(document.getElementById('createTaskModal'), {
                        backdrop: 'static',
                        keyboard: false
                    });
                    createTaskModal.show();
                });

                // Listener para fechar o modal de criação de tarefas após a submissão
                Livewire.on('closeCreateTaskModal', () => {
                    var createTaskModalEl = document.getElementById('createTaskModal');
                    var createTaskModal = bootstrap.Modal.getInstance(createTaskModalEl);
                    if (createTaskModal) {
                        createTaskModal.hide();
                    }
                });

              // Abrir o modal de edição de tarefas
                    Livewire.on('openEditTaskModal', () => {
                        var editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'), {
                            backdrop: 'static',
                            keyboard: false
                        });
                        editTaskModal.show();
                    });

                    // Fechar o modal de edição de tarefas
                    Livewire.on('closeEditTaskModal', () => {
                        var editTaskModalEl = document.getElementById('editTaskModal');
                        var editTaskModal = bootstrap.Modal.getInstance(editTaskModalEl);
                        if (editTaskModal) {
                            editTaskModal.hide();
                        }
                    });
                        });

    </script>
    <!-- Adicionar o script para escutar o evento Livewire -->
   
@endsection