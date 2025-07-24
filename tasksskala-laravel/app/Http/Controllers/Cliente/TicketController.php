<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMensagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('cliente_id', Auth::guard('cliente')->id())
            ->with(['projeto', 'mensagens'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('cliente.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $cliente = Auth::guard('cliente')->user();
        $projetos = $cliente->projetos()->get();
        
        return view('cliente.tickets.create', compact('projetos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'setor' => 'required|in:comercial,financeiro,desenvolvimento',
            'prioridade' => 'required|in:baixa,media,alta',
            'projeto_id' => 'nullable|exists:projetos,id'
        ]);

        $validated['cliente_id'] = Auth::guard('cliente')->id();
        $validated['status'] = 'aberto';

        $ticket = Ticket::create($validated);

        return redirect()->route('cliente.tickets.show', $ticket)
            ->with('success', 'Ticket criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->cliente_id !== Auth::guard('cliente')->id()) {
            abort(403);
        }

        $ticket->load(['mensagens.cliente', 'mensagens.colaborador', 'projeto', 'atribuidoPara']);
        
        return view('cliente.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->cliente_id !== Auth::guard('cliente')->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'mensagem' => 'required|string'
        ]);

        TicketMensagem::create([
            'ticket_id' => $ticket->id,
            'mensagem' => $validated['mensagem'],
            'cliente_id' => Auth::guard('cliente')->id(),
            'is_internal' => false
        ]);

        if ($ticket->status === 'respondido') {
            $ticket->update(['status' => 'aberto']);
        }

        return redirect()->route('cliente.tickets.show', $ticket)
            ->with('success', 'Mensagem enviada com sucesso!');
    }

    public function close(Ticket $ticket)
    {
        if ($ticket->cliente_id !== Auth::guard('cliente')->id()) {
            abort(403);
        }

        if ($ticket->status === 'fechado') {
            return redirect()->route('cliente.tickets.show', $ticket)
                ->with('error', 'Este ticket já está fechado.');
        }

        $ticket->update([
            'status' => 'fechado',
            'fechado_em' => now()
        ]);

        return redirect()->route('cliente.tickets.show', $ticket)
            ->with('success', 'Ticket fechado com sucesso!');
    }
}