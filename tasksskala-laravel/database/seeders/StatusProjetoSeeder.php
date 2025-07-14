<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StatusProjeto;

class StatusProjetoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusProjetos = [
            [
                'nome' => 'Planejamento',
                'cor' => '#8B5CF6',
                'descricao' => 'Projeto em fase de planejamento e análise de requisitos',
                'ordem' => 1,
                'ativo' => true
            ],
            [
                'nome' => 'Desenvolvimento',
                'cor' => '#3B82F6',
                'descricao' => 'Projeto em desenvolvimento ativo',
                'ordem' => 2,
                'ativo' => true
            ],
            [
                'nome' => 'Revisão',
                'cor' => '#F59E0B',
                'descricao' => 'Projeto em fase de revisão e testes',
                'ordem' => 3,
                'ativo' => true
            ],
            [
                'nome' => 'Aprovação Cliente',
                'cor' => '#EF4444',
                'descricao' => 'Aguardando aprovação do cliente',
                'ordem' => 4,
                'ativo' => true
            ],
            [
                'nome' => 'Finalizado',
                'cor' => '#10B981',
                'descricao' => 'Projeto finalizado com sucesso',
                'ordem' => 5,
                'ativo' => true
            ],
            [
                'nome' => 'Aguardando Cliente',
                'cor' => '#6B7280',
                'descricao' => 'Aguardando resposta ou ação do cliente',
                'ordem' => 6,
                'ativo' => true
            ],
            [
                'nome' => 'Em Homologação',
                'cor' => '#EC4899',
                'descricao' => 'Projeto em processo de homologação',
                'ordem' => 7,
                'ativo' => true
            ]
        ];

        foreach ($statusProjetos as $status) {
            StatusProjeto::create($status);
        }
    }
}