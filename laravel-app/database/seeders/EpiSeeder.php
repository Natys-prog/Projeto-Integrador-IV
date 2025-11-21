<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Epi;
use App\Models\Funcionario;

class EpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funcionarios = Funcionario::all();

        $episData = [
            // Capacetes
            [
                'nome' => 'Capacete de Segurança Branco',
                'tipo' => 'capacete',
                'descricao' => 'Capacete de segurança classe A, cor branca',
                'codigo' => 'CAP001',
                'data_aquisicao' => now()->subMonths(3),
                'data_vencimento' => now()->addYears(5),
                'fabricante' => 'MSA',
                'lote' => 'LT2024001',
            ],
            [
                'nome' => 'Capacete de Segurança Azul',
                'tipo' => 'capacete',
                'descricao' => 'Capacete de segurança classe A, cor azul',
                'codigo' => 'CAP002',
                'data_aquisicao' => now()->subMonths(2),
                'data_vencimento' => now()->addYears(5),
                'fabricante' => '3M',
                'lote' => 'LT2024002',
            ],
            // Óculos de Proteção
            [
                'nome' => 'Óculos de Proteção Incolor',
                'tipo' => 'oculos',
                'descricao' => 'Óculos de proteção com lentes incolores',
                'codigo' => 'OCU001',
                'data_aquisicao' => now()->subMonths(1),
                'data_vencimento' => now()->addMonths(18),
                'fabricante' => 'Honeywell',
                'lote' => 'LT2024003',
            ],
            [
                'nome' => 'Óculos de Proteção Fumê',
                'tipo' => 'oculos',
                'descricao' => 'Óculos de proteção com lentes fumê',
                'codigo' => 'OCU002',
                'data_aquisicao' => now()->subWeeks(2),
                'data_vencimento' => now()->addMonths(18),
                'fabricante' => 'Uvex',
                'lote' => 'LT2024004',
            ],
            // Luvas
            [
                'nome' => 'Luvas de Segurança Látex',
                'tipo' => 'luvas',
                'descricao' => 'Luvas de látex natural com punho longo',
                'codigo' => 'LUV001',
                'data_aquisicao' => now()->subDays(15),
                'data_vencimento' => now()->addMonths(6),
                'fabricante' => 'Ansell',
                'lote' => 'LT2024005',
            ],
            [
                'nome' => 'Luvas de Segurança Nitrílica',
                'tipo' => 'luvas',
                'descricao' => 'Luvas de nitrilo sem pó',
                'codigo' => 'LUV002',
                'data_aquisicao' => now()->subDays(10),
                'data_vencimento' => now()->addMonths(8),
                'fabricante' => 'Kimberly-Clark',
                'lote' => 'LT2024006',
            ],
            // Botas
            [
                'nome' => 'Bota de Segurança PVC',
                'tipo' => 'botas',
                'descricao' => 'Bota de segurança em PVC com biqueira de aço',
                'codigo' => 'BOT001',
                'data_aquisicao' => now()->subMonths(4),
                'data_vencimento' => now()->addYears(2),
                'fabricante' => 'Marluvas',
                'lote' => 'LT2024007',
            ],
            [
                'nome' => 'Bota de Segurança Couro',
                'tipo' => 'botas',
                'descricao' => 'Bota de segurança em couro com solado antiderrapante',
                'codigo' => 'BOT002',
                'data_aquisicao' => now()->subMonths(2),
                'data_vencimento' => now()->addYears(3),
                'fabricante' => 'Bracol',
                'lote' => 'LT2024008',
            ],
            // Cintos de Segurança
            [
                'nome' => 'Cinto de Segurança Tipo Paraquedista',
                'tipo' => 'cinto_seguranca',
                'descricao' => 'Cinto de segurança tipo paraquedista com regulagem',
                'codigo' => 'CIN001',
                'data_aquisicao' => now()->subMonths(6),
                'data_vencimento' => now()->addYears(5),
                'fabricante' => 'Altiseg',
                'lote' => 'LT2024009',
            ],
        ];

        foreach ($episData as $epiData) {
            // Randomly assign some EPIs to funcionários
            if (rand(0, 1) && $funcionarios->count() > 0) {
                $epiData['funcionario_id'] = $funcionarios->random()->id;
            }
            
            Epi::create($epiData);
        }

        // Create additional EPIs to match dashboard numbers
        Epi::factory()->count(240)->create();

        // Create some EPIs that are close to expiration for alerts
        for ($i = 0; $i < 12; $i++) {
            Epi::create([
                'nome' => 'EPI Próximo ao Vencimento ' . ($i + 1),
                'tipo' => ['capacete', 'oculos', 'luvas', 'botas'][rand(0, 3)],
                'codigo' => 'VENC' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'data_aquisicao' => now()->subYears(2),
                'data_vencimento' => now()->addDays(rand(1, 30)),
                'funcionario_id' => $funcionarios->count() > 0 ? $funcionarios->random()->id : null,
            ]);
        }
    }
}
