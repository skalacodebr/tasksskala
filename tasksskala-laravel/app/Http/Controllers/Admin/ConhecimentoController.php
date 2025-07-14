<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conhecimento;
use Illuminate\Http\Request;

class ConhecimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conhecimentos = Conhecimento::withCount('colaboradores')->paginate(10);
        return view('admin.conhecimentos.index', compact('conhecimentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.conhecimentos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:conhecimentos,nome',
            'descricao' => 'nullable|string'
        ]);

        Conhecimento::create($validated);

        return redirect()->route('admin.conhecimentos.index')->with('success', 'Conhecimento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conhecimento $conhecimento)
    {
        $conhecimento->load('colaboradores.setor');
        return view('admin.conhecimentos.show', compact('conhecimento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conhecimento $conhecimento)
    {
        return view('admin.conhecimentos.edit', compact('conhecimento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conhecimento $conhecimento)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:conhecimentos,nome,' . $conhecimento->id,
            'descricao' => 'nullable|string'
        ]);

        $conhecimento->update($validated);

        return redirect()->route('admin.conhecimentos.index')->with('success', 'Conhecimento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conhecimento $conhecimento)
    {
        if ($conhecimento->colaboradores()->count() > 0) {
            return redirect()->route('admin.conhecimentos.index')->with('error', 'Não é possível excluir um conhecimento que possui colaboradores.');
        }

        $conhecimento->delete();
        return redirect()->route('admin.conhecimentos.index')->with('success', 'Conhecimento excluído com sucesso!');
    }
}
