<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArquivoTarefa extends Model
{
    protected $table = 'arquivos_tarefa';

    protected $fillable = [
        'tarefa_id',
        'nome_arquivo',
        'caminho_arquivo',
        'tipo_arquivo',
        'tamanho',
        'uploaded_por'
    ];

    // Relacionamentos
    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_por');
    }

    // Métodos auxiliares
    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
    
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIconeAttribute(): string
    {
        return match($this->tipo_arquivo) {
            'pdf' => 'ri-file-pdf-line',
            'doc', 'docx' => 'ri-file-word-line',
            'xls', 'xlsx' => 'ri-file-excel-line',
            'jpg', 'jpeg', 'png', 'gif' => 'ri-image-line',
            default => 'ri-file-line',
        };
    }
}
