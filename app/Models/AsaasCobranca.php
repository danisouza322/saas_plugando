<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsaasCobranca extends Model
{
    use HasFactory;

    protected $table = 'asaas_cobrancas';

    protected $fillable = [
        'empresa_id',
        'asaas_id',
        'valor',
        'status',
        'forma_pagamento',
        'data_vencimento',
        'data_pagamento',
        'valor_pago',
        'link_pagamento',
        'link_boleto',
        'codigo_barras',
    ];

    protected $casts = [
        'valor' => 'float',
        'valor_pago' => 'float',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Status possíveis
    const STATUS_PENDING = 'PENDING';
    const STATUS_RECEIVED = 'RECEIVED';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_OVERDUE = 'OVERDUE';
    const STATUS_REFUNDED = 'REFUNDED';
    const STATUS_RECEIVED_IN_CASH = 'RECEIVED_IN_CASH';

    // Scopes
    public function scopePendentes($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePagas($query)
    {
        return $query->whereIn('status', [self::STATUS_RECEIVED, self::STATUS_CONFIRMED, self::STATUS_RECEIVED_IN_CASH]);
    }

    public function scopeVencidas($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }

    // Helpers
    public function isPaga()
    {
        return in_array($this->status, [self::STATUS_RECEIVED, self::STATUS_CONFIRMED, self::STATUS_RECEIVED_IN_CASH]);
    }

    public function isPendente()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVencida()
    {
        return $this->status === self::STATUS_OVERDUE;
    }
}
