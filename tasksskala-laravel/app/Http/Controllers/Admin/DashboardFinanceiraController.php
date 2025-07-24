<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use App\Models\CategoriaFinanceira;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardFinanceiraController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $mesAtual = $request->get('mes', Carbon::now()->month);
        $anoAtual = $request->get('ano', Carbon::now()->year);
        $periodo = Carbon::create($anoAtual, $mesAtual);
        
        // Atualizar status de contas vencidas
        ContaPagar::atualizarContasVencidas();
        ContaReceber::atualizarContasVencidas();
        
        // Receitas e Despesas do mês
        $receitasMes = ContaReceber::whereMonth('data_vencimento', $mesAtual)
            ->whereYear('data_vencimento', $anoAtual)
            ->where('status', 'recebido')
            ->sum('valor');
            
        $despesasMes = ContaPagar::whereMonth('data_vencimento', $mesAtual)
            ->whereYear('data_vencimento', $anoAtual)
            ->where('status', 'pago')
            ->sum('valor');
            
        $lucroMes = $receitasMes - $despesasMes;
        
        // Análise por categoria - Despesas
        $despesasPorCategoria = ContaPagar::with('categoria')
            ->whereMonth('data_vencimento', $mesAtual)
            ->whereYear('data_vencimento', $anoAtual)
            ->where('status', 'pago')
            ->get()
            ->groupBy('categoria_id')
            ->map(function ($contas, $categoriaId) {
                $categoria = CategoriaFinanceira::find($categoriaId);
                return [
                    'categoria' => $categoria ? $categoria->nome : 'Sem categoria',
                    'cor' => $categoria ? $categoria->cor : '#6B7280',
                    'tipo_custo' => $categoria ? $categoria->tipo_custo : null,
                    'valor' => $contas->sum('valor'),
                    'quantidade' => $contas->count(),
                    'percentual' => 0 // será calculado depois
                ];
            })
            ->sortByDesc('valor')
            ->values();
            
        // Calcular percentuais
        if ($despesasMes > 0) {
            $despesasPorCategoria = $despesasPorCategoria->map(function ($item) use ($despesasMes) {
                $item['percentual'] = round(($item['valor'] / $despesasMes) * 100, 2);
                return $item;
            });
        }
        
        // Análise por tipo de custo
        $despesasPorTipoCusto = [
            'fixo' => 0,
            'variavel' => 0,
            'pessoal' => 0,
            'administrativo' => 0,
            'outros' => 0
        ];
        
        $contasPagas = ContaPagar::with('categoria')
            ->whereMonth('data_vencimento', $mesAtual)
            ->whereYear('data_vencimento', $anoAtual)
            ->where('status', 'pago')
            ->get();
            
        foreach ($contasPagas as $conta) {
            if ($conta->categoria && $conta->categoria->tipo_custo) {
                $despesasPorTipoCusto[$conta->categoria->tipo_custo] += $conta->valor;
            } else {
                $despesasPorTipoCusto['outros'] += $conta->valor;
            }
        }
        
        // Receitas por categoria
        $receitasPorCategoria = ContaReceber::with('categoria')
            ->whereMonth('data_vencimento', $mesAtual)
            ->whereYear('data_vencimento', $anoAtual)
            ->where('status', 'recebido')
            ->get()
            ->groupBy('categoria_id')
            ->map(function ($contas, $categoriaId) {
                $categoria = CategoriaFinanceira::find($categoriaId);
                return [
                    'categoria' => $categoria ? $categoria->nome : 'Sem categoria',
                    'cor' => $categoria ? $categoria->cor : '#6B7280',
                    'valor' => $contas->sum('valor'),
                    'quantidade' => $contas->count(),
                    'percentual' => 0
                ];
            })
            ->sortByDesc('valor')
            ->values();
            
        // Calcular percentuais de receitas
        if ($receitasMes > 0) {
            $receitasPorCategoria = $receitasPorCategoria->map(function ($item) use ($receitasMes) {
                $item['percentual'] = round(($item['valor'] / $receitasMes) * 100, 2);
                return $item;
            });
        }
        
        // Evolução mensal (últimos 12 meses)
        $evolucaoMensal = [];
        for ($i = 11; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $mes = $data->month;
            $ano = $data->year;
            
            $receitas = ContaReceber::whereMonth('data_vencimento', $mes)
                ->whereYear('data_vencimento', $ano)
                ->where('status', 'recebido')
                ->sum('valor');
                
            $despesas = ContaPagar::whereMonth('data_vencimento', $mes)
                ->whereYear('data_vencimento', $ano)
                ->where('status', 'pago')
                ->sum('valor');
                
            $evolucaoMensal[] = [
                'mes' => $data->locale('pt_BR')->monthName,
                'ano' => $ano,
                'receitas' => $receitas,
                'despesas' => $despesas,
                'lucro' => $receitas - $despesas
            ];
        }
        
        // Indicadores
        $margemLucro = $receitasMes > 0 ? round(($lucroMes / $receitasMes) * 100, 2) : 0;
        $percentualPessoal = $receitasMes > 0 ? round(($despesasPorTipoCusto['pessoal'] / $receitasMes) * 100, 2) : 0;
        $percentualFixo = $receitasMes > 0 ? round(($despesasPorTipoCusto['fixo'] / $receitasMes) * 100, 2) : 0;
        $percentualVariavel = $receitasMes > 0 ? round(($despesasPorTipoCusto['variavel'] / $receitasMes) * 100, 2) : 0;
        
        return view('admin.dashboard-financeira.index', compact(
            'mesAtual',
            'anoAtual',
            'receitasMes',
            'despesasMes',
            'lucroMes',
            'margemLucro',
            'despesasPorCategoria',
            'despesasPorTipoCusto',
            'receitasPorCategoria',
            'evolucaoMensal',
            'percentualPessoal',
            'percentualFixo',
            'percentualVariavel'
        ));
    }
}