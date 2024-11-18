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
        'assigned_to',
        'created_by',
        'empresa_id',
        'task_type_id',
        'cliente_id',
        'start_date',
        'due_date',
        'completed_at',
        'status',
        'priority',
        'estimated_minutes',
        'spent_minutes',
        'budget',
        'spent_amount',
        'location',
        'tags',
        'requires_approval',
        'is_approved',
        'approved_by',
        'approved_at',
        'is_recurring',
        'recurrence_pattern',
        'recurrence_config',
        'sla_minutes',
        'sla_breached'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'approved_at' => 'datetime',
        'estimated_minutes' => 'integer',
        'spent_minutes' => 'integer',
        'budget' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'tags' => 'array',
        'requires_approval' => 'boolean',
        'is_approved' => 'boolean',
        'is_recurring' => 'boolean',
        'recurrence_config' => 'array',
        'sla_minutes' => 'integer',
        'sla_breached' => 'boolean'
    ];

    protected $appends = [
        'status_text',
        'priority_text',
        'status_color',
        'priority_color',
        'formatted_due_date'
    ];

    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function type()
    {
        return $this->belongsTo(TaskType::class, 'task_type_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
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

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function history()
    {
        return $this->hasMany(TaskHistory::class);
    }

    // Scopes
    public function scopeForEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDelayed($query)
    {
        return $query->where('status', 'delayed');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    public function scopeRequiresApproval($query)
    {
        return $query->where('requires_approval', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
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
