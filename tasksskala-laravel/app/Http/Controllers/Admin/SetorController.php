<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setor;
use Illuminate\Http\Request;

class SetorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setores = Setor::withCount('colaboradores')->paginate(10);
        return view('admin.setores.index', compact('setores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.setores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:setores,nome',
            'descricao' => 'nullable|string'
        ]);

        Setor::create($validated);

        return redirect()->route('admin.setores.index')->with('success', 'Setor criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Setor $setor)
    {
        $setor->load('colaboradores');
        return view('admin.setores.show', compact('setor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setor $setor)
    {
        return view('admin.setores.edit', compact('setor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setor $setor)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:setores,nome,' . $setor->id,
            'descricao' => 'nullable|string'
        ]);

        $setor->update($validated);

        return redirect()->route('admin.setores.index')->with('success', 'Setor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setor $setor)
    {
        if ($setor->colaboradores()->count() > 0) {
            return redirect()->route('admin.setores.index')->with('error', 'Não é possível excluir um setor que possui colaboradores.');
        }

        $setor->delete();
        return redirect()->route('admin.setores.index')->with('success', 'Setor excluído com sucesso!');
    }
}
