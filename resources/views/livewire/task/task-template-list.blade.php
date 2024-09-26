<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Modelos de Tarefas</h2>
        <button class="btn btn-primary" wire:click="$emit('openCreateTaskTemplateModal')">Novo Modelo</button>
    </div>

    <!-- Tabela de Modelos de Tarefas -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Cliente</th>
                <th>Usuário Responsável</th>
                <th>Frequência</th>
                <th>Dia do Mês</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taskTemplates as $template)
                <tr>
                    <td>{{ $template->titulo }}</td>
                    <td>{{ $template->cliente->nome }}</td>
                    <td>{{ $template->user->name }}</td>
                    <td>{{ ucfirst($template->frequencia) }}</td>
                    <td>{{ $template->dia_do_mes }}</td>
                    <td>
                        <button class="btn btn-sm btn-secondary" wire:click="$emit('openEditTaskTemplateModal', {{ $template->id }})">Editar</button>
                        <button class="btn btn-sm btn-danger" wire:click="deleteTaskTemplate({{ $template->id }})" onclick="confirm('Tem certeza que deseja deletar este modelo de tarefa?') || event.stopImmediatePropagation()">Deletar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum modelo de tarefa encontrado.</td>
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

    <!-- Inclusão dos Componentes de Criação e Edição de Modelos de Tarefas -->
    @livewire('create-task-template')
    @livewire('edit-task-template')
</div>
