<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ArquivoTarefa;
use App\Models\TarefaComentario;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\User;

class Tarefa extends Model
{
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'data_vencimento',
        'criado_por'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
    ];

    // Relacionamentos
    public function responsaveis(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tarefa_responsaveis')
                    ->withPivot('data_atribuicao', 'atribuido_por')
                    ->withTimestamps();
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function arquivos()
    {
        return $this->hasMany(ArquivoTarefa::class);
    }

    public function comentarios()
    {
        return $this->hasMany(TarefaComentario::class)->whereNull('parent_id')->with(['user', 'respostas.user'])->latest();
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    // Métodos auxiliares
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'novo' => 'primary',
            'em_andamento' => 'warning',
            'pendente' => 'info',
            'concluido' => 'success',
            default => 'secondary',
        };
    }

    public function getPrioridadeColorAttribute(): string
    {
        return match($this->prioridade) {
            'baixa' => 'success',
            'media' => 'warning',
            'alta' => 'danger',
            default => 'secondary',
        };
    }
}
