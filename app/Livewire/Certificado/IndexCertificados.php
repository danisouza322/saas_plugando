<?php

namespace App\Livewire\Certificado;

use App\Models\Certificado;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class IndexCertificados extends Component
{
    use WithPagination;

    public $search = '';
    public $titulo = 'Certificados';

    protected $paginationTheme = 'bootstrap';

    #[On('refresh-list')]
    public function refreshList()
    {
        // A lista será atualizada automaticamente
    }

    #[On('excluirCertificado')]
    public function excluirCertificado($id)
    {
        try {
            $certificado = Certificado::where('empresa_id', Auth::user()->empresa_id)
                                    ->where('id', $id)
                                    ->firstOrFail();
            
            if ($certificado->arquivo_path && Storage::disk('public')->exists($certificado->arquivo_path)) {
                Storage::disk('public')->delete($certificado->arquivo_path);
            }
            
            $certificado->delete();
            
            $this->dispatch('certificadoExcluido');
            session()->flash('message', 'Certificado excluído com sucesso.');
        } catch (\Exception $e) {
            $this->dispatch('erroExclusao', ['message' => 'Erro ao excluir certificado: ' . $e->getMessage()]);
        }
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirmDelete', ['id' => $id]);
    }

    public function createCertificado()
    {
        $this->dispatch('showModal');
    }

    public function editCertificado($id)
    {
        $this->dispatch('showModal', ['certificadoId' => $id]);
    }

    public function render()
    {
        $certificados = Certificado::query()
            ->where('empresa_id', Auth::user()->empresa_id)
            ->when($this->search, function ($query) {
                $query->where('nome', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.certificado.index-certificados', [
            'certificados' => $certificados
        ])->layout('layouts.app');
    }
}
