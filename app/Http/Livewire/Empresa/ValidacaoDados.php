<?php

namespace App\Http\Livewire\Empresa;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ValidacaoDados extends Component
{
    public $empresa;
    public $missingFields = [];
    public $showAlert = false;

    public function mount()
    {
        $this->empresa = Auth::user()->empresa;
        $this->checkMissingFields();
    }

    public function checkMissingFields()
    {
        $requiredFields = [
            'razao_social' => 'Razão Social',
            'cnpj' => 'CNPJ',
            'email' => 'E-mail',
            'telefone' => 'Telefone',
            'cep' => 'CEP',
            'endereco' => 'Endereço',
            'numero' => 'Número',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'estado' => 'Estado'
        ];

        $this->missingFields = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($this->empresa->$field)) {
                $this->missingFields[] = $label;
            }
        }

        $this->showAlert = !empty($this->missingFields);
    }

    public function render()
    {
        return view('livewire.empresa.validacao-dados');
    }
}
