<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocioAdministrador extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'entrada',
        'socio',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
