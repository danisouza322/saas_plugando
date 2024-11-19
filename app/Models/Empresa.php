<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'nome', 
        'data_inicio_plano', 
        'plano', 
        'razao_social', 
        'cnpj',
        'email',
        'cep',
        'endereco',
        'bairro',
        'estado',
        'numero',
        'complemento',
        'cidade',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_inicio_plano' => 'datetime'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}