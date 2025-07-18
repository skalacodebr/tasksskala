<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    private GoogleCalendarService $googleCalendarService;
    
    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }
    
    public function redirect()
    {
        $authUrl = $this->googleCalendarService->getAuthorizationUrl();
        return redirect($authUrl);
    }
    
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('dashboard')->with('error', 'Autorização cancelada.');
        }
        
        try {
            $colaborador = Auth::guard('colaborador')->user();
            $this->googleCalendarService->handleCallback($request->get('code'), $colaborador);
            
            return redirect()->route('dashboard')->with('success', 'Google Calendar conectado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Erro ao conectar Google Calendar: ' . $e->getMessage());
        }
    }
    
    public function disconnect()
    {
        try {
            $colaborador = Auth::guard('colaborador')->user();
            $this->googleCalendarService->revokeAccess($colaborador);
            
            return redirect()->route('dashboard')->with('success', 'Google Calendar desconectado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Erro ao desconectar Google Calendar: ' . $e->getMessage());
        }
    }
}
