@extends('layouts.master')
@section('title')
{{ $titulo ?? 'Dashboard' }}
@endsection
@section('content')
{{ $slot }}
@endsection
@section('script')
<!-- Scripts principais -->
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
<script src="{{ URL::asset('build/js/clientes.js') }}"></script>
<script src="{{ URL::asset('build/js/certificados.js') }}"></script>
<script src="{{ URL::asset('build/js/tarefas.js') }}"></script>

<!-- jQuery (antes de todos os outros scripts) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- Bootstrap Bundle com Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<!-- Sweet Alerts js -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
<script>
    document.addEventListener('livewire:initialized', () => {
        // Configuração do Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };

        // Listener para mostrar toasts
        Livewire.on('showToast', (data) => {
            toastr[data.type](data.message);
        });

        // Avatar
        Livewire.on('avatarUpdated', (avatarUrl) => {
            console.log('Evento avatarUpdated recebido:', avatarUrl);
            const headerAvatar = document.getElementById('header-avatar');
            if (headerAvatar) {
                headerAvatar.src = avatarUrl + '?t=' + new Date().getTime();
            }
            toastr.success('Avatar atualizado com sucesso!');
        });

        // Inicializa todos os modais
        Livewire.on('hideModal', () => {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modalEl => {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });
        });

        // Modal de Tarefas
        Livewire.on('openTarefaModal', () => {
            const modalEl = document.getElementById('createTask');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });

        // Modal de Comentários
        Livewire.on('openComentariosModal', () => {
            console.log('Evento openComentariosModal recebido');
            const modalEl = document.getElementById('comentariosModal');
            if (modalEl) {
                console.log('Modal encontrado:', modalEl);
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            } else {
                console.error('Modal não encontrado: comentariosModal');
            }
        });
    });
</script>
@endsection