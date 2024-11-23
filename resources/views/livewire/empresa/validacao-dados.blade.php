<div>
@if($showAlert)
<div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
    <i class="ri-error-warning-line label-icon"></i><strong>Atenção</strong>
    <p class="mb-0">Para prosseguir com a assinatura, é necessário preencher os seguintes dados da empresa:</p>
    <ul class="mt-2">
        @foreach($missingFields as $field)
            <li>{{ $field }}</li>
        @endforeach
    </ul>
    <a href="{{ route('painel.empresa.editar') }}" class="btn btn-warning btn-sm mt-2">
        <i class="ri-edit-box-line align-bottom"></i> Completar Cadastro
    </a>
</div>
@endif
</div>
