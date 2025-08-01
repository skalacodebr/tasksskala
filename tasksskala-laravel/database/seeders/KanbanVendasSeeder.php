<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KanbanVenda;

class KanbanVendasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colunas = [
            [
                'nome' => 'Prospecção',
                'descricao' => 'Leads e oportunidades identificadas',
                'cor' => '#6366f1',
                'ordem' => 1
            ],
            [
                'nome' => 'Contato Inicial',
                'descricao' => 'Primeiro contato realizado',
                'cor' => '#8b5cf6',
                'ordem' => 2
            ],
            [
                'nome' => 'Proposta',
                'descricao' => 'Proposta enviada ao cliente',
                'cor' => '#ec4899',
                'ordem' => 3
            ],
            [
                'nome' => 'Negociação',
                'descricao' => 'Em negociação com o cliente',
                'cor' => '#f59e0b',
                'ordem' => 4
            ],
            [
                'nome' => 'Fechado',
                'descricao' => 'Vendas concluídas',
                'cor' => '#10b981',
                'ordem' => 5
            ],
            [
                'nome' => 'Perdido',
                'descricao' => 'Oportunidades perdidas',
                'cor' => '#ef4444',
                'ordem' => 6
            ]
        ];

        foreach ($colunas as $coluna) {
            KanbanVenda::create($coluna);
        }
    }
}
