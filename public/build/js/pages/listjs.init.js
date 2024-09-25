document.addEventListener('DOMContentLoaded', function () {
    initList();
});

document.addEventListener('livewire:load', function () {
    initList();
});

Livewire.hook('message.processed', (message, component) => {
    initList();
});

var customerList;

function initList() {
    if (customerList) {
        customerList.clear();
        customerList.destroy();
    }

    var options = {
        valueNames: [
            'razao_social',
            'cnpj',
            'inscricao_estadual',
            'regime_tributario',
        ],
        page: 10,
        pagination: true,
        item: '<tr>' +
              '<td class="razao_social"></td>' +
              '<td class="cnpj"></td>' +
              '<td class="inscricao_estadual"></td>' +
              '<td class="regime_tributario"></td>' +
              '<td></td>' +
              '</tr>',
        // plugins: [...] // Se estiver usando plugins
    };

    var data = []; // Inicialmente vazio

    // Verificar se há itens na tabela
    var itemElements = document.querySelectorAll('#customerList .list > tr');
    if (itemElements.length > 0) {
        // Se houver itens, coletar os dados
        itemElements.forEach(function (item) {
            data.push({
                razao_social: item.querySelector('.razao_social').innerText,
                cnpj: item.querySelector('.cnpj').innerText,
                inscricao_estadual: item.querySelector('.inscricao_estadual').innerText,
                regime_tributario: item.querySelector('.regime_tributario').innerText,
            });
        });
    }

    customerList = new List('customerList', options, data).on('updated', function (list) {
        if (list.matchingItems.length === 0) {
            document.getElementsByClassName('noresult')[0].style.display = 'block';
        } else {
            document.getElementsByClassName('noresult')[0].style.display = 'none';
        }
    });

    // Atualizar a exibição inicial
    if (customerList.size() === 0) {
        document.getElementsByClassName('noresult')[0].style.display = 'block';
    } else {
        document.getElementsByClassName('noresult')[0].style.display = 'none';
    }

    // Associar eventos aos botões de exclusão
    attachDeleteEventListeners();
}

function attachDeleteEventListeners() {
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var clientId = this.getAttribute('data-client-id');
            confirmDelete(clientId);
        });
    });
}

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