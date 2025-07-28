<?php

namespace App\Traits;

trait FinanceiroLayoutTrait
{
    /**
     * Detecta o layout correto baseado no usuário autenticado
     */
    protected function getLayoutForUser()
    {
        // Se há um colaborador na sessão, use o layout do colaborador
        if (session('colaborador')) {
            return 'layouts.colaborador';
        }
        
        // Caso contrário, use o layout admin
        return 'layouts.admin';
    }
    
    /**
     * Adiciona o layout correto aos dados da view
     */
    protected function viewWithLayout($view, $data = [])
    {
        $layout = $this->getLayoutForUser();
        $data['layout'] = $layout;
        
        return view($view, $data);
    }
}