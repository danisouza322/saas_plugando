<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cadastro de Nova Empresa</div>

                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="cadastrar">
                        <div class="mb-3">
                            <label for="nome_empresa" class="form-label">Nome da Empresa</label>
                            <input type="text" class="form-control" id="nome_empresa" wire:model="nome_empresa">
                            @error('nome_empresa') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Seu Nome</label>
                            <input type="text" class="form-control" id="nome" wire:model="nome">
                            @error('nome') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Seu E-mail</label>
                            <input type="email" class="form-control" id="email" wire:model="email">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" wire:model="password">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirme a Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" wire:model="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary">Cadastrar Empresa e Usuário</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>