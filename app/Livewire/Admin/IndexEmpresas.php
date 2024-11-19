<?php

namespace App\Livewire\Admin;

use App\Models\Empresa;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexEmpresas extends Component
{
    use WithPagination;

    public $search = '';
    public $titulo = 'Gerenciar Empresas';
    protected $paginationTheme = 'bootstrap';

    // Verifica se o usuário atual é o root (ID 1)
    public function mount()
    {
        if (Auth::id() !== 1) {
            abort(403, 'Acesso não autorizado.');
        }
    }

    public function toggleStatus($empresaId)
    {
        if (Auth::id() !== 1) {
            return;
        }

        try {
            DB::beginTransaction();

            $empresa = Empresa::findOrFail($empresaId);
            $novoStatus = !$empresa->ativo;
            
            // Atualiza o status da empresa
            Empresa::where('id', $empresaId)->update(['ativo' => $novoStatus]);
            
            // Atualiza o status de todos os usuários da empresa
            User::where('empresa_id', $empresaId)->update(['ativo' => $novoStatus]);
            
            DB::commit();

            $status = $novoStatus ? 'ativada' : 'desativada';
            session()->flash('message', "Empresa {$status} com sucesso!");
            
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao alterar status da empresa: ' . $e->getMessage());
        }
    }

    public function confirmDelete($empresaId)
    {
        if (Auth::id() !== 1) {
            return;
        }

        // Não permite excluir a própria empresa (ID 1)
        if ($empresaId == 1) {
            session()->flash('error', 'Não é possível excluir a empresa principal do sistema.');
            return;
        }

        $this->dispatch('showDeleteConfirmation', ['empresaId' => $empresaId]);
    }

    public function deleteEmpresa($empresaId)
    {
        if (Auth::id() !== 1) {
            return;
        }

        try {
            DB::beginTransaction();

            // Exclui todos os usuários da empresa
            User::where('empresa_id', $empresaId)->delete();

            // Exclui a empresa
            Empresa::where('id', $empresaId)->delete();

            DB::commit();
            session()->flash('message', 'Empresa excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao excluir empresa: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $empresas = Empresa::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nome', 'like', '%' . $this->search . '%')
                        ->orWhere('created_at', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount('users')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.index-empresas', [
            'empresas' => $empresas
        ])->layout('layouts.app');
    }
}
