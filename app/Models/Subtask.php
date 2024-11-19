<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Task;
use App\Models\User;

class Subtask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'title',
        'description',
        'completed',
        'assigned_to',
        'completed_at'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    // Relacionamentos
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Métodos
    public function markAsCompleted()
    {
        $this->update([
            'completed' => true,
            'completed_at' => now()
        ]);
    }

    public function markAsIncomplete()
    {
        $this->update([
            'completed' => false,
            'completed_at' => null
        ]);
    }
}
