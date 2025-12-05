<?php
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\database\seeders\DepartamentosSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem dados
        if (DB::table('departamentos')->count() > 0) {
            $this->command->info('ℹ️  Departamentos já existem, pulando inserção...');
            return;
        }

        $departamentos = [
            [
                'nome' => 'Engenharia',
                'codigo' => 'ENG',
                'descricao' => 'Departamento de Engenharia e Projetos',
                'cor' => '#3498db',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Segurança do Trabalho',
                'codigo' => 'SEG',
                'descricao' => 'Departamento de Segurança e Saúde Ocupacional',
                'cor' => '#e74c3c',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Produção',
                'codigo' => 'PROD',
                'descricao' => 'Departamento de Produção Industrial',
                'cor' => '#f39c12',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Recursos Humanos',
                'codigo' => 'RH',
                'descricao' => 'Departamento de Recursos Humanos',
                'cor' => '#9b59b6',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Manutenção',
                'codigo' => 'MAN',
                'descricao' => 'Departamento de Manutenção Industrial',
                'cor' => '#27ae60',
                'status' => 'ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('departamentos')->insert($departamentos);
        
        $this->command->info('✅ ' . count($departamentos) . ' departamentos inseridos com sucesso!');
    }
}
