<?php
// app/Models/Task.php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\User;
use App\Models\TaskTemplate;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'titulo',
        'descricao',
        'tipo',
        'data_de_vencimento',
        'cliente_id',
        'status',
    ];

    /**
     * Relação com Empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relação muitos-para-muitos com User.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Relação com Cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relação com TaskTemplate.
     */
    public function taskTemplate()
    {
        return $this->belongsTo(TaskTemplate::class);
    }
}
