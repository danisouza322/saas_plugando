<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarefaComentario extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tarefa_id',
        'user_id',
        'conteudo',
        'anexos',
        'parent_id'
    ];

    protected $casts = [
        'anexos' => 'array'
    ];

    public function tarefa()
    {
        return $this->belongsTo(Tarefa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respostas()
    {
        return $this->hasMany(TarefaComentario::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(TarefaComentario::class, 'parent_id');
    }
}
