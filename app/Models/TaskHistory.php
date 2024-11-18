<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Task;
use App\Models\User;

class TaskHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'user_id',
        'action_type',
        'field_name',
        'old_value',
        'new_value',
        'description',
        'comment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relacionamentos
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Acessors
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getChangeDescriptionAttribute()
    {
        if ($this->action_type === 'update') {
            return "O campo {$this->field_name} foi alterado de '{$this->old_value}' para '{$this->new_value}'";
        }
        return $this->description;
    }

    // Métodos auxiliares
    public static function logChange($taskId, $userId, $actionType, $description = null, $fieldName = null, $oldValue = null, $newValue = null, $comment = null)
    {
        return self::create([
            'task_id' => $taskId,
            'user_id' => $userId,
            'action_type' => $actionType,
            'field_name' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'description' => $description,
            'comment' => $comment
        ]);
    }
}
