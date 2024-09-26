@extends('layouts.master')
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
<!-- Sweet Alerts js -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
   <!-- Toastr JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', function () {
            // Inicializar Select2 em todos os elementos com a classe 'select2'
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
            });

            // Atualizar Select2 quando propriedades Livewire mudarem
            Livewire.hook('message.processed', (message, component) => {
                $('.select2').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            theme: 'bootstrap4',
                            width: '100%',
                        });
                    }
                });
            });

            // Configurações do Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000",
            };

            // Listener para o evento 'avatarUpdated' (se aplicável)
            Livewire.on('avatarUpdated', (avatarUrl) => {
                console.log('Evento avatarUpdated recebido:', avatarUrl); // Log para depuração

                // Selecionar o elemento do avatar na top bar
                const headerAvatar = document.getElementById('header-avatar');

                if (headerAvatar) {
                    // Atualizar o atributo 'src' com o novo URL do avatar
                    headerAvatar.src = avatarUrl + '?t=' + new Date().getTime();
                    console.log('Atributo src atualizado para:', headerAvatar.src);
                }

                // Exibir uma notificação usando Toastr
                toastr.success('Avatar atualizado com sucesso!');
            });

            // Listener para o evento 'showToast'
            Livewire.on('showToast', (message) => {
                toastr.success(message);
            });
        });
    </script>





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
   
@endsection