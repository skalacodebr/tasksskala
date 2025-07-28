<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tarefa;
use App\Models\Colaborador;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendDailyWhatsAppReport extends Command
{
    protected $signature = 'whatsapp:send-daily-report';
    protected $description = 'Envia relatório diário de tarefas via WhatsApp às 18h';

    public function handle()
    {
        $this->info('Iniciando geração do relatório diário...');
        
        $hoje = Carbon::today();
        $colaboradores = Colaborador::all();
        
        $relatorio = "📊 *RELATÓRIO DIÁRIO DE TAREFAS*\n";
        $relatorio .= "📅 Data: " . $hoje->format('d/m/Y') . "\n";
        $relatorio .= "⏰ Horário: " . Carbon::now()->format('H:i') . "\n\n";
        
        $totalTarefasTrabalhadasHoje = 0;
        $totalTarefasConcluidasHoje = 0;
        $totalTarefasAtrasadas = 0;
        
        foreach ($colaboradores as $colaborador) {
            // Tarefas trabalhadas hoje (criadas ou atualizadas hoje)
            $tarefasTrabalhadasHoje = Tarefa::where('colaborador_id', $colaborador->id)
                ->where(function($query) use ($hoje) {
                    $query->whereDate('created_at', $hoje)
                          ->orWhereDate('updated_at', $hoje);
                })
                ->count();
            
            // Tarefas concluídas hoje
            $tarefasConcluidasHoje = Tarefa::where('colaborador_id', $colaborador->id)
                ->where('status', 'concluída')
                ->whereDate('updated_at', $hoje)
                ->count();
            
            // Tarefas atrasadas (data_vencimento < hoje e status != concluída)
            $tarefasAtrasadas = Tarefa::where('colaborador_id', $colaborador->id)
                ->where('status', '!=', 'concluída')
                ->whereDate('data_vencimento', '<', $hoje)
                ->count();
            
            if ($tarefasTrabalhadasHoje > 0 || $tarefasAtrasadas > 0) {
                $relatorio .= "👤 *{$colaborador->nome}*\n";
                $relatorio .= "   ✅ Trabalhadas hoje: {$tarefasTrabalhadasHoje}\n";
                $relatorio .= "   ✔️ Concluídas hoje: {$tarefasConcluidasHoje}\n";
                $relatorio .= "   ⚠️ Atrasadas: {$tarefasAtrasadas}\n\n";
                
                $totalTarefasTrabalhadasHoje += $tarefasTrabalhadasHoje;
                $totalTarefasConcluidasHoje += $tarefasConcluidasHoje;
                $totalTarefasAtrasadas += $tarefasAtrasadas;
            }
        }
        
        $relatorio .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $relatorio .= "📈 *RESUMO GERAL*\n";
        $relatorio .= "   Total trabalhadas hoje: {$totalTarefasTrabalhadasHoje}\n";
        $relatorio .= "   Total concluídas hoje: {$totalTarefasConcluidasHoje}\n";
        $relatorio .= "   Total atrasadas: {$totalTarefasAtrasadas}\n";
        
        // Enviar via WhatsApp para todos os destinatários
        $destinatarios = config('whatsapp.reports.daily.recipients', []);
        $enviados = 0;
        $falhas = 0;
        
        foreach ($destinatarios as $numero) {
            $numero = trim($numero);
            if (!empty($numero)) {
                $this->info("Enviando para: {$numero}");
                if ($this->enviarWhatsApp($relatorio, $numero)) {
                    $enviados++;
                } else {
                    $falhas++;
                    $this->error("Falha ao enviar para: {$numero}");
                }
            }
        }
        
        $this->info("Relatório enviado com sucesso! Enviados: {$enviados}, Falhas: {$falhas}");
        return 0;
    }
    
    private function enviarWhatsApp($mensagem, $numeroDestino = null)
    {
        try {
            // Usar número específico ou padrão
            $numero = $numeroDestino ?: config('whatsapp.default_phone');
            
            // Preparar o payload no formato correto da API
            $payload = [
                'number' => $numero,
                'text' => $mensagem
            ];
            
            // Enviar usando cURL
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, config('whatsapp.api_url'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . config('whatsapp.api_key'),
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);
            
            if ($error) {
                Log::error('Erro cURL ao enviar relatório WhatsApp para ' . $numero . ': ' . $error);
                return false;
            } elseif ($httpCode >= 200 && $httpCode < 300) {
                Log::info('Relatório WhatsApp enviado com sucesso para: ' . $numero);
                return true;
            } else {
                Log::error('Erro HTTP ao enviar relatório WhatsApp para ' . $numero . ': HTTP ' . $httpCode . ' - ' . $response);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar relatório WhatsApp para ' . $numero . ': ' . $e->getMessage());
            return false;
        }
    }
}