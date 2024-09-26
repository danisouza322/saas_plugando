<?php

namespace App\Livewire\Task;

use Livewire\Component;
use App\Models\TaskTemplate;
use Illuminate\Support\Facades\Auth;

class TaskTemplateList extends Component
{
    public $taskTemplates;

    protected $listeners = [
        'taskTemplateUpdated' => 'loadTaskTemplates',
        'taskTemplateCreated' => 'loadTaskTemplates',
        'taskTemplateDeleted' => 'loadTaskTemplates',
    ];

    public function mount()
    {
        $this->loadTaskTemplates();
    }

    public function loadTaskTemplates()
    {
        $this->taskTemplates = TaskTemplate::with('cliente', 'user')
                                          ->where('empresa_id', Auth::user()->empresa_id)
                                          ->get();
    }

    public function deleteTaskTemplate($templateId)
    {
        $template = TaskTemplate::findOrFail($templateId);

        // Autorizar a ação
        $this->authorize('delete', $template);

        $template->delete();

        session()->flash('message', 'Modelo de tarefa deletado com sucesso.');

        $this->loadTaskTemplates();
    }

    public function render()
    {
        return view('livewire.task-template-list');
    }
}
