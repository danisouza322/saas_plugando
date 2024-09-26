<?php   

// app/Policies/TaskPolicy.php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine se o usuário pode visualizar a tarefa.
     */
    public function view(User $user, Task $task)
    {
        // Verifica se a tarefa pertence à mesma empresa e se o usuário está atribuído a ela
        return $task->empresa_id === $user->empresa_id && $task->users->contains($user);
    }

    /**
     * Determine se o usuário pode atualizar a tarefa.
     */
    public function update(User $user, Task $task)
    {
        // Verifica se a tarefa pertence à mesma empresa e se o usuário está atribuído a ela
        return $task->empresa_id === $user->empresa_id && $task->users->contains($user);
    }

    /**
     * Determine se o usuário pode deletar a tarefa.
     */
    public function delete(User $user, Task $task)
    {
        // Verifica se a tarefa pertence à mesma empresa e se o usuário está atribuído a ela
        return $task->empresa_id === $user->empresa_id && $task->users->contains($user);
    }
}
