<?php
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\database\seeders\TiposEpiSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposEpiSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar se já existem dados para evitar duplicatas
        if (DB::table('tipos_epi')->count() > 0) {
            $this->command->info('ℹ️  Tipos de EPI já existem, pulando inserção...');
            return;
        }

        $tiposEpi = [
            [
                'nome' => 'Capacete de Segurança',
                'codigo' => 'capacete',
                'descricao' => 'Equipamento de proteção para a cabeça contra impactos e quedas de objetos',
                'icone' => '⛑️',
                'cor' => '#FF6B35',
                'categoria' => 'Proteção da Cabeça',
                'validade_meses' => 60,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Óculos de Proteção',
                'codigo' => 'oculos',
                'descricao' => 'Proteção dos olhos contra partículas, radiação e respingos químicos',
                'icone' => '🥽',
                'cor' => '#4ECDC4',
                'categoria' => 'Proteção dos Olhos',
                'validade_meses' => 18,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Luvas de Segurança',
                'codigo' => 'luvas',
                'descricao' => 'Proteção das mãos contra cortes, produtos químicos e temperatura',
                'icone' => '🧤',
                'cor' => '#45B7D1',
                'categoria' => 'Proteção das Mãos',
                'validade_meses' => 6,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Calçados de Segurança',
                'codigo' => 'botas',
                'descricao' => 'Proteção dos pés contra perfurações, impactos e produtos químicos',
                'icone' => '🥾',
                'cor' => '#F7DC6F',
                'categoria' => 'Proteção dos Pés',
                'validade_meses' => 36,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Cinto de Segurança',
                'codigo' => 'cinto_seguranca',
                'descricao' => 'Equipamento para trabalho em altura e prevenção de quedas',
                'icone' => '🦺',
                'cor' => '#E74C3C',
                'categoria' => 'Proteção contra Quedas',
                'validade_meses' => 60,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Máscara Respiratória',
                'codigo' => 'mascara',
                'descricao' => 'Proteção das vias respiratórias contra gases, vapores e partículas',
                'icone' => '😷',
                'cor' => '#A569BD',
                'categoria' => 'Proteção Respiratória',
                'validade_meses' => 3,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Protetor Auditivo',
                'codigo' => 'protetor_auditivo',
                'descricao' => 'Proteção auditiva contra ruídos excessivos no ambiente de trabalho',
                'icone' => '🎧',
                'cor' => '#58D68D',
                'categoria' => 'Proteção Auditiva',
                'validade_meses' => 24,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Colete Refletivo',
                'codigo' => 'colete_refletivo',
                'descricao' => 'Vestimenta de alta visibilidade para trabalhos em vias públicas',
                'icone' => '🦺',
                'cor' => '#F39C12',
                'categoria' => 'Vestimentas de Segurança',
                'validade_meses' => 12,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Outros EPIs',
                'codigo' => 'outros',
                'descricao' => 'Outros equipamentos de proteção individual diversos',
                'icone' => '🛡️',
                'cor' => '#95A5A6',
                'categoria' => 'Diversos',
                'validade_meses' => 12,
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tipos_epi')->insert($tiposEpi);
        
        $this->command->info('✅ ' . count($tiposEpi) . ' tipos de EPI inseridos com sucesso!');
    }
}
