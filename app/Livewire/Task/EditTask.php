<?php

namespace App\Livewire\Task;

use Livewire\Component;
use App\Models\Task;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EditTask extends Component
{
    public $taskId;
    public $titulo;
    public $descricao;
    public $tipo;
    public $data_de_vencimento;
    public $cliente_id;
    public $user_ids = [];

    protected $listeners = ['openEditTaskModal'];

    public function openEditTaskModal($taskId)
    {

        // Carregar a tarefa do banco de dados
        $task = Task::with('users', 'cliente')->findOrFail($taskId);
        // Preencher os campos do formulário com os dados da tarefa
        $this->taskId = $taskId;
        $this->titulo = $task->titulo;
        $this->descricao = $task->descricao;
        $this->tipo = $task->tipo;
        $this->data_de_vencimento = Carbon::parse($task->data_de_vencimento)->format('Y-m-d');
        $this->cliente_id = $task->cliente_id;
      //  $this->user_ids = $task->users->pluck('id')->toArray();

        // Emitir o evento para abrir o modal
      //  $this->dispatch('openEditTaskModal');
    }
    public function updateTask()
    {
        // Validação dos dados
        $this->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:monthly,ad-hoc',
            'data_de_vencimento' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Atualizar a tarefa no banco de dados
        $task = Task::findOrFail($this->taskId);
        $task->update([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'tipo' => $this->tipo,
            'data_de_vencimento' => Carbon::parse($this->data_de_vencimento),
            'cliente_id' => $this->cliente_id,
        ]);

        // Sincronizar os usuários atribuídos
        $task->users()->sync($this->user_ids);

        // Emitir evento para fechar o modal de edição
        $this->dispatch('closeEditTaskModal');

        // Emitir evento para atualizar a lista de tarefas
        $this->emit('taskUpdated');

        // Exibir mensagem de sucesso
        session()->flash('message', 'Tarefa atualizada com sucesso!');
    }

    public function render()
    {
        // Carregar clientes e usuários da empresa atual
        $clientes = Cliente::where('empresa_id', Auth::user()->empresa_id)->get();
        $users = User::where('empresa_id', Auth::user()->empresa_id)->get();

        return view('livewire.task.edit-task', [
            'clientes' => $clientes,
            'users' => $users,
        ])->layout('layouts.app', [
            'titulo' => 'Editar Cliente', // Passando 'titulo' para o layout
        ]);
    }
}

