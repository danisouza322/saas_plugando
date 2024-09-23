<div>
    <h1>Lista de Clientes</h1>

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Razão Social</th>
                <th>Nome Fantasia</th>
                <!-- Outros cabeçalhos -->
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->razao_social }}</td>
                    <td>{{ $cliente->nome_fantasia }}</td>
                    <!-- Outros dados -->
                    <td>
                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        <!-- Botão de exclusão -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
