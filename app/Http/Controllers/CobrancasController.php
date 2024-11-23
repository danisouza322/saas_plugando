<?php

namespace App\Http\Controllers;

use App\Models\AsaasCobranca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CobrancasController extends Controller
{
    public function index()
    {
        $empresa = Auth::user()->empresa;
        $cobrancas = AsaasCobranca::where('empresa_id', $empresa->id)
            ->orderBy('vencimento', 'desc')
            ->paginate(10);

        return view('cobrancas.index', compact('cobrancas'));
    }

    public function show(AsaasCobranca $cobranca)
    {
        // Verifica se a cobrança pertence à empresa do usuário
        if ($cobranca->empresa_id !== Auth::user()->empresa->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('cobrancas.show', compact('cobranca'));
    }
}
