<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;

class IndexClientes extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $selectedClientes = [];
    public $selectAll = false;

    protected $paginationTheme = 'bootstrap'; // Para usar a paginação com estilo Bootstrap

    protected $listeners = ['deleteCliente'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if($this->sortField === $field){
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }else{
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedClientes = Cliente::where('empresa_id', auth()->user()->empresa_id)
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedClientes = [];
        }
    }

    public function deleteSelected()
    {
        Cliente::whereIn('id', $this->selectedClientes)->delete();
        $this->selectedClientes = [];
        $this->selectAll = false;
        session()->flash('message', 'Clientes selecionados foram excluídos com sucesso.');
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('confirmDelete', ['id' => $id]);
    }

    public function deleteCliente($id)
    {
        Cliente::find($id)->delete();
        session()->flash('message', 'Cliente excluído com sucesso.');
    }

    public function render()
    {

        $clientes = Cliente::with(['inscricaoEstadualAtiva'])
        ->where('empresa_id', auth()->user()->empresa_id)
        ->when($this->search, function($query) {
            $query->where(function($query){
                $query->where('razao_social', 'like', '%'.$this->search.'%')
                      ->orWhere('cnpj', 'like', '%'.$this->search.'%')
                      ->orWhere('regime_tributario', 'like', '%'.$this->search.'%')
                      ->orWhereHas('inscricoesEstaduais', function($q){
                          $q->where('numero', 'like', '%'.$this->search.'%');
                      });
            });
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(3);

        return view('livewire.cliente.index-clientes', [
            'clientes' => $clientes,
        ])->layout('layouts.app');
    }
}

