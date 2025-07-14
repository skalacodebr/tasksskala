<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TarefaAutomaticaService;

class GerarTarefasAutomaticas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tarefas:gerar-automaticas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera tarefas automáticas para projetos ativos e em aprovação';

    protected $tarefaService;

    public function __construct(TarefaAutomaticaService $tarefaService)
    {
        parent::__construct();
        $this->tarefaService = $tarefaService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando geração de tarefas automáticas...');

        try {
            $this->info('Gerando tarefas de feedback para projetos ativos...');
            $this->tarefaService->gerarTarefasFeedbackProjetosAtivos();

            $this->info('Gerando tarefas diárias para projetos em aprovação...');
            $this->tarefaService->gerarTarefasDiariasAprovacao();

            $this->info('Tarefas automáticas geradas com sucesso!');
        } catch (\Exception $e) {
            $this->error('Erro ao gerar tarefas automáticas: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
