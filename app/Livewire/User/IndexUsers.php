<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndexUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $titulo = 'Usuários';
    
    // Campos para o modal de criação/edição
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRole;
    public $editingUser = null;
    public $isEditing = false;
    public $roles;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
        'selectedRole' => 'required|exists:roles,id'
    ];

    public function mount()
    {
        $this->resetForm();
        $this->roles = Role::all();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRole = '';
        $this->editingUser = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'selectedRole' => 'required|exists:roles,id'
        ]);

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'empresa_id' => auth()->user()->empresa_id
            ]);

            $user->roles()->sync([$this->selectedRole]);

            $this->dispatch('hideModal');
            $this->resetForm();
            $this->dispatch('showToast', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    public function createUser()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->dispatch('showModal');
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->first()->id ?? null;
        $this->isEditing = true;

        $this->dispatch('show-modal');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => 'nullable|min:6|confirmed',
            'selectedRole' => 'required|exists:roles,id'
        ]);

        try {
            $user = User::findOrFail($this->userId);
            
            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if ($this->password) {
                $data['password'] = bcrypt($this->password);
            }

            $user->update($data);
            $user->roles()->sync([$this->selectedRole]);

            $this->dispatch('hideModal');
            $this->resetForm();
            $this->dispatch('showToast', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            // Não permitir excluir o próprio usuário
            if ($user->id === Auth::id()) {
                $this->dispatch('showError', 'Você não pode excluir seu próprio usuário!');
                return;
            }
            $user->delete();
            $this->dispatch('showToast', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users = User::where('empresa_id', auth()->user()->empresa_id)
            ->where('id', '!=', auth()->id())
            ->with('roles')
            ->paginate(10);

        return view('livewire.user.index-users', [
            'users' => $users,
            'roles' => $this->roles
        ])->layout('layouts.app');
    }
}
