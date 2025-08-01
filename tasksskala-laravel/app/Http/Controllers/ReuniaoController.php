<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\SrsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReuniaoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::orderBy('nome')->get();
        return view('reunioes.index', compact('projetos'));
    }

    public function gerarAnaliseRequisitos(Request $request)
    {
        $request->validate([
            'projeto_id' => 'required|exists:projetos,id'
        ]);

        $projeto = Projeto::find($request->projeto_id);
        
        return response()->json([
            'success' => true,
            'redirect_url' => route('agente-srs2.index') . '?projeto_id=' . $projeto->id . '&projeto_nome=' . urlencode($projeto->nome)
        ]);
    }

    public function vincularRequisito(Request $request)
    {
        $request->validate([
            'projeto_id' => 'required|exists:projetos,id',
            'requisito_id' => 'required|exists:srs_histories,id'
        ]);

        $requisito = SrsHistory::find($request->requisito_id);
        $requisito->projeto_id = $request->projeto_id;
        $requisito->save();

        return response()->json([
            'success' => true,
            'message' => 'Requisito vinculado ao projeto com sucesso!'
        ]);
    }
}