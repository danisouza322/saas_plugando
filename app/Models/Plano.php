<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plano extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'valor',
        'dias_teste',
        'recursos',
        'ativo'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'dias_teste' => 'integer',
        'recursos' => 'array',
        'ativo' => 'boolean'
    ];

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }

    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
