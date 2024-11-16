<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Cliente;
use App\Models\Empresa;

class Certificado extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'data_vencimento',
        'arquivo_path',
        'senha',
        'tipo',
        'cnpj_cpf'
    ];

    protected $casts = [
        'data_vencimento' => 'date'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
