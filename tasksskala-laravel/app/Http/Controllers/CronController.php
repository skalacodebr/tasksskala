<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    /**
     * Execute o scheduler do Laravel
     */
    public function runScheduler(Request $request)
    {
        // Verificar token de segurança
        $token = $request->get('token');
        $expectedToken = env('CRON_TOKEN', 'skala2024cron');
        
        if ($token !== $expectedToken) {
            Log::warning('Tentativa de acesso ao cron com token inválido');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        try {
            // Executar o scheduler
            Artisan::call('schedule:run');
            $output = Artisan::output();
            
            Log::info('Cron executado via web', ['output' => $output]);
            
            return response()->json([
                'success' => true,
                'message' => 'Scheduler executado com sucesso',
                'output' => $output,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao executar cron via web: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erro ao executar scheduler',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Execute apenas o relatório diário do WhatsApp
     */
    public function runDailyReport(Request $request)
    {
        // Verificar token de segurança
        $token = $request->get('token');
        $expectedToken = env('CRON_TOKEN', 'skala2024cron');
        
        if ($token !== $expectedToken) {
            Log::warning('Tentativa de acesso ao relatório diário com token inválido');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        try {
            // Executar apenas o comando do relatório
            Artisan::call('whatsapp:send-daily-report');
            $output = Artisan::output();
            
            Log::info('Relatório diário executado via web', ['output' => $output]);
            
            return response()->json([
                'success' => true,
                'message' => 'Relatório diário enviado com sucesso',
                'output' => $output,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar relatório diário via web: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erro ao enviar relatório',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}