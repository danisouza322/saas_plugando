<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'task_type_id',
        'cliente_id',
        'start_date',
        'due_date',
        'priority',
        'status',
        'estimated_minutes',
        'budget',
        'location',
        'tags',
        'requires_approval',
        'is_recurring',
        'recurrence_pattern',
        'recurrence_config',
        'empresa_id',
        'created_by',
        'ativo'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'tags' => 'array',
        'requires_approval' => 'boolean',
        'is_recurring' => 'boolean',
        'recurrence_config' => 'array',
        'ativo' => 'boolean'
    ];

    protected $appends = [
        'status_text',
        'priority_text',
        'status_color',
        'priority_color',
        'formatted_due_date'
    ];

    // Relacionamentos
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id')
                    ->where('ativo', true)
                    ->withTimestamps();
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function type()
    {
        return $this->belongsTo(TaskType::class, 'task_type_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'task_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function history()
    {
        return $this->hasMany(TaskHistory::class);
    }

    // Scopes
    public function scopeForEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId)
                    ->where('ativo', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->where('ativo', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                    ->where('ativo', true);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress')
                    ->where('ativo', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->where('ativo', true);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent')
                    ->where('ativo', true);
    }

    public function scopeRequiresApproval($query)
    {
        return $query->where('requires_approval', true)
                    ->where('ativo', true);
    }

    // Acessors e Mutators
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'in_progress' => 'Em Andamento',
            'completed' => 'Concluída',
            'delayed' => 'Atrasada',
            'cancelled' => 'Cancelada',
            default => $this->status
        };
    }

    public function getPriorityTextAttribute()
    {
        return match($this->priority) {
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
            'urgent' => 'Urgente',
            default => $this->priority
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'delayed' => 'danger',
            'cancelled' => 'dark',
            default => 'secondary'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'orange',
            'urgent' => 'danger',
            default => 'secondary'
        };
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date ? $this->due_date->format('d/m/Y') : 'N/A';
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->due_date) return false;
        if (in_array($this->status, ['completed', 'cancelled'])) return false;
        return $this->due_date->isPast();
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->status === 'completed') return 100;
        if ($this->status === 'cancelled') return 0;
        
        $total = $this->subtasks()->count();
        if ($total === 0) return 0;
        
        $completed = $this->subtasks()->where('completed', true)->count();
        return round(($completed / $total) * 100);
    }

    // Métodos
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    public function approve($userId)
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $userId,
            'approved_at' => now()
        ]);
    }

    public function addTeamMember($userId, $role = null)
    {
        $this->teamMembers()->attach($userId, ['role' => $role]);
    }

    public function removeTeamMember($userId)
    {
        $this->teamMembers()->detach($userId);
    }

    public function logHistory($userId, $actionType, $description = null, $comment = null)
    {
        return TaskHistory::logChange(
            $this->id,
            $userId,
            $actionType,
            $description,
            null,
            null,
            null,
            $comment
        );
    }

    public function logFieldChange($userId, $fieldName, $oldValue, $newValue)
    {
        return TaskHistory::logChange(
            $this->id,
            $userId,
            'update',
            "Campo {$fieldName} foi atualizado",
            $fieldName,
            $oldValue,
            $newValue
        );
    }
}
