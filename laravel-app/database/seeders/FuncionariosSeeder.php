<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuncionariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando inserção de funcionários...');

        // Verificar se já existem
        if (DB::table('funcionarios')->count() > 0) {
            $this->command->info('ℹ️  Funcionários já existem, pulando...');
            return;
        }

        $funcionarios = [
            [
                'nome' => 'João Silva',
                'matricula' => 'W001',
                'email' => 'joao@empresa.com',
                'cpf' => '11111111111',
                'telefone' => '(11) 99999-0001',
                'status' => 'ativo',
                'data_admissao' => '2020-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Maria Costa',
                'matricula' => 'W002', 
                'email' => 'maria@empresa.com',
                'cpf' => '22222222222',
                'telefone' => '(11) 99999-0002',
                'status' => 'ativo',
                'data_admissao' => '2020-02-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Carlos Lima',
                'matricula' => 'W003',
                'email' => 'carlos@empresa.com', 
                'cpf' => '33333333333',
                'telefone' => '(11) 99999-0003',
                'status' => 'ativo',
                'data_admissao' => '2020-03-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('funcionarios')->insert($funcionarios);
        
        $this->command->info('✅ ' . count($funcionarios) . ' funcionários criados!');
    }
}
