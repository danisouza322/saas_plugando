<?php

// app/Notifications/TaskAssignedNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * Cria uma nova notificação.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Determina os canais de notificação.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Adicione outros canais conforme necessário
    }

    /**
     * Gera a mensagem de e-mail da notificação.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nova Tarefa Atribuída: ' . $this->task->title)
                    ->greeting('Olá ' . $notifiable->name . ',')
                    ->line('Você foi atribuído a uma nova tarefa.')
                    ->line('**Título:** ' . $this->task->title)
                    ->line('**Descrição:** ' . $this->task->description)
                    ->line('**Data de Vencimento:** ' . \Carbon\Carbon::parse($this->task->due_date)->format('d/m/Y'))
                    ->action('Visualizar Tarefa', url(route('tarefas.index')))
                    ->line('Obrigado por usar nossa aplicação!');
    }

    /**
     * Gera os dados para o banco de dados.
     */
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'titulo' => $this->task->title,
            'descricao' => $this->task->description,
            'data_de_vencimento' => $this->task->due_date,
            'tipo' => $this->task->type,
        ];
    }
}

