@extends('layouts.master')

@section('title')
    Boleto de Pagamento
@endsection

@section('css')
    <!-- Adicione aqui seus estilos específicos -->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Boleto de Pagamento</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($error))
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @else
                        <div class="text-center mb-4">
                            <h5>Valor: R$ {{ number_format($valor, 2, ',', '.') }}</h5>
                            <p>Vencimento: {{ \Carbon\Carbon::parse($vencimento)->format('d/m/Y') }}</p>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-6 text-center">
                                @if($boletoUrl)
                                    <div class="mb-4">
                                        <a href="{{ $boletoUrl }}" target="_blank" class="btn btn-primary btn-lg w-100">
                                            <i class="ri-file-pdf-line me-2"></i>Visualizar Boleto
                                        </a>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <a href="{{ $boletoUrl }}" download class="btn btn-success btn-lg w-100">
                                            <i class="ri-download-line me-2"></i>Baixar Boleto
                                        </a>
                                    </div>

                                    @if($codigoBarras)
                                        <div class="mt-4">
                                            <h6>Código de Barras:</h6>
                                            <div class="border p-3 bg-light rounded">
                                                <code id="codigoBarras" class="user-select-all">{{ $codigoBarras }}</code>
                                            </div>
                                            <button class="btn btn-secondary mt-2" onclick="copyToClipboard()">
                                                <i class="ri-file-copy-line me-1"></i>Copiar Código
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        <i class="ri-error-warning-line me-2"></i>
                                        Boleto não disponível no momento. Por favor, tente novamente em alguns instantes.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-muted">
                                <small>
                                    <i class="ri-information-line me-1"></i>
                                    Após o pagamento, pode levar até 2 dias úteis para a confirmação.
                                    <br>Em caso de dúvidas, entre em contato com nosso suporte.
                                </small>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard() {
            const codigoBarras = document.getElementById('codigoBarras').textContent;
            navigator.clipboard.writeText(codigoBarras).then(function() {
                // Criar um elemento de toast
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3';
                toast.style.zIndex = '5';
                toast.innerHTML = `
                    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="ri-check-line me-2"></i>Código de barras copiado com sucesso!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Inicializar e mostrar o toast
                const toastElement = toast.querySelector('.toast');
                const bsToast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                });
                bsToast.show();
                
                // Remover o elemento depois que o toast for escondido
                toastElement.addEventListener('hidden.bs.toast', function () {
                    toast.remove();
                });
            }).catch(function(err) {
                console.error('Erro ao copiar:', err);
                alert('Não foi possível copiar o código. Por favor, tente copiar manualmente.');
            });
        }
    </script>
    @endpush
@endsection
