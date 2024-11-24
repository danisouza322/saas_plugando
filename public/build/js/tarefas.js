// Funções para o sistema de tarefas

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Manipulador do menu mobile
    const menuBtn = document.querySelector('.file-menu-btn');
    const sidebar = document.querySelector('.file-manager-sidebar');

    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // Fechar sidebar ao clicar fora em dispositivos móveis
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992 && 
            !e.target.closest('.file-manager-sidebar') && 
            !e.target.closest('.file-menu-btn')) {
            sidebar.classList.remove('show');
        }
    });

    // Drag and Drop para status das tarefas
    const draggableTasks = document.querySelectorAll('.draggable-task');
    const dropZones = document.querySelectorAll('.status-dropzone');

    draggableTasks.forEach(task => {
        task.addEventListener('dragstart', handleDragStart);
        task.addEventListener('dragend', handleDragEnd);
    });

    dropZones.forEach(zone => {
        zone.addEventListener('dragover', handleDragOver);
        zone.addEventListener('dragleave', handleDragLeave);
        zone.addEventListener('drop', handleDrop);
    });

    // Preview de arquivos
    const fileInput = document.querySelector('input[type="file"]');
    const previewContainer = document.querySelector('.file-preview-container');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (previewContainer) {
                previewContainer.innerHTML = '';
                Array.from(e.target.files).forEach(file => {
                    const preview = createFilePreview(file);
                    previewContainer.appendChild(preview);
                });
            }
        });
    }
});

// Funções auxiliares para Drag and Drop
function handleDragStart(e) {
    this.classList.add('dragging');
    e.dataTransfer.setData('text/plain', this.dataset.taskId);
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
}

function handleDragOver(e) {
    e.preventDefault();
    this.classList.add('drag-over');
}

function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

function handleDrop(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
    
    const taskId = e.dataTransfer.getData('text/plain');
    const newStatus = this.dataset.status;
    
    // Atualizar status via Livewire
    Livewire.dispatch('updateTaskStatus', { taskId, newStatus });
}

// Função para criar preview de arquivo
function createFilePreview(file) {
    const div = document.createElement('div');
    div.className = 'file-preview';
    
    const icon = getFileIcon(file.type);
    const size = formatFileSize(file.size);
    
    div.innerHTML = `
        <i class="${icon}"></i>
        <div class="file-info">
            <div class="file-name">${file.name}</div>
            <div class="file-size">${size}</div>
        </div>
    `;
    
    return div;
}

// Função para obter ícone baseado no tipo de arquivo
function getFileIcon(mimeType) {
    const icons = {
        'image': 'ri-image-line',
        'application/pdf': 'ri-file-pdf-line',
        'application/msword': 'ri-file-word-line',
        'application/vnd.ms-excel': 'ri-file-excel-line',
        'text/plain': 'ri-file-text-line'
    };

    for (const [type, icon] of Object.entries(icons)) {
        if (mimeType.includes(type)) return icon;
    }

    return 'ri-file-line';
}

// Função para formatar tamanho do arquivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

document.addEventListener('livewire:initialized', () => {
    // Inicializa Select2
    initializeSelect2();

    // Confirmação de exclusão
    Livewire.on('confirmDelete', (data) => {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteTarefa');
            }
        });
    });

    // Gerencia o modal
    let modal = null;

    function hideModal() {
        const modalEl = document.getElementById('createTask');
        if (modalEl) {
            modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
            // Limpa backdrop e classes do body
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }
    }

    // Eventos do Livewire
    Livewire.on('showModal', () => {
        const modalEl = document.getElementById('createTask');
        if (modalEl) {
            modal = new bootstrap.Modal(modalEl);
            modal.show();
            initializeSelect2();
        }
    });

    Livewire.on('hideModal', () => {
        hideModal();
    });

    // Limpa o modal quando fechado manualmente
    const modalEl = document.getElementById('createTask');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', () => {
            hideModal();
            Livewire.dispatch('closeModal');
        });
    }

    // Atualiza Select2 quando uma tarefa é atualizada
    Livewire.on('tarefaUpdated', () => {
        initializeSelect2();
    });

    // Notificações com Toastify
    Livewire.on('showToast', (data) => {
        const message = data.message || 'Operação realizada com sucesso';
        const type = data.type || 'success';
        
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            close: true,
            style: {
                background: type === 'success' 
                    ? "linear-gradient(to right, #00b09b, #96c93d)"
                    : "linear-gradient(to right, #ff5f6d, #ffc371)",
            }
        }).showToast();
    });
});

// Função para inicializar o Select2
function initializeSelect2() {
    if (typeof jQuery !== 'undefined') {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    }
}

// Livewire.on('taskCreated', () => {
//     Swal.fire({
//         icon: 'success',
//         title: 'Sucesso!',
//         text: 'Tarefa criada com sucesso!',
//         showConfirmButton: false,
//         timer: 1500
//     });
// });

// Livewire.on('taskUpdated', () => {
//     Swal.fire({
//         icon: 'success',
//         title: 'Sucesso!',
//         text: 'Tarefa atualizada com sucesso!',
//         showConfirmButton: false,
//         timer: 1500
//     });
// });

// Livewire.on('taskDeleted', () => {
//     Swal.fire({
//         icon: 'success',
//         title: 'Sucesso!',
//         text: 'Tarefa excluída com sucesso!',
//         showConfirmButton: false,
//         timer: 1500
//     });
// });

// Livewire.on('error', (message) => {
//     Swal.fire({
//         icon: 'error',
//         title: 'Erro!',
//         text: message
//     });
// });
