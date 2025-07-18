<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use App\Models\Colaborador;
use App\Models\Projeto;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarefa::with(['colaborador', 'projeto']);
        
        // Se não há filtro de colaborador na requisição e não é uma requisição de filtro explícita,
        // usar o colaborador padrão (lucas@skalacode.com.br) por padrão
        $colaboradorSelecionado = null;
        if ($request->filled('colaborador_id')) {
            $colaboradorSelecionado = $request->colaborador_id;
        } elseif (!$request->hasAny(['status', 'projeto_id', 'tipo']) && !$request->has('colaborador_id')) {
            // Se não há nenhum filtro aplicado, usar o colaborador padrão
            $colaboradorPadrao = Colaborador::where('email', 'lucas@skalacode.com.br')->first();
            $colaboradorSelecionado = $colaboradorPadrao ? $colaboradorPadrao->id : null;
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($colaboradorSelecionado) {
            $query->where('colaborador_id', $colaboradorSelecionado);
        }

        if ($request->filled('projeto_id')) {
            $query->where('projeto_id', $request->projeto_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $tarefas = $query->orderBy('data_vencimento', 'asc')->paginate(15);
        $colaboradores = Colaborador::all();
        $projetos = Projeto::all();

        return view('admin.tarefas.index', compact('tarefas', 'colaboradores', 'projetos', 'colaboradorSelecionado'));
    }

    public function create()
    {
        $colaboradores = Colaborador::all();
        $projetos = Projeto::all();
        return view('admin.tarefas.create', compact('colaboradores', 'projetos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'projeto_id' => 'nullable|exists:projetos,id',
            'tipo' => 'required|in:manual,automatica_feedback,automatica_aprovacao',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'data_vencimento' => 'nullable|date',
            'recorrente' => 'boolean',
            'frequencia_recorrencia' => 'nullable|in:diaria,semanal,mensal',
        ]);

        $validated['recorrente'] = $request->has('recorrente');

        Tarefa::create($validated);

        return redirect()->route('admin.tarefas.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Tarefa $tarefa)
    {
        $tarefa->load(['colaborador', 'projeto']);
        return view('admin.tarefas.show', compact('tarefa'));
    }

    public function edit(Tarefa $tarefa)
    {
        $colaboradores = Colaborador::all();
        $projetos = Projeto::all();
        return view('admin.tarefas.edit', compact('tarefa', 'colaboradores', 'projetos'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'colaborador_id' => 'required|exists:colaboradores,id',
            'projeto_id' => 'nullable|exists:projetos,id',
            'tipo' => 'required|in:manual,automatica_feedback,automatica_aprovacao',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'status' => 'required|in:pendente,em_andamento,concluida,cancelada',
            'data_vencimento' => 'nullable|date',
            'observacoes' => 'nullable|string',
            'recorrente' => 'boolean',
            'frequencia_recorrencia' => 'nullable|in:diaria,semanal,mensal',
        ]);

        $validated['recorrente'] = $request->has('recorrente');

        $tarefa->update($validated);

        return redirect()->route('admin.tarefas.index')
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();

        return redirect()->route('admin.tarefas.index')
            ->with('success', 'Tarefa excluída com sucesso!');
    }

    public function iniciar(Tarefa $tarefa)
    {
        if ($tarefa->status !== 'pendente') {
            return redirect()->back()->with('error', 'Tarefa não pode ser iniciada!');
        }

        $tarefa->iniciarTarefa();

        return redirect()->back()->with('success', 'Tarefa iniciada com sucesso!');
    }

    public function concluir(Request $request, Tarefa $tarefa)
    {
        if ($tarefa->status !== 'em_andamento') {
            return redirect()->back()->with('error', 'Tarefa não está em andamento!');
        }

        $validated = $request->validate([
            'observacoes' => 'nullable|string|max:1000'
        ]);

        $tarefa->concluirTarefa($validated['observacoes'] ?? null);

        return redirect()->back()->with('success', 'Tarefa concluída com sucesso!');
    }

    public function cancelar(Request $request, Tarefa $tarefa)
    {
        if (in_array($tarefa->status, ['concluida', 'cancelada'])) {
            return redirect()->back()->with('error', 'Tarefa não pode ser cancelada!');
        }

        $validated = $request->validate([
            'observacoes' => 'required|string|max:1000'
        ]);

        $tarefa->cancelarTarefa($validated['observacoes']);

        return redirect()->back()->with('success', 'Tarefa cancelada com sucesso!');
    }
}
