<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="/" class="d-inline-block auth-logo">
                                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="20">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">Plataforma de Gestão de Certificados Digitais</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Cadastro de Nova Empresa</h5>
                                <p class="text-muted">Comece sua jornada com nossa plataforma.</p>
                            </div>
                            <div class="p-2 mt-4">
                                @if (session()->has('message'))
                                    <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                        <i class="ri-check-double-line label-icon"></i>
                                        {{ session('message') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                        <i class="ri-error-warning-line label-icon"></i>
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form wire:submit="cadastrar">
                                    <div class="mb-3">
                                        <label for="nome_empresa" class="form-label">Nome da Empresa <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('nome_empresa') is-invalid @enderror" 
                                                id="nome_empresa" 
                                                wire:model="nome_empresa" 
                                                placeholder="Digite o nome da empresa"
                                                required>
                                            @error('nome_empresa')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Seu Nome <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                                id="nome" 
                                                wire:model="nome" 
                                                placeholder="Digite seu nome completo"
                                                required>
                                            @error('nome')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Seu E-mail <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                id="email" 
                                                wire:model="email" 
                                                placeholder="Digite seu e-mail"
                                                required>
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Senha <span class="text-danger">*</span></label>
                                        <div class="position-relative auth-pass-inputgroup">
                                            <input type="password" 
                                                class="form-control pe-5 password-input @error('password') is-invalid @enderror" 
                                                id="password" 
                                                wire:model="password"
                                                placeholder="Digite sua senha"
                                                required>
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" 
                                                type="button">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirme a Senha <span class="text-danger">*</span></label>
                                        <div class="position-relative auth-pass-inputgroup">
                                            <input type="password" 
                                                class="form-control pe-5 password-input" 
                                                id="password_confirmation" 
                                                wire:model="password_confirmation"
                                                placeholder="Confirme sua senha"
                                                required>
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" 
                                                type="button">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <p class="mb-0 fs-12 text-muted fst-italic">Ao se registrar você concorda com os 
                                            <a href="#" class="text-primary text-decoration-underline fst-normal fw-medium">Termos de Uso</a>
                                        </p>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">
                                            Cadastrar Empresa e Usuário
                                        </button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Já possui uma conta? <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline">Entrar</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">
                            &copy; {{ date('Y') }} Plugando. Desenvolvido com <i class="mdi mdi-heart text-danger"></i> por Plugando
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
<script>
    // Password show & hide
    document.querySelectorAll(".password-addon").forEach(function (item) {
        item.addEventListener('click', function() {
            var passwordInput = item.closest('.auth-pass-inputgroup').querySelector('.password-input');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                item.querySelector('i').classList.remove("ri-eye-fill");
                item.querySelector('i').classList.add("ri-eye-off-fill");
            } else {
                passwordInput.type = "password";
                item.querySelector('i').classList.remove("ri-eye-off-fill");
                item.querySelector('i').classList.add("ri-eye-fill");
            }
        });
    });
</script>
@endpush