<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use App\Models\Setor;
use App\Models\Conhecimento;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colaboradores = Colaborador::with(['setor', 'conhecimentos'])->paginate(10);
        return view('admin.colaboradores.index', compact('colaboradores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setores = Setor::all();
        $conhecimentos = Conhecimento::all();
        return view('admin.colaboradores.create', compact('setores', 'conhecimentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:colaboradores,email',
            'senha' => 'required|string|min:6',
            'setor_id' => 'required|exists:setores,id',
            'conhecimentos' => 'array',
            'conhecimentos.*' => 'exists:conhecimentos,id',
            'novo_conhecimento' => 'nullable|string|max:255',
            'novo_setor' => 'nullable|string|max:255'
        ]);

        if ($request->novo_setor) {
            $setor = Setor::create(['nome' => $request->novo_setor]);
            $validated['setor_id'] = $setor->id;
        }

        $colaborador = Colaborador::create($validated);

        if ($request->novo_conhecimento) {
            $conhecimento = Conhecimento::create(['nome' => $request->novo_conhecimento]);
            $validated['conhecimentos'][] = $conhecimento->id;
        }

        if (isset($validated['conhecimentos'])) {
            $colaborador->conhecimentos()->sync($validated['conhecimentos']);
        }

        return redirect()->route('admin.colaboradores.index')->with('success', 'Colaborador criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colaborador $colaborador)
    {
        $colaborador->load(['setor', 'conhecimentos']);
        return view('admin.colaboradores.show', compact('colaborador'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colaborador $colaborador)
    {
        $setores = Setor::all();
        $conhecimentos = Conhecimento::all();
        $colaborador->load('conhecimentos');
        return view('admin.colaboradores.edit', compact('colaborador', 'setores', 'conhecimentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Colaborador $colaborador)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:colaboradores,email,' . $colaborador->id,
            'senha' => 'nullable|string|min:6',
            'setor_id' => 'required|exists:setores,id',
            'conhecimentos' => 'array',
            'conhecimentos.*' => 'exists:conhecimentos,id',
            'novo_conhecimento' => 'nullable|string|max:255',
            'novo_setor' => 'nullable|string|max:255'
        ]);

        if ($request->novo_setor) {
            $setor = Setor::create(['nome' => $request->novo_setor]);
            $validated['setor_id'] = $setor->id;
        }

        if (empty($validated['senha'])) {
            unset($validated['senha']);
        }

        $colaborador->update($validated);

        if ($request->novo_conhecimento) {
            $conhecimento = Conhecimento::create(['nome' => $request->novo_conhecimento]);
            $validated['conhecimentos'][] = $conhecimento->id;
        }

        $colaborador->conhecimentos()->sync($validated['conhecimentos'] ?? []);

        return redirect()->route('admin.colaboradores.index')->with('success', 'Colaborador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colaborador $colaborador)
    {
        $colaborador->delete();
        return redirect()->route('admin.colaboradores.index')->with('success', 'Colaborador excluído com sucesso!');
    }
}
