<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscricaoEstadual extends Model
{

    protected $table = 'inscricoes_estaduais';
    
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'estado',
        'numero',
        'ativa',
        'data_status',
        'status_id',
        'status_texto',
        'tipo_id',
        'tipo_texto',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}