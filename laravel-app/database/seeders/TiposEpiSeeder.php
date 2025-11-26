<?php
// Executar: php artisan make:seeder TiposEpiSeeder
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\database\seeders\TiposEpiSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoEpi;

class TiposEpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'nome' => 'Capacete de Segurança',
                'codigo' => 'capacete',
                'descricao' => 'Equipamento de proteção para a cabeça',
                'icone' => '⛑️',
                'cor' => '#FF6B35'
            ],
            [
                'nome' => 'Óculos de Proteção',
                'codigo' => 'oculos',
                'descricao' => 'Proteção para os olhos',
                'icone' => '🥽',
                'cor' => '#4ECDC4'
            ],
            [
                'nome' => 'Luvas de Segurança',
                'codigo' => 'luvas',
                'descricao' => 'Proteção para as mãos',
                'icone' => '🧤',
                'cor' => '#45B7D1'
            ],
            [
                'nome' => 'Botas de Segurança',
                'codigo' => 'botas',
                'descricao' => 'Calçados de proteção',
                'icone' => '🥾',
                'cor' => '#8B4513'
            ],
            [
                'nome' => 'Cinto de Segurança',
                'codigo' => 'cinto_seguranca',
                'descricao' => 'Equipamento para trabalho em altura',
                'icone' => '🔗',
                'cor' => '#FF8C42'
            ],
            [
                'nome' => 'Máscara Respiratória',
                'codigo' => 'mascara',
                'descricao' => 'Proteção respiratória',
                'icone' => '😷',
                'cor' => '#6A994E'
            ],
            [
                'nome' => 'Protetor Auditivo',
                'codigo' => 'protetor_auditivo',
                'descricao' => 'Proteção contra ruídos',
                'icone' => '🎧',
                'cor' => '#A663CC'
            ],
            [
                'nome' => 'Colete Refletivo',
                'codigo' => 'colete_refletivo',
                'descricao' => 'Vestimenta de alta visibilidade',
                'icone' => '🦺',
                'cor' => '#F77F00'
            ],
            [
                'nome' => 'Uniforme de Segurança',
                'codigo' => 'uniforme',
                'descricao' => 'Vestimenta adequada para trabalho',
                'icone' => '👔',
                'cor' => '#277DA1'
            ],
            [
                'nome' => 'Outros',
                'codigo' => 'outros',
                'descricao' => 'Outros equipamentos de proteção',
                'icone' => '🛡️',
                'cor' => '#6C757D'
            ]
        ];

        foreach ($tipos as $tipo) {
            TipoEpi::create($tipo);
        }
    }
}
