<?php

namespace App\Livewire\Task;

use Livewire\Component;
use App\Models\TaskTemplate;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateTaskTemplate extends Component
{
    public $titulo;
    public $descricao;
    public $user_id;
    public $cliente_id;
    public $frequencia = 'monthly';
    public $dia_do_mes = 1;

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'user_id' => 'required|exists:users,id',
        'cliente_id' => 'required|exists:clientes,id',
        'frequencia' => 'required|in:monthly',
        'dia_do_mes' => 'required|integer|min:1|max:31',
    ];

    protected $listeners = [
        'openCreateTaskTemplateModal' => 'openModal',
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
        $this->user_id = '';
        $this->cliente_id = '';
        $this->frequencia = 'monthly';
        $this->dia_do_mes = 1;
    }

    public function createTaskTemplate()
    {
        $this->validate();

        TaskTemplate::create([
            'empresa_id' => Auth::user()->empresa_id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'user_id' => $this->user_id,
            'cliente_id' => $this->cliente_id,
            'frequencia' => $this->frequencia,
            'dia_do_mes' => $this->dia_do_mes,
        ]);

        session()->flash('message', 'Modelo de tarefa criado com sucesso.');

        $this->closeModal();
        $this->dispatch('taskTemplateCreated'); // Atualiza a lista de modelos
    }

    public function render()
    {
        $clientes = Cliente::where('empresa_id', Auth::user()->empresa_id)->get();
        $users = User::where('empresa_id', Auth::user()->empresa_id)->get();

        return view('livewire.task.create-task-template', [
            'clientes' => $clientes,
            'users' => $users,
        ]);
    }
}
