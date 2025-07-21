<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Cliente;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with(['cliente', 'projeto', 'respondidoPor']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('projeto_id')) {
            $query->where('projeto_id', $request->projeto_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('assunto', 'like', "%{$search}%")
                  ->orWhere('mensagem', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $feedbacks = $query->paginate(20)->withQueryString();

        // Para os filtros
        $clientes = Cliente::orderBy('nome')->get();
        $projetos = Projeto::orderBy('nome')->get();

        // Estatísticas
        $estatisticas = [
            'total' => Feedback::count(),
            'pendentes' => Feedback::pendentes()->count(),
            'respondidos' => Feedback::respondidos()->count(),
            'media_avaliacao' => Feedback::whereNotNull('avaliacao')->avg('avaliacao'),
        ];

        return view('admin.feedbacks.index', compact('feedbacks', 'clientes', 'projetos', 'estatisticas'));
    }

    public function show(Feedback $feedback)
    {
        $feedback->load(['cliente', 'projeto', 'respondidoPor']);
        
        return view('admin.feedbacks.show', compact('feedback'));
    }

    public function responder(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'resposta' => 'required|string|min:10',
            'status' => 'required|in:em_analise,respondido',
        ]);

        $feedback->update([
            'resposta' => $validated['resposta'],
            'status' => $validated['status'],
            'respondido_em' => $validated['status'] === 'respondido' ? now() : null,
            'respondido_por' => session('colaborador')->id,
        ]);

        return redirect()->route('admin.feedbacks.show', $feedback)
            ->with('success', 'Resposta enviada com sucesso!');
    }

    public function atualizarStatus(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendente,em_analise,respondido,resolvido,arquivado',
        ]);

        $updateData = ['status' => $validated['status']];

        // Se marcando como respondido e não tem resposta, definir data de resposta
        if ($validated['status'] === 'respondido' && !$feedback->respondido_em) {
            $updateData['respondido_em'] = now();
            $updateData['respondido_por'] = session('colaborador')->id;
        }

        $feedback->update($updateData);

        return redirect()->back()->with('success', 'Status atualizado com sucesso!');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('admin.feedbacks.index')
            ->with('success', 'Feedback excluído com sucesso!');
    }

    public function estatisticas()
    {
        // Estatísticas gerais
        $estatisticasGerais = [
            'total_feedbacks' => Feedback::count(),
            'pendentes' => Feedback::pendentes()->count(),
            'respondidos' => Feedback::respondidos()->count(),
            'tempo_medio_resposta' => $this->calcularTempoMedioResposta(),
            'media_avaliacao' => Feedback::whereNotNull('avaliacao')->avg('avaliacao'),
            'total_avaliacoes' => Feedback::whereNotNull('avaliacao')->count(),
        ];

        // Por tipo
        $porTipo = Feedback::selectRaw('tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        // Por prioridade
        $porPrioridade = Feedback::selectRaw('prioridade, COUNT(*) as total')
            ->groupBy('prioridade')
            ->pluck('total', 'prioridade');

        // Por status
        $porStatus = Feedback::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Feedbacks por mês (últimos 6 meses)
        $porMes = Feedback::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                return [
                    'mes' => Carbon::createFromFormat('Y-m', $item->mes)->format('M/Y'),
                    'total' => $item->total
                ];
            });

        // Top clientes com mais feedbacks
        $topClientes = Cliente::withCount('feedbacks')
            ->having('feedbacks_count', '>', 0)
            ->orderBy('feedbacks_count', 'desc')
            ->limit(10)
            ->get();

        // Feedbacks recentes
        $recentes = Feedback::with(['cliente', 'projeto'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.feedbacks.estatisticas', compact(
            'estatisticasGerais',
            'porTipo',
            'porPrioridade',
            'porStatus',
            'porMes',
            'topClientes',
            'recentes'
        ));
    }

    private function calcularTempoMedioResposta()
    {
        $feedbacksRespondidos = Feedback::whereNotNull('respondido_em')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, respondido_em)) as horas')
            ->first();

        if (!$feedbacksRespondidos || !$feedbacksRespondidos->horas) {
            return null;
        }

        $horas = round($feedbacksRespondidos->horas);
        
        if ($horas < 24) {
            return $horas . ' horas';
        } else {
            $dias = round($horas / 24);
            return $dias . ($dias == 1 ? ' dia' : ' dias');
        }
    }
}