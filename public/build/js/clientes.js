document.addEventListener('DOMContentLoaded', function () {
    // Delegar eventos aos botões de exclusão
    document.addEventListener('click', function (e) {
        // Exclusão individual
        if (e.target && e.target.classList.contains('delete-btn')) {
            var clientId = e.target.getAttribute('data-id');
            confirmDelete(clientId);
        }

        // Exclusão em massa
        if (e.target && e.target.classList.contains('delete-selected-btn')) {
            confirmDeleteSelected();
        }
    });
});

function confirmDelete(clientId) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Você não poderá reverter esta ação!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary ms-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Chamar o método Livewire para excluir o cliente
            Livewire.dispatch('deleteCliente', { id: clientId });

            // Mostrar mensagem de sucesso
            Swal.fire({
                title: 'Excluído!',
                text: 'O cliente foi excluído com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        }
    });
}

function confirmDeleteSelected() {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Os clientes selecionados serão excluídos!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary ms-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Chamar o método Livewire para excluir os clientes selecionados
            Livewire.dispatch('deleteSelected');

            // Mostrar mensagem de sucesso
            Swal.fire({
                title: 'Excluídos!',
                text: 'Os clientes selecionados foram excluídos com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        }
    });
}



  

