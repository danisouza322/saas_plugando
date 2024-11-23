<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'razao_social',
        'cnpj',
        'email',
        'telefone',
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
        'ativo' => 'boolean'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}