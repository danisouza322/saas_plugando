document.addEventListener('DOMContentLoaded', function () {
    // Delegar eventos aos botões de exclusão
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('delete-btn')) {
            var certificadoId = e.target.getAttribute('data-id');
            confirmDelete(certificadoId);
        }
    });
});

function confirmDelete(certificadoId) {
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
            // Chamar o método Livewire para excluir o certificado
            Livewire.dispatch('excluirCertificado', { id: certificadoId });

            // Mostrar mensagem de sucesso
            Swal.fire({
                title: 'Excluído!',
                text: 'O certificado foi excluído com sucesso.',
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
