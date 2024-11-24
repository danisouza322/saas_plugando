document.addEventListener('livewire:initialized', () => {
    // Inicializar o modal de tarefas
    const taskModal = new bootstrap.Modal(document.getElementById('taskModal'));

    // Listener para mostrar o modal
    Livewire.on('show-task-modal', () => {
        taskModal.show();
    });

    // Listener para esconder o modal
    Livewire.on('hide-task-modal', () => {
        taskModal.hide();
    });

    // Listener para quando a tarefa for salva
    Livewire.on('task-saved', () => {
        taskModal.hide();
        // A notificação será tratada pelo tarefas.js
    });
});
