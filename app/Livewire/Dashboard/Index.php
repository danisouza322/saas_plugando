<?php

namespace App\Livewire\Dashboard;

use App\Models\Cliente;
use App\Models\Certificado;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $certificadosPorMes;
    public $titulo = 'Dashboard';

    public function mount()
    {
        $this->certificadosPorMes = $this->getCertificadosPorMes();
    }

    public function getTotalClientes()
    {
        return Cliente::where('empresa_id', Auth::user()->empresa_id)->count();
    }

    public function getTotalCertificados()
    {
        return Certificado::where('empresa_id', Auth::user()->empresa_id)->count();
    }

    public function getCertificadosPorMes()
    {
        $certificados = Certificado::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->where('empresa_id', Auth::user()->empresa_id)
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        // Inicializa array com 12 meses
        $dados = array_fill(1, 12, 0);

        // Preenche com os dados reais
        foreach ($certificados as $certificado) {
            $dados[$certificado->mes] = $certificado->total;
        }

        return array_values($dados); // Retorna apenas os valores, mantendo a ordem dos meses
    }

    public function render()
    {
        $totalClientes = $this->getTotalClientes();
        $totalCertificados = $this->getTotalCertificados();

        return view('livewire.dashboard.index', [
            'totalClientes' => $totalClientes,
            'totalCertificados' => $totalCertificados,
            'certificadosPorMes' => $this->certificadosPorMes,
        ])->layout('layouts.app');;
    }
}
