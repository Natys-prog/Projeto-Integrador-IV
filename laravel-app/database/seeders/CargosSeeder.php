<?php
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\database\seeders\CargosSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem dados
        if (DB::table('cargos')->count() > 0) {
            $this->command->info('ℹ️  Cargos já existem, pulando inserção...');
            return;
        }

        $cargos = [
            [
                'nome' => 'Engenheiro Civil',
                'codigo' => 'ENG_CIVIL',
                'descricao' => 'Responsável por projetos de engenharia civil',
                'nivel' => 'superior',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Técnico de Segurança',
                'codigo' => 'SEG_TEC',
                'descricao' => 'Responsável pela segurança do trabalho',
                'nivel' => 'tecnico',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Operador de Produção',
                'codigo' => 'PROD_OP',
                'descricao' => 'Operador de máquinas e equipamentos de produção',
                'nivel' => 'operacional',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Analista de RH',
                'codigo' => 'RH_ANALISTA',
                'descricao' => 'Analista de recursos humanos',
                'nivel' => 'tecnico',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Mecânico de Manutenção',
                'codigo' => 'MAN_MEC',
                'descricao' => 'Mecânico responsável pela manutenção de equipamentos',
                'nivel' => 'tecnico',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('cargos')->insert($cargos);
        
        $this->command->info('✅ ' . count($cargos) . ' cargos inseridos com sucesso!');
    }
}
