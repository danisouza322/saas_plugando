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
        <div class="col-xl-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="fas fa-users text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-3">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Total de Clientes</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0">{{ $totalClientes }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="fas fa-certificate text-success"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden ms-3">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Total de Certificados</p>
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fs-4 flex-grow-1 mb-0">{{ $totalCertificados }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de certificados -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Certificados por Mês</h4>
                </div>
                <div class="card-body">
                    <div id="certificados-por-mes"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Registros -->
    <div class="row">
        <!-- Últimos Clientes -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Últimos Clientes</h5>
                        <div class="flex-shrink-0">
                            <a href="{{ route('painel.clientes.index') }}" class="btn btn-soft-info btn-sm">
                                <i class="ri-eye-line align-middle"></i> Ver Todos
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col">Razão Social</th>
                                    <th scope="col">CPF/CNPJ</th>
                                    <th scope="col">Regime Tributário</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosClientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->razao_social }}</td>
                                        <td>{{ $cliente->cpf_cnpj }}</td>
                                        <td>
                                            @switch($cliente->regime_tributario)
                                                @case('simples_nacional')
                                                    Simples Nacional
                                                    @break
                                                @case('lucro_presumido')
                                                    Lucro Presumido
                                                    @break
                                                @default
                                                    {{ $cliente->regime_tributario }}
                                            @endswitch
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhum cliente registrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos Certificados -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Últimos Certificados</h5>
                        <div class="flex-shrink-0">
                            <a href="{{ route('painel.certificados.index') }}" class="btn btn-soft-info btn-sm">
                                <i class="ri-eye-line align-middle"></i> Ver Todos
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Validade</th>
                                    <th scope="col">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosCertificados as $certificado)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xs me-2">
                                                        <span class="avatar-title rounded-circle bg-soft-success text-success">
                                                            {{ strtoupper(substr($certificado->nome, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">{{ $certificado->nome }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $certificado->tipo }}</td>
                                        <td>{{ $certificado->data_vencimento->format('d/m/Y') }}</td>
                                        <td>{{ $certificado->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Nenhum certificado registrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gráfico de certificados por mês
        var options = {
            series: [{
                name: 'Certificados',
                data: @json($certificadosPorMes)
            }],
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top'
                    },
                    colors: {
                        ranges: [{
                            from: 0,
                            to: 100,
                            color: '#405189'
                        }]
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                position: 'bottom',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: true,
                    formatter: function (val) {
                        return val;
                    }
                }
            },
            title: {
                text: 'Certificados Emitidos em ' + new Date().getFullYear(),
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };

        document.addEventListener('livewire:load', function () {
            var chart = new ApexCharts(document.querySelector("#certificados-por-mes"), options);
            chart.render();
        });
    </script>
</div>
