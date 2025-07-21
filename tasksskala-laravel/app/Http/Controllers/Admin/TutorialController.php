<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tutorial;
use Illuminate\Support\Facades\Storage;

class TutorialController extends Controller
{
    public function index()
    {
        $tutoriais = Tutorial::ordenados()->paginate(10);
        return view('admin.tutoriais.index', compact('tutoriais'));
    }

    public function create()
    {
        return view('admin.tutoriais.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo_video' => 'required|file|mimes:mp4|max:102400', // 100MB max
            'publico_alvo' => 'required|in:colaboradores,clientes',
            'ativo' => 'boolean',
            'ordem' => 'integer|min:0',
        ]);

        // Upload do vídeo
        $videoPath = $request->file('arquivo_video')->store('tutoriais', 'public');

        $validated['arquivo_video'] = $videoPath;
        $validated['ativo'] = $request->has('ativo');

        Tutorial::create($validated);

        return redirect()->route('admin.tutoriais.index')
            ->with('success', 'Tutorial criado com sucesso!');
    }

    public function show(Tutorial $tutorial)
    {
        return view('admin.tutoriais.show', compact('tutorial'));
    }

    public function edit(Tutorial $tutorial)
    {
        return view('admin.tutoriais.edit', compact('tutorial'));
    }

    public function update(Request $request, Tutorial $tutorial)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo_video' => 'nullable|file|mimes:mp4|max:102400', // 100MB max
            'publico_alvo' => 'required|in:colaboradores,clientes',
            'ativo' => 'boolean',
            'ordem' => 'integer|min:0',
        ]);

        // Se um novo vídeo foi enviado
        if ($request->hasFile('arquivo_video')) {
            // Deletar o vídeo antigo
            if ($tutorial->arquivo_video) {
                Storage::disk('public')->delete($tutorial->arquivo_video);
            }
            
            // Upload do novo vídeo
            $videoPath = $request->file('arquivo_video')->store('tutoriais', 'public');
            $validated['arquivo_video'] = $videoPath;
        }

        $validated['ativo'] = $request->has('ativo');

        $tutorial->update($validated);

        return redirect()->route('admin.tutoriais.index')
            ->with('success', 'Tutorial atualizado com sucesso!');
    }

    public function destroy(Tutorial $tutorial)
    {
        // Deletar o arquivo de vídeo
        if ($tutorial->arquivo_video) {
            Storage::disk('public')->delete($tutorial->arquivo_video);
        }

        $tutorial->delete();

        return redirect()->route('admin.tutoriais.index')
            ->with('success', 'Tutorial excluído com sucesso!');
    }
}
