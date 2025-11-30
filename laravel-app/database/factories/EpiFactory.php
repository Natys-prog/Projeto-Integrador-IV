<?php

namespace Database\Factories;

use App\Models\Epi;
use App\Models\Funcionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Epi>
 */
class EpiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Epi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['capacete', 'oculos', 'luvas', 'botas', 'cinto_seguranca', 'mascara', 'protetor_auditivo', 'colete_refletivo'];
        $tipo = $this->faker->randomElement($tipos);
        
        $nomes = [
            'capacete' => ['Capacete de Segurança', 'Capacete Industrial', 'Capacete com Carneira', 'Capacete Classe A'],
            'oculos' => ['Óculos de Proteção', 'Óculos de Segurança', 'Óculos Anti-Respingo', 'Óculos Ampla Visão'],
            'luvas' => ['Luvas de Segurança', 'Luvas de Proteção', 'Luvas Industriais', 'Luvas Antiderrapante'],
            'botas' => ['Bota de Segurança', 'Bota Industrial', 'Bota com Biqueira', 'Bota Impermeável'],
            'cinto_seguranca' => ['Cinto de Segurança', 'Cinto Paraquedista', 'Cinto Industrial', 'Cinto Tipo Y'],
            'mascara' => ['Máscara de Proteção', 'Máscara Respiratória', 'Máscara Industrial', 'Máscara PFF2'],
            'protetor_auditivo' => ['Protetor Auricular', 'Abafador de Ruído', 'Protetor de Ouvido', 'Protetor Tipo Concha'],
            'colete_refletivo' => ['Colete Refletivo', 'Colete de Segurança', 'Colete Sinalizador', 'Colete Alta Visibilidade'],
        ];

        $fabricantes = ['3M', 'MSA', 'Honeywell', 'Uvex', 'Ansell', 'Kimberly-Clark', 'Marluvas', 'Bracol', 'Altiseg', 'Delta Plus'];
        $status = ['ativo', 'ativo', 'ativo', 'ativo', 'manutencao', 'inativo']; // 66% ativo

        return [
            'nome' => $this->faker->randomElement($nomes[$tipo]) . ' ' . $this->faker->word(),
            'tipo' => $tipo,
            'descricao' => $this->faker->sentence(),
            'codigo' => strtoupper($this->faker->unique()->bothify('???###')),
            'data_aquisicao' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'data_vencimento' => $this->faker->optional(0.8)->dateTimeBetween('now', '+3 years'), // 80% têm vencimento
            'status' => $this->faker->randomElement($status),
            'fabricante' => $this->faker->randomElement($fabricantes),
            'lote' => 'LT' . $this->faker->year() . $this->faker->numberBetween(1000, 9999),
            'funcionario_id' => $this->faker->optional(0.4)->randomElement(Funcionario::pluck('id')->toArray()), // 40% atribuídos
        ];
    }

    /**
     * EPIs próximos ao vencimento
     */
    public function proximoVencimento(): static
    {
        return $this->state(fn (array $attributes) => [
            'data_vencimento' => $this->faker->dateTimeBetween('now', '+30 days'),
            'status' => 'ativo',
        ]);
    }

    /**
     * EPIs em manutenção
     */
    public function emManutencao(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'manutencao',
            'funcionario_id' => null, // EPIs em manutenção não ficam com funcionários
        ]);
    }

    /**
     * EPIs vencidos
     */
    public function vencido(): static
    {
        return $this->state(fn (array $attributes) => [
            'data_vencimento' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => 'inativo',
            'funcionario_id' => null,
        ]);
    }
}
