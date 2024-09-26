<?php 

// app/Policies/TaskTemplatePolicy.php

namespace App\Policies;

use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskTemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine se o usuário pode visualizar o modelo de tarefa.
     */
    public function view(User $user, TaskTemplate $template)
    {
        // Verifica se o modelo pertence à mesma empresa
        return $template->empresa_id === $user->empresa_id;
    }

    /**
     * Determine se o usuário pode atualizar o modelo de tarefa.
     */
    public function update(User $user, TaskTemplate $template)
    {
        // Verifica se o modelo pertence à mesma empresa
        return $template->empresa_id === $user->empresa_id;
    }

    /**
     * Determine se o usuário pode deletar o modelo de tarefa.
     */
    public function delete(User $user, TaskTemplate $template)
    {
        // Verifica se o modelo pertence à mesma empresa
        return $template->empresa_id === $user->empresa_id;
    }
}
