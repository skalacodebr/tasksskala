@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho com Filtros -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Dashboard Financeira</h1>
        
        <form method="GET" action="{{ route('admin.dashboard-financeira.index') }}" class="flex space-x-2">
            <select name="mes" class="rounded-md border-gray-600" onchange="this.form.submit()">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $mesAtual == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->locale('pt_BR')->monthName }}
                    </option>
                @endfor
            </select>
            <select name="ano" class="rounded-md border-gray-600" onchange="this.form.submit()">
                @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                    <option value="{{ $i }}" {{ $anoAtual == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card-dark rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-dark">Receitas</p>
                    <p class="text-2xl font-bold text-green-600">R$ {{ number_format($receitasMes, 2, ',', '.') }}</p>
                </div>
                <i class="fas fa-arrow-up text-3xl text-green-500"></i>
            </div>
        </div>

        <div class="card-dark rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-dark">Despesas</p>
                    <p class="text-2xl font-bold text-red-600">R$ {{ number_format($despesasMes, 2, ',', '.') }}</p>
                </div>
                <i class="fas fa-arrow-down text-3xl text-red-500"></i>
            </div>
        </div>

        <div class="card-dark rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-dark">Lucro/Prejuízo</p>
                    <p class="text-2xl font-bold {{ $lucroMes >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        R$ {{ number_format($lucroMes, 2, ',', '.') }}
                    </p>
                </div>
                <i class="fas fa-chart-line text-3xl {{ $lucroMes >= 0 ? 'text-green-500' : 'text-red-500' }}"></i>
            </div>
        </div>

        <div class="card-dark rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-dark">Margem de Lucro</p>
                    <p class="text-2xl font-bold {{ $margemLucro >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $margemLucro }}%
                    </p>
                </div>
                <i class="fas fa-percentage text-3xl {{ $margemLucro >= 0 ? 'text-green-500' : 'text-red-500' }}"></i>
            </div>
        </div>
    </div>

    <!-- Indicadores de Custo -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-dark rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">Custo com Pessoal</h3>
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200">
                            {{ $percentualPessoal }}% do faturamento
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-red-600">
                            R$ {{ number_format($despesasPorTipoCusto['pessoal'], 2, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-200">
                    <div style="width:{{ min($percentualPessoal, 100) }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                </div>
            </div>
        </div>

        <div class="card-dark rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">Custos Fixos</h3>
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-purple-600 bg-purple-200">
                            {{ $percentualFixo }}% do faturamento
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-purple-600">
                            R$ {{ number_format($despesasPorTipoCusto['fixo'], 2, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-purple-200">
                    <div style="width:{{ min($percentualFixo, 100) }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-purple-500"></div>
                </div>
            </div>
        </div>

        <div class="card-dark rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">Custos Variáveis</h3>
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-yellow-600 bg-yellow-200">
                            {{ $percentualVariavel }}% do faturamento
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-yellow-600">
                            R$ {{ number_format($despesasPorTipoCusto['variavel'], 2, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-yellow-200">
                    <div style="width:{{ min($percentualVariavel, 100) }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Despesas por Categoria -->
        <div class="card-dark rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Despesas por Categoria</h2>
            
            @if($despesasPorCategoria->count() > 0)
                <canvas id="despesasChart" width="400" height="300"></canvas>
                
                <div class="mt-4 space-y-2">
                    @foreach($despesasPorCategoria as $item)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-800 rounded">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded mr-2" style="background-color: {{ $item['cor'] }}"></div>
                                <span class="text-sm">{{ $item['categoria'] }}</span>
                                @if($item['tipo_custo'])
                                    <span class="text-xs text-muted-dark ml-2">({{ ucfirst($item['tipo_custo']) }})</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold">R$ {{ number_format($item['valor'], 2, ',', '.') }}</span>
                                <span class="text-xs text-muted-dark ml-2">{{ $item['percentual'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted-dark text-center py-8">Nenhuma despesa registrada no período.</p>
            @endif
        </div>

        <!-- Receitas por Categoria -->
        <div class="card-dark rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Receitas por Categoria</h2>
            
            @if($receitasPorCategoria->count() > 0)
                <canvas id="receitasChart" width="400" height="300"></canvas>
                
                <div class="mt-4 space-y-2">
                    @foreach($receitasPorCategoria as $item)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-800 rounded">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded mr-2" style="background-color: {{ $item['cor'] }}"></div>
                                <span class="text-sm">{{ $item['categoria'] }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold">R$ {{ number_format($item['valor'], 2, ',', '.') }}</span>
                                <span class="text-xs text-muted-dark ml-2">{{ $item['percentual'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted-dark text-center py-8">Nenhuma receita registrada no período.</p>
            @endif
        </div>
    </div>

    <!-- Evolução Mensal -->
    <div class="mt-8 card-dark rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Evolução Mensal (Últimos 12 meses)</h2>
        <canvas id="evolucaoChart" width="400" height="200"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance optimizations for Chart.js
Chart.defaults.animation = false;
Chart.defaults.elements.line.borderWidth = 2;
Chart.defaults.elements.point.radius = 0;
Chart.defaults.elements.point.hoverRadius = 4;

// Função para criar gráficos com lazy loading
function createChartWhenVisible(elementId, createChartFn) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                createChartFn();
                observer.disconnect();
            }
        });
    }, {
        rootMargin: '50px'
    });
    
    observer.observe(element);
}

document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Despesas com otimizações
    @if($despesasPorCategoria->count() > 0)
        createChartWhenVisible('despesasChart', function() {
            const despesasCtx = document.getElementById('despesasChart').getContext('2d', { 
                willReadFrequently: false,
                alpha: false 
            });
            
            new Chart(despesasCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($despesasPorCategoria->pluck('categoria')) !!},
                    datasets: [{
                        data: {!! json_encode($despesasPorCategoria->pluck('valor')) !!},
                        backgroundColor: {!! json_encode($despesasPorCategoria->pluck('cor')) !!},
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            animation: false
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    @endif

    // Gráfico de Receitas com otimizações
    @if($receitasPorCategoria->count() > 0)
        createChartWhenVisible('receitasChart', function() {
            const receitasCtx = document.getElementById('receitasChart').getContext('2d', { 
                willReadFrequently: false,
                alpha: false 
            });
            
            new Chart(receitasCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($receitasPorCategoria->pluck('categoria')) !!},
                    datasets: [{
                        data: {!! json_encode($receitasPorCategoria->pluck('valor')) !!},
                        backgroundColor: {!! json_encode($receitasPorCategoria->pluck('cor')) !!},
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            animation: false
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    @endif

    // Gráfico de Evolução com otimizações e decimação
    createChartWhenVisible('evolucaoChart', function() {
        const evolucaoCtx = document.getElementById('evolucaoChart').getContext('2d', { 
            willReadFrequently: false,
            alpha: false 
        });
        
        new Chart(evolucaoCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($evolucaoMensal)->map(function($item) { return $item['mes'] . '/' . $item['ano']; })) !!},
                datasets: [{
                    label: 'Receitas',
                    data: {!! json_encode(collect($evolucaoMensal)->pluck('receitas')) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: false
                }, {
                    label: 'Despesas',
                    data: {!! json_encode(collect($evolucaoMensal)->pluck('despesas')) !!},
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: false
                }, {
                    label: 'Lucro',
                    data: {!! json_encode(collect($evolucaoMensal)->pluck('lucro')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        enabled: true,
                        animation: false
                    },
                    decimation: {
                        enabled: true,
                        algorithm: 'lttb',
                        samples: 50
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 8
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            },
                            maxTicksLimit: 6
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0
                    }
                }
            }
        });
    });
});
</script>
@endsection