<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nome', 'data_inicio_plano', 'plano', 'razao_social', 'cnpj'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}