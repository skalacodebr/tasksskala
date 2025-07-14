<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatusProjeto;
use Illuminate\Http\Request;

class StatusProjetoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statusProjetos = StatusProjeto::withCount('projetos')->ordenados()->paginate(10);
        return view('admin.status-projetos.index', compact('statusProjetos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.status-projetos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:status_projetos,nome',
            'cor' => 'required|string|max:7',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
            'ordem' => 'integer|min:0'
        ]);

        StatusProjeto::create($validated);

        return redirect()->route('admin.status-projetos.index')->with('success', 'Status de projeto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(StatusProjeto $statusProjeto)
    {
        $statusProjeto->load('projetos');
        return view('admin.status-projetos.show', compact('statusProjeto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusProjeto $statusProjeto)
    {
        return view('admin.status-projetos.edit', compact('statusProjeto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StatusProjeto $statusProjeto)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:status_projetos,nome,' . $statusProjeto->id,
            'cor' => 'required|string|max:7',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
            'ordem' => 'integer|min:0'
        ]);

        $statusProjeto->update($validated);

        return redirect()->route('admin.status-projetos.index')->with('success', 'Status de projeto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusProjeto $statusProjeto)
    {
        if ($statusProjeto->projetos()->count() > 0) {
            return redirect()->route('admin.status-projetos.index')->with('error', 'Não é possível excluir um status que possui projetos associados.');
        }

        $statusProjeto->delete();
        return redirect()->route('admin.status-projetos.index')->with('success', 'Status de projeto excluído com sucesso!');
    }
}
