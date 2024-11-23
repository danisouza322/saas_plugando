<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Cards de estatísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total de Clientes</p>
                            <h4 class="fs-22 fw-semibold mb-0">
                                <span class="counter-value" data-target="{{ $totalClientes }}">0</span>
                            </h4>
                            <p class="text-muted mt-2 mb-0">
                                <span class="badge bg-light text-success mb-0">
                                    <i class="ri-arrow-up-line align-middle"></i> Ativos
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="fas fa-users text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total de Certificados</p>
                            <h4 class="fs-22 fw-semibold mb-0">
                                <span class="counter-value" data-target="{{ $totalCertificados }}">0</span>
                            </h4>
                            <p class="text-muted mt-2 mb-0">
                                <span class="badge bg-light text-success mb-0">
                                    <i class="ri-arrow-up-line align-middle"></i> Emitidos
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded fs-3">
                                <i class="fas fa-certificate text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Certificados do Mês</p>
                            <h4 class="fs-22 fw-semibold mb-0">
                                <span class="counter-value" data-target="{{ $certificadosPorMes[date('n')-1] }}">0</span>
                            </h4>
                            <p class="text-muted mt-2 mb-0">
                                <span class="badge bg-light text-info mb-0">
                                    <i class="ri-calendar-line align-middle"></i> {{ date('M/Y') }}
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                <i class="fas fa-calendar-check text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Média Mensal</p>
                            <h4 class="fs-22 fw-semibold mb-0">
                                <span class="counter-value" data-target="{{ round(array_sum($certificadosPorMes)/12, 1) }}">0</span>
                            </h4>
                            <p class="text-muted mt-2 mb-0">
                                <span class="badge bg-light text-primary mb-0">
                                    <i class="ri-line-chart-line align-middle"></i> Certificados
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="fas fa-chart-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico e Últimos Registros -->
    <div class="row">
        <!-- Gráfico de certificados -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Certificados Emitidos em {{ date('Y') }}</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fw-semibold text-uppercase fs-12">Período: </span>
                                <span class="text-muted">Anual<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Últimos 30 dias</a>
                                <a class="dropdown-item" href="#">Últimos 6 meses</a>
                                <a class="dropdown-item" href="#">Anual</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="certificados-por-mes" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <!-- Últimos Certificados -->
        <div class="col-xl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Últimos Certificados</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('painel.certificados.index') }}" class="btn btn-soft-info btn-sm">
                            <i class="ri-eye-line align-middle"></i> Ver Todos
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosCertificados as $certificado)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-primary rounded">
                                                        {{ $certificado->cliente ? substr($certificado->cliente->razao_social, 0, 1) : 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    {{ $certificado->cliente ? $certificado->cliente->razao_social : 'Cliente não encontrado' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $certificado->tipo }}</td>
                                        <td>{{ $certificado->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhum certificado encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Clientes -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Últimos Clientes</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('painel.clientes.index') }}" class="btn btn-soft-info btn-sm">
                            <i class="ri-eye-line align-middle"></i> Ver Todos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col" style="width: 40%;">Razão Social</th>
                                    <th scope="col" style="width: 20%;">CPF/CNPJ</th>
                                    <th scope="col" style="width: 20%;">Regime Tributário</th>
                                    <th scope="col" style="width: 20%;">Data Cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosClientes as $cliente)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-info rounded">
                                                        {{ substr($cliente->razao_social, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    {{ $cliente->razao_social }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $cliente->cpf_cnpj }}</td>
                                        <td>{{ $cliente->regime_tributario }}</td>
                                        <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Nenhum cliente encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Gráfico de certificados por mês
        var options = {
            series: [{
                name: 'Certificados',
                data: @json($certificadosPorMes)
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
            },
            colors: ['#3577f1'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            tooltip: {
                x: {
                    format: 'MM'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        var chart = new ApexCharts(document.querySelector("#certificados-por-mes"), options);
        chart.render();
    </script>
    @endpush
</div>
