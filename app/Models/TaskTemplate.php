<?php

// app/Models/TaskTemplate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Cliente;

class TaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', // Adicionado
        'titulo',
        'descricao',
        'user_id',
        'cliente_id',
        'frequencia',
        'dia_do_mes',
    ];

    /**
     * Relação com Empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relação com User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relação com Cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
