@extends('layouts.master')

@section('title')
Pagamento PIX
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-4">Pagamento via PIX</h2>
                <p class="mb-4">Valor: R$ {{ number_format($valor, 2, ',', '.') }}</p>

                @if($qrcode)
                    <div class="flex justify-center mb-4">
                        <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code PIX" class="w-48 h-48">
                    </div>
                @else
                    <div class="text-red-600 mb-4">
                        QR Code não disponível no momento. Por favor, tente novamente.
                    </div>
                @endif

                @if($qrCodeText)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Código PIX Copia e Cola</p>
                        <div class="flex justify-center items-center space-x-2">
                            <input type="text" value="{{ $qrCodeText }}" id="pixCode" readonly 
                                class="border rounded px-3 py-2 w-full max-w-md text-sm bg-gray-50">
                            <button onclick="copyPixCode()" 
                                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                Copiar
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-red-600 mb-4">
                        Código PIX não disponível no momento. Por favor, tente novamente.
                    </div>
                @endif

                <div class="mt-6">
                    <p class="text-sm text-gray-600 mb-2">Instruções:</p>
                    <ol class="text-left max-w-md mx-auto text-sm text-gray-600 space-y-2">
                        <li>1. Abra o app do seu banco</li>
                        <li>2. Escolha pagar via PIX</li>
                        <li>3. Escaneie o QR Code ou cole o código PIX</li>
                        <li>4. Confirme os dados e finalize o pagamento</li>
                    </ol>
                </div>

                <div class="mt-8">
                    <a href="{{ route('painel.dashboard') }}" 
                        class="text-indigo-600 hover:text-indigo-800">
                        Voltar para o Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function copyPixCode() {
        const pixCode = document.getElementById('pixCode');
        pixCode.select();
        document.execCommand('copy');
        
        // Feedback visual
        const button = event.currentTarget;
        const originalText = button.textContent;
        button.textContent = 'Copiado!';
        button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        }, 2000);
    }
</script>
@endsection
