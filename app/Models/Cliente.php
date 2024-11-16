<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'cnpj',
        'cpf',
        'razao_social',
        'nome_fantasia',
        'regime_tributario',
        'atividadesEconomicas',
        'data_abertura',
        'porte',
        'capital_social',
        'natureza_juridica',
        'tipo',
        'situacao_cadastral',
        'simples',
        'mei'
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

    /**
     * Relação com TaskTemplates (se aplicável).
     */
    public function taskTemplates()
    {
        return $this->hasMany(TaskTemplate::class);
    }

    /**
     * Relação com Tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getRegimeTributarioLabelAttribute()
        {
            $labels = [
                'simples_nacional' => 'Simples Nacional',
                'lucro_presumido' => 'Lucro Presumido',
                'lucro_real'      => 'Lucro Real',
                'mei'             => 'Mei'
                // Adicione outros mapeamentos conforme necessário
            ];

            return $labels[$this->regime_tributario] ?? $this->regime_tributario;
        }

    public function setCnpjAttribute($value)
        {
            // Remove tudo que não seja dígito
            $this->attributes['cnpj'] = preg_replace('/\D/', '', $value);
        }

    public function getCnpjAttribute($value)
        {
            // Formata o CNPJ com pontuação
            return $this->formatCnpj($value);
        }

        private function formatCnpj($cnpj)
        {
            // Verifica se o CNPJ tem 14 dígitos
            if (strlen($cnpj) != 14) {
                return $cnpj; // Retorna o valor original se não tiver 14 dígitos
            }
        
            // Formata o CNPJ: 99.999.999/9999-99
            return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $cnpj);
        }

        public function inscricaoEstadualAtiva()
{
             return $this->hasOne(InscricaoEstadual::class)
                ->where('ativa', 'ativa')
                ->orderBy('id');
}
           
}
