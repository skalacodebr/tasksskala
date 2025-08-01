<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContatoWp;
use App\Models\MessageWp;
use App\Models\Colaborador;

class WhatsAppChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $colaborador = Colaborador::with('setor')->find(session('colaborador_id'));
            
            if (!$colaborador || $colaborador->setor->nome !== 'Administrativo') {
                return redirect()->route('dashboard')->with('error', 'Acesso negado. Esta funcionalidade é exclusiva para o setor Administrativo.');
            }
            
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $instanceName = $request->get('instance');
        $selectedContact = $request->get('contact');
        
        // Buscar todas as instâncias disponíveis
        $instances = MessageWp::select('instance_name')
            ->distinct()
            ->orderBy('instance_name')
            ->pluck('instance_name');
        
        if ($instances->isEmpty()) {
            $instances = ContatoWp::select('instance_name')
                ->distinct()
                ->orderBy('instance_name')
                ->pluck('instance_name');
        }
        
        // Se não tiver instância selecionada, usar a primeira disponível
        if (!$instanceName && $instances->isNotEmpty()) {
            $instanceName = $instances->first();
        }
        
        $contacts = collect();
        $messages = collect();
        
        if ($instanceName) {
            // Buscar contatos da instância com última mensagem
            $contacts = ContatoWp::where('contatos_wp.instance_name', $instanceName)
                ->leftJoin('messages_wp', function($join) use ($instanceName) {
                    $join->on('contatos_wp.remote_jid', '=', 'messages_wp.remote_jid')
                         ->where('messages_wp.instance_name', '=', $instanceName);
                })
                ->select(
                    'contatos_wp.*',
                    'messages_wp.message_text as last_message',
                    'messages_wp.message_timestamp as last_message_time',
                    'messages_wp.from_me as last_message_from_me'
                )
                ->selectRaw('MAX(messages_wp.message_timestamp) as max_timestamp')
                ->groupBy('contatos_wp.id', 'contatos_wp.remote_jid', 'contatos_wp.push_name', 
                         'contatos_wp.profile_pic_url', 'contatos_wp.instance_id', 
                         'contatos_wp.instance_name', 'contatos_wp.is_group', 
                         'contatos_wp.created_at', 'contatos_wp.updated_at',
                         'messages_wp.message_text', 'messages_wp.message_timestamp', 'messages_wp.from_me')
                ->orderByDesc('max_timestamp')
                ->get();
            
            // Se tiver contato selecionado, buscar mensagens
            if ($selectedContact) {
                $messages = MessageWp::where('instance_name', $instanceName)
                    ->where('remote_jid', $selectedContact)
                    ->orderBy('message_timestamp', 'asc')
                    ->get();
            }
        }
        
        return view('whatsapp-chat.index', compact('instances', 'instanceName', 'contacts', 'selectedContact', 'messages'));
    }
    
    public function getMessages(Request $request)
    {
        $instanceName = $request->get('instance');
        $contactJid = $request->get('contact');
        
        $messages = MessageWp::where('instance_name', $instanceName)
            ->where('remote_jid', $contactJid)
            ->orderBy('message_timestamp', 'asc')
            ->get();
            
        return response()->json($messages);
    }
}
