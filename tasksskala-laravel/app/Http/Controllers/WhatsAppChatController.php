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
            // Buscar todos os contatos da instância
            $allContacts = ContatoWp::where('instance_name', $instanceName)->get();
            
            // Para cada contato, buscar a última mensagem
            $contacts = $allContacts->map(function($contact) use ($instanceName) {
                $lastMessage = MessageWp::where('instance_name', $instanceName)
                    ->where('remote_jid', $contact->remote_jid)
                    ->orderBy('message_timestamp', 'desc')
                    ->first();
                
                $contact->last_message = $lastMessage ? $lastMessage->message_text : null;
                $contact->last_message_time = $lastMessage ? $lastMessage->message_timestamp : null;
                $contact->last_message_from_me = $lastMessage ? $lastMessage->from_me : null;
                $contact->max_timestamp = $lastMessage ? $lastMessage->message_timestamp : 0;
                
                return $contact;
            })->sortByDesc('max_timestamp')->values();
            
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
