<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EditProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $avatar;
    public $currentAvatar;

    public $titulo;

   
    // Propriedade para armazenar mensagens de sucesso específicas para o avatar
    public $avatarMessage;

    public function mount()
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->currentAvatar = $user->avatar;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|max:1024', // Máximo de 1MB
        ];
    }

      // Método para atualizar o avatar imediatamente após a seleção
      public function updatedAvatar()
      {
        $this->validateOnly('avatar');

        if ($this->avatar) {
            $user = Auth::user();

            // Deletar o avatar antigo se existir
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Armazenar o novo avatar
            $avatarPath = $this->avatar->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();

            // Atualizar a propriedade do avatar atual
            $this->currentAvatar = $user->avatar;

            // Emitir um evento para atualizar o avatar na top bar
            $this->dispatch('avatarUpdated', asset('storage/' . $avatarPath));

             
              // Exibir mensagem de sucesso específica para o avatar
              $this->dispatch('showToast', 'Foto Atualizada com Sucesso!');
          }
      }

    public function save()
    {
        $this->validate();

        $user = Auth::user();

        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();


        $this->dispatch('showToast', 'Dados Atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.user.edit-profile')
        ->layout('layouts.app', [
            'titulo' => 'Meu Perfil', // Passando 'titulo' para o layout
        ]);
    }
}
