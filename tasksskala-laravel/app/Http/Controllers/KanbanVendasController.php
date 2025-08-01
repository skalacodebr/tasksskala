<?php

namespace App\Http\Controllers;

use App\Models\KanbanVenda;
use App\Models\KanbanCard;
use App\Models\Cliente;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KanbanVendasController extends Controller
{
    public function index()
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado. Esta página é exclusiva para os setores de Vendas e Administrativo.');
        }

        $colunas = KanbanVenda::with(['cards' => function($query) {
            $query->with(['cliente', 'colaborador'])->orderBy('ordem');
        }])->orderBy('ordem')->get();

        $clientes = Cliente::orderBy('nome')->get();
        $colaboradores = Colaborador::whereHas('setor', function($query) {
            $query->whereIn('nome', ['Vendas', 'Administrativo']);
        })->orderBy('nome')->get();

        return view('kanban-vendas.index', compact('colunas', 'clientes', 'colaboradores'));
    }

    public function storeColuna(Request $request)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'cor' => 'required|string|max:7'
        ]);

        $ultimaOrdem = KanbanVenda::max('ordem') ?? 0;

        $coluna = KanbanVenda::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'cor' => $request->cor,
            'ordem' => $ultimaOrdem + 1
        ]);

        return response()->json($coluna);
    }

    public function updateColuna(Request $request, KanbanVenda $coluna)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'cor' => 'required|string|max:7'
        ]);

        $coluna->update($request->only(['nome', 'descricao', 'cor']));

        return response()->json($coluna);
    }

    public function deleteColuna(KanbanVenda $coluna)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        if ($coluna->cards()->count() > 0) {
            return response()->json(['error' => 'Não é possível excluir uma coluna com cards'], 400);
        }

        $coluna->delete();

        return response()->json(['success' => true]);
    }

    public function storeCard(Request $request)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'kanban_venda_id' => 'required|exists:kanban_vendas,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'valor' => 'nullable|numeric|min:0',
            'data_previsao' => 'nullable|date'
        ]);

        $ultimaOrdem = KanbanCard::where('kanban_venda_id', $request->kanban_venda_id)
            ->max('ordem') ?? 0;

        $card = KanbanCard::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'kanban_venda_id' => $request->kanban_venda_id,
            'cliente_id' => $request->cliente_id,
            'colaborador_id' => $request->colaborador_id,
            'valor' => $request->valor,
            'data_previsao' => $request->data_previsao,
            'ordem' => $ultimaOrdem + 1,
            'observacoes' => $request->observacoes
        ]);

        $card->load(['cliente', 'colaborador']);

        return response()->json($card);
    }

    public function showCard(KanbanCard $card)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $card->load(['cliente', 'colaborador', 'kanbanVenda']);
        
        return response()->json($card);
    }

    public function updateCard(Request $request, KanbanCard $card)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'cliente_id' => 'nullable|exists:clientes,id',
            'colaborador_id' => 'nullable|exists:colaboradores,id',
            'valor' => 'nullable|numeric|min:0',
            'data_previsao' => 'nullable|date',
            'data_conclusao' => 'nullable|date'
        ]);

        $card->update($request->only([
            'titulo', 'descricao', 'cliente_id', 'colaborador_id',
            'valor', 'data_previsao', 'data_conclusao', 'observacoes'
        ]));

        $card->load(['cliente', 'colaborador']);

        return response()->json($card);
    }

    public function deleteCard(KanbanCard $card)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $card->delete();

        return response()->json(['success' => true]);
    }

    public function moveCard(Request $request, KanbanCard $card)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'kanban_venda_id' => 'required|exists:kanban_vendas,id',
            'nova_ordem' => 'required|integer|min:0'
        ]);

        DB::transaction(function() use ($request, $card) {
            $colunaAntigaId = $card->kanban_venda_id;
            $ordemAntiga = $card->ordem;
            
            if ($colunaAntigaId == $request->kanban_venda_id) {
                // Movendo na mesma coluna
                if ($ordemAntiga < $request->nova_ordem) {
                    KanbanCard::where('kanban_venda_id', $colunaAntigaId)
                        ->where('ordem', '>', $ordemAntiga)
                        ->where('ordem', '<=', $request->nova_ordem)
                        ->decrement('ordem');
                } else {
                    KanbanCard::where('kanban_venda_id', $colunaAntigaId)
                        ->where('ordem', '<', $ordemAntiga)
                        ->where('ordem', '>=', $request->nova_ordem)
                        ->increment('ordem');
                }
            } else {
                // Movendo para outra coluna
                KanbanCard::where('kanban_venda_id', $colunaAntigaId)
                    ->where('ordem', '>', $ordemAntiga)
                    ->decrement('ordem');
                
                KanbanCard::where('kanban_venda_id', $request->kanban_venda_id)
                    ->where('ordem', '>=', $request->nova_ordem)
                    ->increment('ordem');
            }
            
            $card->update([
                'kanban_venda_id' => $request->kanban_venda_id,
                'ordem' => $request->nova_ordem
            ]);
        });

        return response()->json(['success' => true]);
    }

    public function reorderColunas(Request $request)
    {
        $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
        
        if (!$colaborador || !in_array($colaborador->setor->nome, ['Vendas', 'Administrativo'])) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'colunas' => 'required|array',
            'colunas.*' => 'exists:kanban_vendas,id'
        ]);

        DB::transaction(function() use ($request) {
            foreach ($request->colunas as $ordem => $colunaId) {
                KanbanVenda::where('id', $colunaId)->update(['ordem' => $ordem]);
            }
        });

        return response()->json(['success' => true]);
    }
}