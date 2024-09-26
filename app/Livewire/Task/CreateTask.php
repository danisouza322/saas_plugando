<?php

namespace App\Livewire\Task;

use Livewire\Component;

use App\Models\Task;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateTask extends Component
{
    public $titulo;
    public $descricao;
    public $tipo = 'ad-hoc';
    public $data_de_vencimento;
    public $cliente_id;
    public $user_ids = []; // Para múltiplos usuários

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'tipo' => 'required|in:monthly,ad-hoc',
        'data_de_vencimento' => 'required|date',
        'cliente_id' => 'required|exists:clientes,id',
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'exists:users,id',
    ];

    protected $listeners = [
        'openCreateTaskModal' => 'openModal',
    ];

    public $isOpen = false;

    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->titulo = '';
        $this->descricao = '';
        $this->tipo = 'ad-hoc';
        $this->data_de_vencimento = '';
        $this->cliente_id = '';
        $this->user_ids = [];
    }

    public function createTask()
    {
        $this->validate();

        $task = Task::create([
            'empresa_id' => Auth::user()->empresa_id, // Atribuir empresa
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'tipo' => $this->tipo,
            'data_de_vencimento' => Carbon::parse($this->data_de_vencimento),
            'cliente_id' => $this->cliente_id,
            'status' => 'pending',
        ]);

        // Atribuir usuários à tarefa
        $task->users()->attach($this->user_ids);

        // Notificar cada usuário atribuído
        foreach ($this->user_ids as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->notify(new \App\Notifications\TaskAssignedNotification($task));
            }
        }

        session()->flash('message', 'Tarefa criada com sucesso.');

        $this->closeModal();
        $this->emit('taskUpdated'); // Atualiza a lista de tarefas
    }

    public function render()
    {
        $clientes = Cliente::where('empresa_id', Auth::user()->empresa_id)->get(); // Filtrar por empresa
        $users = User::where('empresa_id', Auth::user()->empresa_id)
                     ->where('id', '!=', Auth::id()) // Excluir o usuário atual, se necessário
                     ->get(); // Filtrar usuários pela mesma empresa

        return view('livewire.create-task', [
            'clientes' => $clientes,
            'users' => $users,
        ]);
    }
}
