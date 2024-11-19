<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Empresa;
use App\Models\Task;

class TaskType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'color',
        'requires_approval',
        'ativo',
        'empresa_id'
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'ativo' => 'boolean'
    ];

    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
