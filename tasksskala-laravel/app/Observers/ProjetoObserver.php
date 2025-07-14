<?php

namespace App\Observers;

use App\Models\Projeto;
use App\Services\TarefaAutomaticaService;

class ProjetoObserver
{
    protected $tarefaService;

    public function __construct(TarefaAutomaticaService $tarefaService)
    {
        $this->tarefaService = $tarefaService;
    }

    /**
     * Handle the Projeto "created" event.
     */
    public function created(Projeto $projeto): void
    {
        if ($projeto->status === 'em_andamento') {
            $this->tarefaService->processarMudancaStatusProjeto($projeto, null);
        }
    }

    /**
     * Handle the Projeto "updated" event.
     */
    public function updated(Projeto $projeto): void
    {
        if ($projeto->isDirty('status')) {
            $statusAnterior = $projeto->getOriginal('status');
            $this->tarefaService->processarMudancaStatusProjeto($projeto, $statusAnterior);
        }
    }

    /**
     * Handle the Projeto "deleted" event.
     */
    public function deleted(Projeto $projeto): void
    {
        $this->tarefaService->processarMudancaStatusProjeto($projeto, $projeto->status);
    }

    /**
     * Handle the Projeto "restored" event.
     */
    public function restored(Projeto $projeto): void
    {
        //
    }

    /**
     * Handle the Projeto "force deleted" event.
     */
    public function forceDeleted(Projeto $projeto): void
    {
        //
    }
}
