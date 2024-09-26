<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Minhas Tarefas</h2>
        <button class="btn btn-primary" wire:click="$emit('openCreateTaskModal')">Nova Tarefa</button>
    </div>

    <!-- Filtro por Status -->
    <div class="mb-3">
        <label for="statusFilter" class="form-label">Filtrar por Status:</label>
        <select id="statusFilter" wire:model="statusFilter" class="form-select">
            <option value="all">Todas</option>
            <option value="pending">Pendentes</option>
            <option value="completed">Concluídas</option>
        </select>
    </div>

    <!-- Tabela de Tarefas -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Data de Vencimento</th>
                <th>Usuários Atribuídos</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->titulo }}</td>
                    <td>{{ $task->cliente->nome }}</td>
                    <td>{{ ucfirst($task->tipo) }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->data_de_vencimento)->format('d/m/Y') }}</td>
                    <td>
                        @foreach($task->users as $user)
                            <span class="badge bg-info text-dark">{{ $user->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @if($task->status == 'pending')
                            <span class="badge bg-warning">Pendente</span>
                        @else
                            <span class="badge bg-success">Concluída</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-secondary" wire:click="$emit('openEditTaskModal', {{ $task->id }})">Editar</button>
                        <button class="btn btn-sm btn-success" wire:click="markAsCompleted({{ $task->id }})" {{ $task->status == 'completed' ? 'disabled' : '' }}>Concluir</button>
                        <button class="btn btn-sm btn-danger" wire:click="deleteTask({{ $task->id }})" onclick="confirm('Tem certeza que deseja deletar esta tarefa?') || event.stopImmediatePropagation()">Deletar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Nenhuma tarefa encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Mensagem de Sucesso -->
    @if (session()->has('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
    @endif

    <!-- Inclusão dos Componentes de Criação e Edição de Tarefas -->
    @livewire('create-task')
    @livewire('edit-task')
</div>
