<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Epi;
use App\Models\Funcionario;
use Carbon\Carbon;

class EpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar funcionários existentes
        $funcionarios = Funcionario::all();

        // Buscar tipos de EPI para usar os IDs corretos
        $tipoCapacete = \App\Models\TipoEpi::where('codigo', 'capacete')->first();
        $tipoOculos = \App\Models\TipoEpi::where('codigo', 'oculos')->first();
        $tipoLuvas = \App\Models\TipoEpi::where('codigo', 'luvas')->first();
        $tipoBotas = \App\Models\TipoEpi::where('codigo', 'botas')->first();
        $tipoCintoSeguranca = \App\Models\TipoEpi::where('codigo', 'cinto_seguranca')->first();
        $tipoMascara = \App\Models\TipoEpi::where('codigo', 'mascara')->first();
        $tipoProtetorAuditivo = \App\Models\TipoEpi::where('codigo', 'protetor_auditivo')->first();
        $tipoColeteRefletivo = \App\Models\TipoEpi::where('codigo', 'colete_refletivo')->first();

        $episData = [
            // Capacetes
            [
                'nome' => 'Capacete de Segurança Branco MSA',
                'tipo_epi_id' => $tipoCapacete?->id ?? 1,
                'descricao_manual_tipo_epi' => 'Capacete de segurança classe A, cor branca, com suspensão de 4 pontos',
                'codigo' => 'CAP001',
                'data_aquisicao' => now()->subMonths(3),
                'data_vencimento' => now()->addYears(5),
                'status' => 'ativo',
                'fabricante' => 'MSA',
                'lote' => 'LT2024001',
                'funcionario_id' => null,
            ],
            [
                'nome' => 'Capacete de Segurança Azul 3M',
                'tipo_epi_id' => $tipoCapacete?->id ?? 1,
                'descricao_manual_tipo_epi' => 'Capacete de segurança classe A, cor azul, resistente a impactos',
                'codigo' => 'CAP002',
                'data_aquisicao' => now()->subMonths(2),
                'data_vencimento' => now()->addYears(5),
                'status' => 'ativo',
                'fabricante' => '3M',
                'lote' => 'LT2024002',
                'funcionario_id' => null,
            ],

            // Óculos de Proteção
            [
                'nome' => 'Óculos de Proteção Incolor Honeywell',
                'tipo_epi_id' => $tipoOculos?->id ?? 2,
                'descricao_manual_tipo_epi' => 'Óculos de proteção com lentes incolores, anti-embaçante',
                'codigo' => 'OCU001',
                'data_aquisicao' => now()->subMonths(1),
                'data_vencimento' => now()->addMonths(18),
                'status' => 'ativo',
                'fabricante' => 'Honeywell',
                'lote' => 'LT2024003',
                'funcionario_id' => null,
            ],
            [
                'nome' => 'Óculos de Proteção Fumê Uvex',
                'tipo_epi_id' => $tipoOculos?->id ?? 2,
                'descricao_manual_tipo_epi' => 'Óculos de proteção com lentes fumê, proteção UV',
                'codigo' => 'OCU002',
                'data_aquisicao' => now()->subWeeks(2),
                'data_vencimento' => now()->addMonths(18),
                'status' => 'ativo',
                'fabricante' => 'Uvex',
                'lote' => 'LT2024004',
                'funcionario_id' => null,
            ],

            // Luvas
            [
                'nome' => 'Luvas de Segurança Látex Ansell',
                'tipo_epi_id' => $tipoLuvas?->id ?? 3,
                'descricao_manual_tipo_epi' => 'Luvas de látex natural com punho longo, antiderrapante',
                'codigo' => 'LUV001',
                'data_aquisicao' => now()->subDays(15),
                'data_vencimento' => now()->addMonths(6),
                'status' => 'ativo',
                'fabricante' => 'Ansell',
                'lote' => 'LT2024005',
                'funcionario_id' => null,
            ],
            [
                'nome' => 'Luvas de Segurança Nitrílica Kimberly-Clark',
                'tipo_epi_id' => $tipoLuvas?->id ?? 3,
                'descricao_manual_tipo_epi' => 'Luvas de nitrilo sem pó, alta resistência química',
                'codigo' => 'LUV002',
                'data_aquisicao' => now()->subDays(10),
                'data_vencimento' => now()->addMonths(8),
                'status' => 'ativo',
                'fabricante' => 'Kimberly-Clark',
                'lote' => 'LT2024006',
                'funcionario_id' => null,
            ],

            // Botas
            [
                'nome' => 'Bota de Segurança PVC Marluvas',
                'tipo_epi_id' => $tipoBotas?->id ?? 3, // usar luvas como fallback
                'descricao_manual_tipo_epi' => 'Bota de segurança em PVC com biqueira de aço, impermeável',
                'codigo' => 'BOT001',
                'data_aquisicao' => now()->subMonths(4),
                'data_vencimento' => now()->addYears(2),
                'status' => 'ativo',
                'fabricante' => 'Marluvas',
                'lote' => 'LT2024007',
                'funcionario_id' => null,
            ],
            [
                'nome' => 'Bota de Segurança Couro Bracol',
                'tipo_epi_id' => $tipoBotas?->id ?? 3, // usar luvas como fallback
                'descricao_manual_tipo_epi' => 'Bota de segurança em couro com solado antiderrapante',
                'codigo' => 'BOT002',
                'data_aquisicao' => now()->subMonths(2),
                'data_vencimento' => now()->addYears(3),
                'status' => 'ativo',
                'fabricante' => 'Bracol',
                'lote' => 'LT2024008',
                'funcionario_id' => null,
            ],

            // Cintos de Segurança
            [
                'nome' => 'Cinto de Segurança Paraquedista Altiseg',
                'tipo_epi_id' => $tipoCintoSeguranca?->id ?? 1, // usar capacete como fallback
                'descricao_manual_tipo_epi' => 'Cinto de segurança tipo paraquedista com regulagem',
                'codigo' => 'CIN001',
                'data_aquisicao' => now()->subMonths(6),
                'data_vencimento' => now()->addYears(5),
                'status' => 'ativo',
                'fabricante' => 'Altiseg',
                'lote' => 'LT2024009',
                'funcionario_id' => null,
            ],

            // Máscaras
            [
                'nome' => 'Máscara Respiratória PFF2 3M',
                'tipo_epi_id' => $tipoMascara?->id ?? 2, // usar óculos como fallback
                'descricao_manual_tipo_epi' => 'Máscara respiratória descartável PFF2, filtro de partículas',
                'codigo' => 'MAS001',
                'data_aquisicao' => now()->subWeeks(1),
                'data_vencimento' => now()->addMonths(3),
                'status' => 'ativo',
                'fabricante' => '3M',
                'lote' => 'LT2024010',
                'funcionario_id' => null,
            ],

            // Protetor Auditivo
            [
                'nome' => 'Protetor Auditivo Tipo Concha Honeywell',
                'tipo_epi_id' => $tipoProtetorAuditivo?->id ?? 2, // usar óculos como fallback
                'descricao_manual_tipo_epi' => 'Protetor auditivo tipo concha, atenuação 28dB',
                'codigo' => 'PRO001',
                'data_aquisicao' => now()->subDays(30),
                'data_vencimento' => now()->addYears(1),
                'status' => 'ativo',
                'fabricante' => 'Honeywell',
                'lote' => 'LT2024011',
                'funcionario_id' => null,
            ],

            // Colete Refletivo
            [
                'nome' => 'Colete Refletivo Alta Visibilidade',
                'tipo_epi_id' => $tipoColeteRefletivo?->id ?? 1, // usar capacete como fallback
                'descricao_manual_tipo_epi' => 'Colete refletivo classe 2, tecido mesh respirável',
                'codigo' => 'COL001',
                'data_aquisicao' => now()->subWeeks(3),
                'data_vencimento' => now()->addYear(),
                'status' => 'ativo',
                'fabricante' => 'Delta Plus',
                'lote' => 'LT2024012',
                'funcionario_id' => null,
            ],
        ];

        // Criar EPIs básicos
        foreach ($episData as $epiData) {
            // Atribuir alguns EPIs aleatoriamente a funcionários (30% de chance)
            if ($funcionarios->count() > 0 && rand(0, 100) < 30) {
                $epiData['funcionario_id'] = $funcionarios->random()->id;
            }
            
            Epi::create($epiData);
        }

        // Criar EPIs adicionais usando Factory (para atingir números do dashboard)
        Epi::factory()->count(240)->create();

        // Criar EPIs próximos ao vencimento (para alertas)
        $this->createExpiringEpis();
        
        // Criar EPIs em manutenção
        $this->createMaintenanceEpis();
    }

    /**
     * Criar EPIs próximos ao vencimento
     */
    private function createExpiringEpis()
    {
        $tiposIds = [1, 2, 3, 4, 6]; // capacete, oculos, luvas, botas, mascara
        
        for ($i = 1; $i <= 15; $i++) {
            Epi::create([
                'nome' => 'EPI Próximo Vencimento ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tipo_epi_id' => $tiposIds[array_rand($tiposIds)],
                'codigo' => 'VENC' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'descricao_manual_tipo_epi' => 'EPI com vencimento próximo para teste de alertas',
                'data_aquisicao' => now()->subYears(2),
                'data_vencimento' => now()->addDays(rand(1, 30)), // 1 a 30 dias
                'status' => 'ativo',
                'fabricante' => ['3M', 'MSA', 'Honeywell'][array_rand(['3M', 'MSA', 'Honeywell'])],
                'lote' => 'LT2024' . str_pad($i + 100, 3, '0', STR_PAD_LEFT),
                'funcionario_id' => null,
            ]);
        }
    }

    /**
     * Criar EPIs em manutenção
     */
    private function createMaintenanceEpis()
    {
        $tiposIds = [1, 4, 5]; // capacete, botas, cinto_seguranca
        
        for ($i = 1; $i <= 8; $i++) {
            Epi::create([
                'nome' => 'EPI Manutenção ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tipo_epi_id' => $tiposIds[array_rand($tiposIds)],
                'codigo' => 'MANU' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'descricao_manual_tipo_epi' => 'EPI em processo de manutenção',
                'data_aquisicao' => now()->subMonths(6),
                'data_vencimento' => now()->addMonths(6),
                'status' => 'manutencao',
                'fabricante' => ['MSA', 'Bracol', 'Altiseg'][array_rand(['MSA', 'Bracol', 'Altiseg'])],
                'lote' => 'LT2024' . str_pad($i + 200, 3, '0', STR_PAD_LEFT),
                'funcionario_id' => null,
            ]);
        }
    }
}
