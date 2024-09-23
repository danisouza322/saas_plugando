<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'cpf_cnpj',
        'razao_social',
        'nome_fantasia',
        'regime_tributario',
        'data_abertura',
        'porte',
        'capital_social',
        'natureza_juridica',
        'tipo',
        'situacao_cadastral',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function endereco()
    {
        return $this->hasOne(Endereco::class);
    }

    public function inscricoesEstaduais()
    {
        return $this->hasMany(InscricaoEstadual::class);
    }

    public function sociosAdministradores()
    {
        return $this->hasMany(SocioAdministrador::class);
    }

    public function atividadesEconomicas()
    {
        return $this->hasMany(AtividadeEconomica::class);
    }
}
