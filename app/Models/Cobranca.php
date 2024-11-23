<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cobranca extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asaas_cobrancas';

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'valor',
        'status',
        'descricao',
        'data_vencimento',
        'metodo_pagamento',
        'asaas_id',
        'link_pagamento',
        'qrcode',
        'codigo_barras',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_vencimento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'status_formatado',
        'valor_formatado',
    ];

    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Boot do modelo
    protected static function boot()
    {
        parent::boot();

        // Adiciona scope global para filtrar por empresa
        static::addGlobalScope('empresa', function ($query) {
            if (auth()->check()) {
                $query->where('empresa_id', auth()->user()->empresa_id);
            }
        });

        // Adiciona empresa_id automaticamente ao criar
        static::creating(function ($model) {
            if (auth()->check() && !$model->empresa_id) {
                $model->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    // Acessors
    public function getStatusFormatadoAttribute()
    {
        $statusMap = [
            'PENDING' => 'Pendente',
            'RECEIVED' => 'Recebido',
            'CONFIRMED' => 'Confirmado',
            'OVERDUE' => 'Vencido',
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'RECEIVED');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'CONFIRMED');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'OVERDUE');
    }
}
