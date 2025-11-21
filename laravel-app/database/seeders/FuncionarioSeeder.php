<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Funcionario;

class FuncionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funcionarios = [
            [
                'nome' => 'João Silva',
                'cpf' => '12345678901',
                'email' => 'joao.silva@empresa.com',
                'telefone' => '(11) 99999-1111',
                'departamento' => 'producao',
                'cargo' => 'Operador de Máquina',
                'data_admissao' => now()->subMonths(6),
                'status' => 'ativo',
                'endereco' => 'Rua das Flores, 123',
                'cep' => '01234567',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
            ],
            [
                'nome' => 'Maria Santos',
                'cpf' => '23456789012',
                'email' => 'maria.santos@empresa.com',
                'telefone' => '(11) 99999-2222',
                'departamento' => 'manutencao',
                'cargo' => 'Técnica de Manutenção',
                'data_admissao' => now()->subDays(15),
                'status' => 'ativo',
                'endereco' => 'Av. Principal, 456',
                'cep' => '02345678',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
            ],
            [
                'nome' => 'Pedro Oliveira',
                'cpf' => '34567890123',
                'email' => 'pedro.oliveira@empresa.com',
                'telefone' => '(11) 99999-3333',
                'departamento' => 'logistica',
                'cargo' => 'Supervisor de Logística',
                'data_admissao' => now()->subYears(2),
                'status' => 'ativo',
                'endereco' => 'Rua do Comércio, 789',
                'cep' => '03456789',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
            ],
            [
                'nome' => 'Ana Costa',
                'cpf' => '45678901234',
                'email' => 'ana.costa@empresa.com',
                'telefone' => '(11) 99999-4444',
                'departamento' => 'producao',
                'cargo' => 'Operadora de Produção',
                'data_admissao' => now()->subMonths(3),
                'status' => 'ativo',
                'endereco' => 'Rua da Indústria, 321',
                'cep' => '04567890',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
            ],
            [
                'nome' => 'Carlos Ferreira',
                'cpf' => '56789012345',
                'email' => 'carlos.ferreira@empresa.com',
                'telefone' => '(11) 99999-5555',
                'departamento' => 'manutencao',
                'cargo' => 'Eletricista',
                'data_admissao' => now()->subYear(),
                'status' => 'ativo',
                'endereco' => 'Rua da Energia, 654',
                'cep' => '05678901',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
            ],
        ];

        foreach ($funcionarios as $funcionario) {
            Funcionario::create($funcionario);
        }

        // Create additional funcionários to reach the numbers shown in dashboard
        Funcionario::factory()->count(80)->create();
    }
}
