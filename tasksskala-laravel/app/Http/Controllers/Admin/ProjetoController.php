<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projeto;
use App\Models\Cliente;
use App\Models\Colaborador;
use App\Models\MarcosProjeto;
use App\Models\StatusProjeto;
use Illuminate\Http\Request;

class ProjetoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::with(['cliente', 'colaboradorResponsavel', 'marcos'])
            ->withCount('marcos')
            ->paginate(10);
        return view('admin.projetos.index', compact('projetos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $colaboradores = Colaborador::all();
        $statusProjetos = StatusProjeto::ativos()->ordenados()->get();
        return view('admin.projetos.create', compact('clientes', 'colaboradores', 'statusProjetos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'repositorio_git' => 'nullable|url',
            'colaborador_responsavel_id' => 'required|exists:colaboradores,id',
            'cliente_id' => 'required|exists:clientes,id',
            'prazo' => 'required|date',
            'anotacoes' => 'nullable|string',
            'status' => 'required|in:em_andamento,aprovacao_app,concluido,pausado,cancelado',
            'status_id' => 'nullable|exists:status_projetos,id',
            'marcos' => 'nullable|array',
            'marcos.*.nome' => 'required_with:marcos|string|max:255',
            'marcos.*.descricao' => 'nullable|string',
            'marcos.*.prazo' => 'required_with:marcos|date',
            'marcos.*.valor' => 'nullable|numeric|min:0',
        ]);

        $marcos = $validated['marcos'] ?? [];
        unset($validated['marcos']);

        $projeto = Projeto::create($validated);

        foreach ($marcos as $marco) {
            $marco['projeto_id'] = $projeto->id;
            MarcosProjeto::create($marco);
        }

        return redirect()->route('admin.projetos.index')
            ->with('success', 'Projeto criado com sucesso!');
    }

    public function show(Projeto $projeto)
    {
        $projeto->load(['cliente', 'colaboradorResponsavel', 'marcos']);
        return view('admin.projetos.show', compact('projeto'));
    }

    public function edit(Projeto $projeto)
    {
        $clientes = Cliente::all();
        $colaboradores = Colaborador::all();
        $statusProjetos = StatusProjeto::ativos()->ordenados()->get();
        $projeto->load('marcos');
        return view('admin.projetos.edit', compact('projeto', 'clientes', 'colaboradores', 'statusProjetos'));
    }

    public function update(Request $request, Projeto $projeto)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'repositorio_git' => 'nullable|url',
            'colaborador_responsavel_id' => 'required|exists:colaboradores,id',
            'cliente_id' => 'required|exists:clientes,id',
            'prazo' => 'required|date',
            'anotacoes' => 'nullable|string',
            'status' => 'required|in:em_andamento,aprovacao_app,concluido,pausado,cancelado',
            'status_id' => 'nullable|exists:status_projetos,id',
            'marcos' => 'nullable|array',
            'marcos.*.id' => 'nullable|exists:marcos_projeto,id',
            'marcos.*.nome' => 'required_with:marcos|string|max:255',
            'marcos.*.descricao' => 'nullable|string',
            'marcos.*.prazo' => 'required_with:marcos|date',
            'marcos.*.valor' => 'nullable|numeric|min:0',
            'marcos.*.status' => 'nullable|in:pendente,entregue,aprovado,rejeitado',
        ]);

        $marcos = $validated['marcos'] ?? [];
        unset($validated['marcos']);

        $projeto->update($validated);

        $existingMarcoIds = [];
        foreach ($marcos as $marco) {
            if (!empty($marco['id'])) {
                $marcoObj = MarcosProjeto::find($marco['id']);
                if ($marcoObj) {
                    $marcoObj->update($marco);
                    $existingMarcoIds[] = $marco['id'];
                }
            } else {
                $marco['projeto_id'] = $projeto->id;
                $newMarco = MarcosProjeto::create($marco);
                $existingMarcoIds[] = $newMarco->id;
            }
        }

        $projeto->marcos()->whereNotIn('id', $existingMarcoIds)->delete();

        return redirect()->route('admin.projetos.index')
            ->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();

        return redirect()->route('admin.projetos.index')
            ->with('success', 'Projeto excluído com sucesso!');
    }
}
