<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtividadeEconomica extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'codigo',
        'descricao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

