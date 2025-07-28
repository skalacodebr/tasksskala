<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMensagem;
use App\Models\Colaborador;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['cliente', 'projeto', 'atribuidoPara', 'mensagens'])
            ->orderBy('created_at', 'desc');
            
        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('setor')) {
            $query->where('setor', $request->setor);
        }
        
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }
        
        // Se o colaborador não for admin, mostrar apenas tickets do seu setor ou atribuídos a ele
        $colaborador = Colaborador::find(session('colaborador_id'));
        if ($colaborador && !$colaborador->is_admin) {
            // Mapear o nome do setor para o valor usado no banco
            $setorMap = [
                'Comercial' => 'comercial',
                'Financeiro' => 'financeiro',
                'Desenvolvimento' => 'desenvolvimento'
            ];
            
            $setorKey = $setorMap[$colaborador->setor->nome] ?? strtolower($colaborador->setor->nome);
            
            $query->where(function($q) use ($colaborador, $setorKey) {
                $q->where('setor', $setorKey)
                  ->orWhere('atribuido_para', $colaborador->id);
            });
        }
        
        $tickets = $query->paginate(15);
        
        // Estatísticas
        $stats = [
            'total' => Ticket::count(),
            'abertos' => Ticket::where('status', 'aberto')->count(),
            'em_andamento' => Ticket::where('status', 'em_andamento')->count(),
            'respondidos' => Ticket::where('status', 'respondido')->count(),
            'fechados' => Ticket::where('status', 'fechado')->count(),
        ];
            
        return view('tickets.index', compact('tickets', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['cliente', 'projeto', 'atribuidoPara', 'mensagens.cliente', 'mensagens.colaborador']);
        
        $colaboradores = Colaborador::where('ativo', true)->get();
        
        return view('tickets.show', compact('ticket', 'colaboradores'));
    }

    public function atribuir(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'colaborador_id' => 'required|exists:colaboradores,id'
        ]);
        
        $ticket->update([
            'atribuido_para' => $validated['colaborador_id'],
            'status' => 'em_andamento'
        ]);
        
        // Adicionar mensagem interna
        TicketMensagem::create([
            'ticket_id' => $ticket->id,
            'mensagem' => 'Ticket atribuído para ' . $ticket->atribuidoPara->nome,
            'colaborador_id' => session('colaborador_id'),
            'is_internal' => true
        ]);
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket atribuído com sucesso!');
    }

    public function responder(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'mensagem' => 'required|string',
            'is_internal' => 'boolean'
        ]);
        
        TicketMensagem::create([
            'ticket_id' => $ticket->id,
            'mensagem' => $validated['mensagem'],
            'colaborador_id' => session('colaborador_id'),
            'is_internal' => $validated['is_internal'] ?? false
        ]);
        
        // Se não for mensagem interna, atualizar status
        if (!($validated['is_internal'] ?? false)) {
            $ticket->update([
                'status' => 'respondido',
                'respondido_em' => now()
            ]);
        }
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Resposta enviada com sucesso!');
    }

    public function alterarStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:aberto,em_andamento,respondido,fechado'
        ]);
        
        $updateData = ['status' => $validated['status']];
        
        if ($validated['status'] === 'fechado') {
            $updateData['fechado_em'] = now();
        }
        
        $ticket->update($updateData);
        
        // Adicionar mensagem interna
        TicketMensagem::create([
            'ticket_id' => $ticket->id,
            'mensagem' => 'Status alterado para: ' . $ticket->getStatusLabelAttribute(),
            'colaborador_id' => session('colaborador_id'),
            'is_internal' => true
        ]);
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Status alterado com sucesso!');
    }

    public function transferir(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'novo_responsavel_id' => 'required|exists:colaboradores,id'
        ]);
        
        $colaboradorAnterior = $ticket->atribuidoPara;
        
        $ticket->update([
            'atribuido_para' => $validated['novo_responsavel_id']
        ]);
        
        // Adicionar mensagem interna sobre a transferência
        $mensagem = 'Responsabilidade transferida';
        if ($colaboradorAnterior) {
            $mensagem .= ' de ' . $colaboradorAnterior->nome;
        }
        $mensagem .= ' para ' . $ticket->atribuidoPara->nome;
        
        TicketMensagem::create([
            'ticket_id' => $ticket->id,
            'mensagem' => $mensagem,
            'colaborador_id' => session('colaborador_id'),
            'is_internal' => true
        ]);
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Responsabilidade transferida com sucesso!');
    }
}